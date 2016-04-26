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
import java.util.Objects;

public class FacultyList extends Activity {

    private ListView listView = null;
    private EditText search;
    private ArrayAdapter<String> arrayAdapter = null;
    private String[] facultyList;
    private String[] roomList;
    private String[] departments;
    private String[] departmentIds;
    private String[] departmentId2;

    private String departmentId = "";


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.faculty_list);

        departments = getIntent().getStringArrayExtra("departments");
        departmentId = getIntent().getStringExtra("department");

        listView = (ListView) findViewById(R.id.listView);
        listView.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                String faculty = (String)parent.getItemAtPosition(position);
                String room = roomList[position];
                String dept1 = departmentIds[position];
                String dept2 = departmentId2[position];
                startExplicitActivation(faculty, room, dept1, dept2);
            }
        });

        search = (EditText) findViewById(R.id.editText);
        search.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {

            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
                FacultyList.this.arrayAdapter.getFilter().filter(s);
            }

            @Override
            public void afterTextChanged(Editable s) {

            }
        });

        final String url = "https://people.rit.edu/wrg1932/database/facjson.php";
        new AsyncHttpTask().execute(url);
    }

    public void startExplicitActivation(String faculty, String room, String dept1, String dept2){
        Intent explicitIntent = new Intent(FacultyList.this,FacultyDetails.class);
        explicitIntent.putExtra("name", faculty);
        explicitIntent.putExtra("room", room);
        explicitIntent.putExtra("deptMain", dept1);
        explicitIntent.putExtra("deptSec", dept2);
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
                arrayAdapter = new ArrayAdapter(FacultyList.this, android.R.layout.simple_list_item_1, facultyList);
                listView.setAdapter(arrayAdapter);
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
                JSONArray faculty = response.optJSONArray("faculty");

                int count = 0;
                int count2 = 0;
                for(int i=0;i<faculty.length();i++){
                    JSONObject fac = faculty.optJSONObject(i);
                    if(fac.optString("departmentId").equalsIgnoreCase(departmentId)){
                        count++;
                    }
                }
                facultyList = new String[count];
                roomList = new String[count];
                departmentIds = new String[count];
                departmentId2 = new String[count];

                for (int i = 0; i < faculty.length(); i++) {
                    JSONObject fac = faculty.optJSONObject(i);
                    if(fac.optString("departmentId").equalsIgnoreCase(departmentId) || fac.optString("secondaryDepartmentId").equalsIgnoreCase(departmentId)){
                        String name = fac.optString("fName") + " " + fac.optString("lName");
                        facultyList[count2] = name;
                        String room = fac.optString("roomNumber");
                        roomList[count2] = room;
                        String deptId = fac.optString("departmentId");
                        departmentIds[count2] = deptId;
                        if(fac.optString("secondaryDepartmentId").equalsIgnoreCase("null")){
                            departmentId2[count2] = "None";
                        }else{
                            departmentId2[count2] = fac.optString("secondaryDepartmentId");
                        }
                        count2++;
                    };
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
        }
    }

}