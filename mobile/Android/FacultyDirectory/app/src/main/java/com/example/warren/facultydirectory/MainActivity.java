package com.example.warren.facultydirectory;

import android.app.Activity;
import android.content.Intent;
import android.graphics.drawable.Drawable;
import android.media.Image;
import android.os.AsyncTask;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.Spinner;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemSelectedListener;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import java.io.BufferedInputStream;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;

/**
 * @author Blue Team
 * @version 1.0.0
 * @since 2016-05-03
 */

public class MainActivity extends Activity {

    private ArrayAdapter<String> arrayAdapter = null;
    private String[] departments, deptId, buildingLabel;
    private Button button1, button2, button3;
    private String choice;
    private boolean sortByRoom = false;
    private Spinner building;
    private ImageView image;

    //Called when app is opened
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        button1 = (Button) findViewById(R.id.button1);
        button2 = (Button) findViewById(R.id.button2);
        button3 = (Button) findViewById(R.id.button3);
        buildingLabel = getResources().getStringArray(R.array.buildings);
        building = (Spinner) findViewById(R.id.buildingSpinner);
        building.setSelection(0);
        sortByRoom = false;

        ArrayAdapter<CharSequence> adapter = ArrayAdapter.createFromResource(this, R.array.buildings, android.R.layout.simple_spinner_item);
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        building.setAdapter(adapter);
        building.setOnItemSelectedListener(new MyOnItemSelectedListener());

        final String url = "https://people.rit.edu/wrg1932/database/deptjson.php";
        new AsyncHttpTask().execute(url);
    }

    //Listener for building spinner
    class MyOnItemSelectedListener implements OnItemSelectedListener{
        @Override
        public void onItemSelected(AdapterView<?> parentView, View selectedItemView, int position, long id){
            int index = parentView.getSelectedItemPosition();
            if(index == 0) {
            }else {
                choice = buildingLabel[index];
                sortByRoom = true;
                startExplicitActivation(choice);
            }
        }

        @Override
        public void onNothingSelected(AdapterView<?> view){
        }
    }

    //Start explicit activity to go to faculty list
    public void startExplicitActivation(String option){
        Intent explicitIntent = new Intent(MainActivity.this,FacultyList.class);
        if(sortByRoom){
            explicitIntent.putExtra("building", option);
            explicitIntent.putExtra("department", "none");
        }else{
            explicitIntent.putExtra("building", "none");
            explicitIntent.putExtra("department", option);
        }
        explicitIntent.putExtra("departments", departments);
        startActivity(explicitIntent);
    }

    //Accesses the php file that returns the database in json format
    public class AsyncHttpTask extends AsyncTask<String, Void, Integer> {

        @Override
        protected Integer doInBackground(String... params) {
            InputStream is = null;
            HttpURLConnection urlConnection = null;
            Integer result = 0;
            try {
                URL url = new URL(params[0]); //change this address
                urlConnection = (HttpURLConnection) url.openConnection();

                //Optional request header
                urlConnection.setRequestProperty("Context-Type", "application/json");
                urlConnection.setRequestProperty("Accept", "application/json");

                //for Get request
                urlConnection.setRequestMethod("GET");
                int statusCode = urlConnection.getResponseCode();
                //200 represents HTTP OK
                if (statusCode == 200) {
                    is = new BufferedInputStream(urlConnection.getInputStream());
                    String response = convertInputStreamToString(urlConnection.getInputStream());
                    parseResult(response);
                    result = 1; //Successful
                } else {
                    result = 0; //Failed to fetch data!//
                }
            } catch (Exception e) {
                Log.d("log_tag", e.getLocalizedMessage());
            }
            return result; //Failed to fetch data
        }

        //set the UI up
        protected void onPostExecute(Integer result) {
            //Update UI
            if (result == 1) {
                button1.setText(departments[0]);
                button1.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        sortByRoom = false;
                        startExplicitActivation(deptId[0]);
                    }
                });

                button2.setText(departments[1]);
                button2.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        sortByRoom = false;
                        startExplicitActivation(deptId[1]);
                    }
                });

                button3.setText(departments[2]);
                button3.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        sortByRoom = false;
                        startExplicitActivation(deptId[2]);
                    }
                });
            } else {
                Log.e("log_tag", "Failed to fetch data!");
            }
        }

        //Reads through the bufferedInputStream and turns to string
        private String convertInputStreamToString(InputStream is) throws IOException {
            BufferedReader bufferedReader = new BufferedReader(new InputStreamReader(is));
            String line = "";
            String result = "";
            while ((line = bufferedReader.readLine()) != null) {
                result += line;
            }

            if (null != is) {
                is.close();
            }
            return result;
        }

        //parses json and converts data to populate list view of faculty
        private void parseResult(String result) {
            try {
                JSONObject response = new JSONObject(result);
                JSONArray department = response.optJSONArray("department");
                departments = new String[department.length()];
                deptId = new String[department.length()];
                for (int i = 0; i < department.length(); i++) {
                    JSONObject dept = department.optJSONObject(i);
                    String name = dept.optString("departmentName");
                    String id = dept.optString("departmentId");
                    departments[i] = name;
                    deptId[i] = id;
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
        }
    }

}