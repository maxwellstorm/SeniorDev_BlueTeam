package com.example.warren.facultydirectory;

import android.app.Activity;
import android.os.Bundle;
import android.util.Log;
import android.widget.TextView;

/**
 * @author Blue Team
 * @version 1.0.0
 * @since 2016-05-03
 */


public class FacultyDetails extends Activity{

    private TextView facultyName;
    private TextView roomNumber;
    private TextView deptMain;
    private TextView deptSec;

    private String facultyNameData = null;
    private String roomNumberData = null;
    private String deptMainData = null;
    private String deptSecData = null;

    private String[] departments;

    /**
     * This creates the gui of the faculty details view. It then calls the method to set the data
     *
     * @param savedInstanceState
     */
    @Override
    public void onCreate(Bundle savedInstanceState){
        super.onCreate(savedInstanceState);
        setContentView(R.layout.faculty_details);
        facultyName = (TextView) findViewById(R.id.facultyName);
        roomNumber = (TextView) findViewById(R.id.roomNumber);
        deptMain = (TextView) findViewById(R.id.deptMain);
        deptSec = (TextView) findViewById(R.id.deptSec);
        setData();
    }

    /**
     * Sets the values for each label with the details of the faculty chosen
     */
    public void setData(){
        facultyNameData = getIntent().getStringExtra("name");
        facultyName.setText(facultyNameData);

        roomNumberData = getIntent().getStringExtra("room");
        roomNumber.setText(roomNumberData);


        departments = getIntent().getStringArrayExtra("departments");

        deptMainData = getIntent().getStringExtra("deptMain");
        deptMain.setText(departments[Integer.parseInt(deptMainData)-1]);

        deptSecData = getIntent().getStringExtra("deptSec");
        if(deptSecData.equalsIgnoreCase("None")){
            deptSec.setText("None");
        }else {
            deptSec.setText(departments[Integer.parseInt(deptSecData)-1]);
        }
    }
}
