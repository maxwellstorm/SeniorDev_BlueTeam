package com.example.warren.facultydirectory;

import android.app.Activity;
import android.content.Intent;
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
import android.widget.ListView;
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

public class MainActivity extends Activity {

    private ArrayAdapter<String> arrayAdapter = null;
    private String[] departments, deptId;
    private Button button1, button2, button3;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        button1 = (Button) findViewById(R.id.button1);
        button2 = (Button) findViewById(R.id.button2);
        button3 = (Button) findViewById(R.id.button3);

        final String url = "https://people.rit.edu/wrg1932/database/deptjson.php";
        new AsyncHttpTask().execute(url);
    }

    public void startExplicitActivation(String department){
        Intent explicitIntent = new Intent(MainActivity.this,FacultyList.class);
        explicitIntent.putExtra("department", department);
        explicitIntent.putExtra("departments", departments);
        startActivity(explicitIntent);
    }

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

        protected void onPostExecute(Integer result) {
            //Update UI
            if (result == 1) {
                button1.setText(departments[0]);
                button1.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        startExplicitActivation(deptId[0]);
                    }
                });

                button2.setText(departments[1]);
                button2.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        startExplicitActivation(deptId[1]);
                    }
                });

                button3.setText(departments[2]);
                button3.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        startExplicitActivation(deptId[2]);
                    }
                });
            } else {
                Log.e("log_tag", "Failed to fetch data!");
            }
        }

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