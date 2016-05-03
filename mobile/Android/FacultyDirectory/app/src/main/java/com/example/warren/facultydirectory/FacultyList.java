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

/**
 * @author Blue Team
 * @version 1.0.0
 * @since 2016-05-03
 */

public class FacultyList extends Activity {

    private ListView listView = null;
    private EditText search;
    private ArrayAdapter<String> arrayAdapter = null;
    private String[] facultyList,roomList, departments, departmentIds, departmentId2;
    private String departmentId = "";
    private String building;

    /**
     * This creates the gui of the list view of the app. This includes setting on click listeners for each list item
     * Also sets the listener for the search bar function
     *
     * @param savedInstanceState
     */
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.faculty_list);

        departments = getIntent().getStringArrayExtra("departments");
        departmentId = getIntent().getStringExtra("department");
        building = getIntent().getStringExtra("building");

        //list view listener activated when a faculty is selected
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

        //search bar listener
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

        final String url = "http://kelvin.ist.rit.edu/~blueteam/database/facjson.php";
        new AsyncHttpTask().execute(url);
    }

    /**
     * Start explicit activity to go to faculty details and send the according data
     *
     * @param faculty: name of the faculty
     * @param room: faculty's room
     * @param dept1: main department of the faculty
     * @param dept2: secondary department of the faculty
     */
    public void startExplicitActivation(String faculty, String room, String dept1, String dept2){
        Intent explicitIntent = new Intent(FacultyList.this,FacultyDetails.class);
        explicitIntent.putExtra("name", faculty);
        explicitIntent.putExtra("room", room);
        explicitIntent.putExtra("deptMain", dept1);
        explicitIntent.putExtra("deptSec", dept2);
        explicitIntent.putExtra("departments", departments);
        startActivity(explicitIntent);
    }

    /**
     *  Accesses the php file that returns the database in json format
     */
    public class AsyncHttpTask extends AsyncTask<String, Void, Integer> {

        /**
         * This reads the url and takes the data from it
         *
         * @param params
         * @return The result if it was successful or not
         */
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

        /**
         * Set the UI up
         *
         * @param result
         */
        protected void onPostExecute(Integer result) {
            //Update UI
            if (result == 1) {
                arrayAdapter = new ArrayAdapter(FacultyList.this, android.R.layout.simple_list_item_1, facultyList);
                listView.setAdapter(arrayAdapter);
            } else {
                Log.e("log_tag", "Failed to fetch data!");
            }
        }

        /**
         * Reads through the bufferedInputStream and turns it to string
         *
         * @param is inputStream from the url
         * @return
         * @throws IOException
         */
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

        /**
         * Parses json and converts data to populate faculty details in the list view accordingly with the selected filters
         *
         * @param result
         */
        private void parseResult(String result) {
            try {
                JSONObject response = new JSONObject(result);
                JSONArray faculty = response.optJSONArray("faculty");

                int count = 0;
                int count2 = 0;
                if(departmentId.equalsIgnoreCase("all")){
                    count = faculty.length();
                }else {
                    for (int i = 0; i < faculty.length(); i++) {
                        JSONObject fac = faculty.optJSONObject(i);
                        if (departmentId.equals("none")) {
                            if (fac.optString("roomNumber").substring(0, 3).equalsIgnoreCase(building)) {
                                count++;
                            }
                        } else {
                            if (fac.optString("departmentId").equalsIgnoreCase(departmentId) || fac.optString("secondaryDepartmentId").equalsIgnoreCase(departmentId)) {
                                count++;
                            }
                        }
                    }
                }
                facultyList = new String[count];
                roomList = new String[count];
                departmentIds = new String[count];
                departmentId2 = new String[count];

                for (int i = 0; i < faculty.length(); i++) {
                    JSONObject fac = faculty.optJSONObject(i);
                    if(departmentId.equals("none")){
                        if(fac.optString("roomNumber").substring(0,3).equalsIgnoreCase(building)){
                            String name = fac.optString("fName") + " " + fac.optString("lName");
                            facultyList[count2] = name;
                            String room = fac.optString("roomNumber");
                            roomList[count2] = room;
                            String deptId = fac.optString("departmentId");
                            departmentIds[count2] = deptId;
                            if (fac.optString("secondaryDepartmentId").equalsIgnoreCase("null")) {
                                departmentId2[count2] = "None";
                            } else {
                                departmentId2[count2] = fac.optString("secondaryDepartmentId");
                            }
                            count2++;
                        }
                    }else {
                        if(departmentId.equalsIgnoreCase("All")){
                            String name = fac.optString("fName") + " " + fac.optString("lName");
                            facultyList[count2] = name;
                            String room = fac.optString("roomNumber");
                            roomList[count2] = room;
                            String deptId = fac.optString("departmentId");
                            departmentIds[count2] = deptId;
                            if (fac.optString("secondaryDepartmentId").equalsIgnoreCase("null")) {
                                departmentId2[count2] = "None";
                            } else {
                                departmentId2[count2] = fac.optString("secondaryDepartmentId");
                            }
                            count2++;
                        }else {
                            if (fac.optString("departmentId").equalsIgnoreCase(departmentId) || fac.optString("secondaryDepartmentId").equalsIgnoreCase(departmentId)) {
                                String name = fac.optString("fName") + " " + fac.optString("lName");
                                facultyList[count2] = name;
                                String room = fac.optString("roomNumber");
                                roomList[count2] = room;
                                String deptId = fac.optString("departmentId");
                                departmentIds[count2] = deptId;
                                if (fac.optString("secondaryDepartmentId").equalsIgnoreCase("null")) {
                                    departmentId2[count2] = "None";
                                } else {
                                    departmentId2[count2] = fac.optString("secondaryDepartmentId");
                                }
                                count2++;
                            }
                        }
                    }
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
        }
    }

}