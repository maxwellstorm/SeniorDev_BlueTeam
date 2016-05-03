//THIS JS FILE CONTROLS CLIENTSIDE FUNCTIONALITY FOR THE ADMINISTRATIVE PORTAL

/**
 * A function to allow for image preview before form upload on the Employee page
 * This populates the "No Image Available" section of the form with the image that will be uploaded
 */
var loadFile = function(event) {
    var img = document.getElementById('userImage');
    img.src = URL.createObjectURL(event.target.files[0]);
};


/**
 * A method to set an Employee to active (i.e. highlight their entry and populate the fields with their information) 
 * when their entry is selected from the search column
 * @param active The employee (a <li>) to be set as active
 */
function setEmployeeActive(active) {
	$('#results li').removeClass("activeResult"); //remove active from all <li> in the search column - only 1 Employee can be active at a time
	active.className += " activeResult";
	document.getElementById('editBtn').disabled = false;

	getInfo(active);
	$('#addEmployee').data('formValidation').resetForm();
}

/**
 * A method to set an Administrator to active (i.e. highlight their entry and populate the fields with their information) 
 * when their entry is selected from the search column
 * @param active The Administrator (a <li>) to be set as active
 */
function setAdminActive(active) {
	$('#results li').removeClass("activeResult"); //remove active from all <li> in the search column - only Admin can be active at a time
	active.className += " activeResult";
	document.getElementById('editBtn').disabled = false;

	getAdminInfo(active);
	$('#addAdmin').data('formValidation').resetForm();
}

/**
 * A function to format the phone number inputted in the Employee form
 * If the user just enters the 10 digits, this will add the parentheses and hypen to put it in the appropriate form
 */
function formatPhoneNum(phone) {
	if(phone.value.length == 10) { //If user inputs only the numbers
		//Set the field value to (###) ###-####
		phone.value = "(" + phone.value.slice(0,3) + ") " + phone.value.slice(3, 6) + "-" + phone.value.slice(6,10);
		$('#addEmployee').formValidation('revalidateField', 'phone'); //"revalidate" the form so it correctly displays the approved status
	}
}

/**
 * A function to check if the returned AJAX content contains the HTML for an error message
 * This is used to determine what ot do with AJAX responses
 * @param response The response from the AJAX call
 * @return true/false Whether or not the response is an error message
 */
function checkAJAXError(response) {
	if(response.indexOf("<div class='alert alert-dismissible") >= 0) { //If that HTML fragment is in the response, it is an error message
		return true;
	} else {
		return false;
	}
}

/**
 * A function to send an AJAX call to the database when an Employee is selected from the search column to retrieve all of their info
 * @param selected The selected employee's <li> in the search column
 */
function getInfo(selected) {
	var facId = $(selected).children('.fId').text();

	//AJA Call
	$.ajax({
		method: "GET",
		url: "../database/getInfo.php",
		data: {facultyId : facId, page : "employee"} //Send the faculty ID for the query, and the name of the page, so the correct info is returned
	}).done(function(response) { //on successful completion of the AJAX call
		
		console.log(response);
		var infoResponse = JSON.parse(response); //parse the returned JSON object
		if(checkAJAXError(response)) { //If the returned JSON is an error message, append it above the form on the HTML page
			$('.form-horizontal').before(infoResponse);
		} else { //If it isn't an error message, then populate the form with the results
			$('#facultyId').val(infoResponse["facultyId"]);
			$('#firstName').val(infoResponse["fName"]);
			$('#lastName').val(infoResponse["lName"]);
			$('#title').val(infoResponse["title"]);
			$('#email').val(infoResponse["email"]);
			$('#phone').val(infoResponse["phone"]);

			//Selectpicker enables a plugin that allows for live search in a select and for selection of multiple elements
			$('#room').selectpicker('val', infoResponse["roomNumber"]);
			$('#depts').selectpicker('val', [infoResponse["departmentName"], infoResponse["secondaryDepartmentName"]]);
			
			var isActive = infoResponse['isActive'];
			var isFaculty = infoResponse['isFaculty'];

			if(isActive == 0) { //If the active value is 0, set that the employee is inactive
				document.getElementById('activeNo').checked = true;
				document.getElementById('activeYes').checked = false;
			} else { //If the active value is 1, set that the employee is active
				document.getElementById('activeNo').checked = false;
				document.getElementById('activeYes').checked = true;
			}

			if(isFaculty == 0) { //If the faculty value is 0, set that the employee is Staff
				document.getElementById('facultyNo').checked = true;
				document.getElementById('facultyYes').checked = false;
			} else { //If the faculty value is 1, set that the employee is faculty
				document.getElementById('facultyNo').checked = false;
				document.getElementById('facultyYes').checked = true;
			}

			$('#about').val(infoResponse["about"]);
			$('#education').val(infoResponse["education"]);
			$('#highlights').val(infoResponse["highlights"]);
			var imagePath = infoResponse['imageName'];

			if(imagePath) { //If the image path data exists, set the preview image to that image
				$('#userImage').attr("src", imagePath);
			} else { //If nothing is set, set it to the default image
				$('#userImage').attr("src", 'media/no-preview.png');
			}
		}
	})
}

/**
 * A function to send an AJAX call to the database when an Admin is selected from the search column to retrieve all of their info
 * @param selected The selected admin's <li> in the search column
 */
function getAdminInfo(selected) {
	var aId = $(selected).children('.aId').text();

	//AJAX call
	$.ajax({
		method: "GET",
		url: "../database/getInfo.php",
		data: {adminId : aId, page : "admin"} //Send the admin ID for the query, and the name of the page, so the correct info is returned
	}).done(function(response) {
		var infoResponse = JSON.parse(response);
		
		if(checkAJAXError(response)) { //If the returned JSON is an error message, append it above the form on the HTML page
			$('.form-horizontal').before(infoResponse);
		} else { //If it isn't an error message, then populate the form with the results
			$('#adminId').val(infoResponse["AdminId"]);
			$('#firstName').val(infoResponse["fName"]);
			$('#lastName').val(infoResponse["lName"]);
			$('#username').val(infoResponse["username"]);
			$('#accessLevel').val(infoResponse["accessLevel"]);
			$('#department').val(infoResponse["departmentName"]);
		}
	})
}

/**
 * A function to send an AJAX call to the database when a room is selected from the dropdown menu to retrieve all of their info
 * @param selectedVal The selected room's Room Number
 */
function getRoomInfo(selectedVal) {
	$.ajax({
		method: "GET",
		url: "../database/getInfo.php",
		data: {room : selectedVal, page : "room"} //Send the room number for the query, and the name of the page, so the correct info is returned
	}).done(function(response) {
		var infoResponse = JSON.parse(response);
		
		if(checkAJAXError(response)) { //If the returned JSON is an error message, append it above the form on the HTML page
			$('.form-horizontal').before(infoResponse);
		} else { //If it isn't an error message, then populate the form with the results
			$('#room').val(infoResponse["roomNumber"]);
			$('#description').val(infoResponse["description"]);
			$('#posX').val(infoResponse['posX']);
			$('#posY').val(infoResponse['posY']);
			var imagePath = infoResponse['roomMap'];

			//This code enables the snap.svg plugin. This allows annotation of the floor plan to show room locations
			var s = Snap("#floorPlan");
			makePoint(infoResponse['posX'], infoResponse['posY'], s); //Create the point on the map showing the selected room's location
			prepMap(s, 5); //prepare & instantiate the SVG annotation map
		}
	})
}

/**
 * A function to send an AJAX call to the database when a department is selected from the dropdown menu to retrieve all of their info
 * @param selectedVal The selected department's ID Number
 */
function getDepartmentInfo(selectedVal) {
	$.ajax({
		method: "GET",
		url: "../database/getInfo.php",
		data: {deptId : selectedVal, page : "department"} //Send the Department ID for the query, and the name of the page, so the correct info is returned
	}).done(function(response) {
		var infoResponse = JSON.parse(response);
		if(checkAJAXError(response)) { //If the returned JSON is an error message, append it above the form on the HTML page
			$('.form-horizontal').before(infoResponse);
		} else { //If it isn't an error message, then populate the form with the results
			$('#deptId').val(infoResponse["departmentId"]);
			$('#deptName').val(infoResponse["departmentName"]);
			$('#deptAbbr').val(infoResponse["departmentAbbr"]);
		}
	})
}

/**
 * A function to send an AJAX call to the database when a floor plan is selected from the dropdown menu to retrieve all of their info
 * @param selectedVal The selected floor plan's ID Number
 */
function getfpInfo(selectedVal) {
	$.ajax({
		method: "GET",
		url: "../database/getInfo.php",
		data: {fpId : selectedVal, page : "floorplan"} //Send the Floor plan's ID for the query, and the name of the page, so the correct info is returned
	}).done(function(response) {
		var infoResponse = JSON.parse(response);
		if(checkAJAXError(response)) { //If the returned JSON is an error message, append it above the form on the HTML page
			$('.form-horizontal').before(infoResponse);
		} else { //If it isn't an error message, then populate the form with the results
			$('#fpId').val(infoResponse['fpId']);
			$('#fpName').val(infoResponse['name']);
		}
	})
}

/**
 * A function to create a point representing a room location on the SVG Image map
 * @param xPos The percentage at which the x-coordinate is relative to the image (e.g. If the x-coordinate was 8 on a 16 pixel image, the value for xPos would be .5)
 * @param yPos The percentage at which the y-coordinate is relative to the image (e.g. If the y-coordinate was 8 on a 16 pixel image, the value for yPos would be .5)
 * @param s A snap.svg canvas that allows for annotation
 */
function makePoint(xPos, yPos, s) {
	$('circle').remove(); //Remove any circle element (used to mark rooms) that has been placed on the page (only one point can exist at a time)

	//Get the height & width of the floor plan image
	var mapWidth = document.getElementById('floorPlan').clientWidth;
	var mapHeight = document.getElementById('floorPlan').clientHeight;

	if (xPos != null && yPos != null) { //If there are values for xPos & yPos (i.e. The point exists)
		var newCircle = s.circle(xPos*mapWidth, yPos*mapHeight, 5); //get the actual coordinates by multiplying the percentages by the width/height of the map
		newCircle.attr('id', 'officeLocation');
		newCircle.attr({fill: "#F36E21"});
	}
}

/**
 * A function to prepare an Snap.SVG canvs for annotation
 * @ s A snap.SVG canvas
 * @ radius The radius of the circle that is placed when the user clicks on the canvas
 */
function prepMap(s, radius) {
	$('#floorPlan').click(function(e)  { //Define a click listener for the canvas
		var parentOffset = $(this).parent().offset(); //Get the relative position of the image offset from the rest of the page (allows us to only use coordinates relative to the image rather than the page)

		//Set the relative X & Y coordinates of the point drawn on the map (at the location of the click)
		var relX = e.pageX - parentOffset.left;
		var relY = e.pageY - parentOffset.top;

		//Get the width & height of the floor plan image
		var mapWidth = document.getElementById('floorPlan').clientWidth;
		var mapHeight = document.getElementById('floorPlan').clientHeight;

		if($('circle').length == 0) { //If no circle elements (points drawn on the map) exist:
			//Create the circle element where the mouse was clicked on the image
			newCircle = s.circle(relX, relY, radius);
			newCircle.attr('id', 'officeLocation');

			//Set the values to be sent on form submit to the database
			$('#posX').val(relX/mapWidth);
			$('#posY').val(relY/mapHeight);
		} else { //If there is a circle element already on the canvas
			//Get the coordinates of the existing circle, delete it, and recreate the circle on the canvs
			//This part is weird, but needed to be done to allow for actually setting the position of the circle
			var oldX = $('circle').attr('cx');
			var oldY = $('circle').attr('cy');
			$('circle').remove();
			var newCircle = s.circle(oldX, oldY, radius);
		}

		newCircle.attr({fill: "#F36E21"}); //Color the circle Orange

		//Custom define a move function for dragging the element
		var moveFunc = function (dx, dy) {
			this.attr({
				//This code basically, moves the point by detecting the original position and following the mouse at it changes coordinates
				transform: this.data('origTransform') + (this.data('origTransform') ? "T" : "t") + [dx, dy]
			});
		};

		//Custom define a function on the start of a drag
		var start = function() {
			//This enables movement
			this.data('origTransform', this.transform().local);
		}

		//Custom define a drag function - this allows us to send the coordinates of the point at the end of the drag
		newCircle.drag(moveFunc, start, function() {
				//Custom define an ending drag function - send the coordinates of the point to the form
				var thisBox = this.getBBox();
				$('#posX').val((thisBox.x+radius)/mapWidth);
				$('#posY').val((thisBox.y+radius)/mapHeight);

				//slightly move the circle so that it ends with it's center where the mouse point was, rather than it's top left corner
				$('circle').attr('cx', thisBox.x+radius);
				$('circle').attr('cy', thisBox.y+radius);
		});
	});
}

//Document.ready listeners for the administrative portal
$(document).ready(function() {

	//Listener for the search box on the Employee page - enables live search of employees
	$("#filter").keyup(function() {
		var searchKeyword = $(this).val();

		if(searchKeyword.length >= 2 || searchKeyword.length == 0) { //Don't query the database with only one character (cuts down on unneccessary DB queries)
			$.ajax({
				method: "GET",
				url: "../database/search.php",
				data: {name : searchKeyword, page : "employee"} //send the search keyword and that the search is from the employee page
			}).done(function(response) {
				//Replace the contents of the search box with the results of the search
				$('#results').replaceWith('<ul multiple class="form-control" id="results">' + response + "</ul>");
			})
		}
	})

	//Listener for the search box on the Admin page - enables live search of administrators
	$("#adminFilter").keyup(function() {
		var searchKeyword = $(this).val();

		if(searchKeyword.length >= 2 || searchKeyword.length == 0) { //Don't query the database with only one character (cuts down on unneccessary DB queries)
			$.ajax({
				method: "GET",
				url: "../database/search.php",
				data: {name : searchKeyword, page : "admin"} //send the search keyword and that the search is from the admin page
			}).done(function(response) {
				//Replace the contents of the search box with the results of the search
				$('#results').replaceWith('<ul multiple class="form-control" id="results">' + response + "</ul>");
			})
		}
	})

	//Pepare the Create New button to be enabled on a keyup event (the user presses a key while focus is in one of the form inputs)
	allowEnableCreate();

	//Change listener for the room <select> containing all of the rooms
	$('#roomSelect').on('change', function() {
		//Populate the fields with information, enable the edit button, re-validate the form (for the formvalidaiton.js plugin), and disable the create button
		document.getElementById('editBtn').disabled = false;  		
  		getRoomInfo($(this).val());
  		$('#addRoom').data('formValidation').resetForm();
  		disableCreate();
	});

	//Change listener for the department <select> containing all of the departments
	$('#deptSelect').on('change', function() {
		//Populate the fields with information, enable the edit button, re-validate the form (for the formvalidaiton.js plugin), and disable the create button
		document.getElementById('editBtn').disabled = false;
		getDepartmentInfo($(this).val());
		$('#addDepartment').data('formValidation').resetForm();
		disableCreate();
	});

	//Change listener for the floorplan <select> containing all of the floorplans
	$('#fpSelect').on('change', function() {
		//Populate the fields with information and re-validate the form (for the formvalidaiton.js plugin)
		getfpInfo($(this).val());
		$('#addFloorplan').data('formValidation').resetForm();
	});

	//Change listener for the room <select> containing all of the floor plans
	$('#planSelect').on('change', function() {
		//SVG Creation & Form Population
		var imgPath = $(this).val();
		var img = $(document.createElement('img'));

		img.attr('src', imgPath).on('load', function() { //When the floor plan image loads, get it's width, height, and image path, and use those to create the SVG Map Canvas
			width = img[0].naturalWidth;
			var height = img[0].naturalHeight;
			document.getElementById("imgSrc").value = imgPath;

			//The <svg> and <image> tag must be set with widths & heights on creation
			$('#svgContainer').replaceWith("<div id='svgContainer'><svg id='floorPlan' width='" + width + "' height='" + height + "'><image xlink:href='" + imgPath + "' src='" + imgPath + "' width='" + width + "' height='" + height + "'/></svg></div>");

			var s = Snap("#floorPlan");
			$('body').bind('touchstart', function() {}); //makes touchscreen taps behave like hover
			
			//Create a circle if one existed on the SVG map before it was changed to a new one	
			if($('#posX').val() != "" && $('#posY').val() != "") {
				var newCircle = s.circle($('#posX').val(), $('#posY').val(), 5);
				newCircle.attr('id', 'officeLocation');
			}

			prepMap(s, 5);
		});
	});

	//Functions to add bullet points to the higlights & education textareas on the Employee Page
	applyBullets('highlights');
	applyBullets('education');
	
	//Custom form Validation through the formValidation.js plugin
	//Documentation on this can be found at http://formvalidation.io/examples/ and at http://formvalidation.io/developing/
	$('.form-horizontal').formValidation({
		//Defines what CSS framework we're working with
		framework: 'bootstrap',

		icon: { //Defines what icons we're using for feedback
			valid: 'glyphicon glyphicon-ok',
			invalid: 'glyphicon glyphicon-remove',
			validating: 'glyphicon glyphicon-refresh'
		},

		err: { //Sets the error message to be a tooltip rather than text display
            container: 'tooltip'
        },

		fields: { //apply validation to each of the fields
			firstName: { //validation for the firstName field (Employee & Admin page)
				validators: {
					notEmpty: { //require input
						message: "A first name is required"
					},

					stringLength: { //set a min & max string length
						min: 1,
						max: 50,
						message: "The name must be between 1 & 50 characters"
					}, 

					regexp: { //Add regex to allow only certain characters
						regexp: /^[a-zA-Z \'\-\.\(\)]+$/,
						message: "Acceptable characters are letters and ' - . ( )"
					}
				}
			}, 

			lastName: { //validation for the lastName field (Employee & Admin page)
				validators: {
					notEmpty: {
						message: "A last name is required"
					},

					stringLength: {
						min: 1,
						max: 50,
						message: "The name must be between 1 & 50 characters"
					}, 

					regexp: {
						regexp: /^[a-zA-Z \'\-\.\(\)]+$/,
						message: "Acceptable characters are letters and ' - . ( )"
					}
				}
			},

			title: { //validation for the title field (Employee page)
				validators: {
					stringLength: {
						max: 100,
						message: "The name must be less than 100 characters"
					}
				}
			},

			 email: { //validation for the email field (Employee page)
				validators: {
					stringLength: {
						max: 100,
						message: "The name must be less than 100 characters"
					}, 

					regexp: {
						regexp: /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/,
						message: "Please input a valid email address"
					}
				}
			},

			phone: { //validation for the phone field (Employee page)
				validators: {
					regexp: {
						regexp: /^\([0-9]{3}\) [0-9]{3}\-[0-9]{4}$/,
						message: "Phone number must be in the format (###) ###-####"
					}
				}
			},

			room: { //validation for the room field (Employee page)
				validators: {
					notEmpty: {
						message: "A room is required"
					},
					regexp: {
						regexp: /^[a-zA-Z0-9]{3} [A-Za-z0-9]{1}\d{3}$/,
						message: "Room numbers must be in the format 'AAA ####'"
					}
				}
			},

			active: { //validation for the active radio button (Employee page)
				validators: {
					notEmpty: {
						message: "An active status is required"
					}
				}
			},

			faculty: { //validation for the faculty radio button (Employee page)
				validators: {
					notEmpty: {
						message: "A faculty status is required"
					}
				}
			},

			deptName: { //validation for the deptName field (Department & Employee Page)
				validators: {
					notEmpty: {
						message: "A department Name is required"
					},

					stringLength: {
						min: 1,
						max: 250,
						message: "Department Names must be between 1 & 250 characters"
					}
				}
			},

			deptAbbr: { //validation for the deptAbbr field (Department Page)
				validators: {
					notEmpty: {
						message: "An abbreviation is required"
					},

					stringLength: {
						min: 1,
						max: 10,
						message: "Department Abbreviations must be between 1 & 10 characters"
					}
				}
			},

			username: { //validation for the username field (Admin page)
				validators: {
					notEmpty: {
						message: "An RIT Username is required"
					},

					stringLength: {
						min: 1,
						max: 256,
						message: "RIT Usernames must be between 1 & 256 characters"
					}
				}
			},

			accessLevel: { //validation for the access Level field (Admin Page)
				validators: {
					notEmpty: {
						message: "An Access Level is required"
					}
				}
			},

			department: { //validation for the department field (Admin page)
				validators: {
					notEmpty: {
						message: "A department is required"
					}
				}
			},

			fpName: { //validation for the floor plan name field (Floor Plan page)
				validators: {
					notEmpty: {
						message: "A name is required"
					}
				}
			}
		}
	})
});

/**
 * A method to add bullets to a textarea
 * @param idName The HTML ID of the textarea
 */
function applyBullets(idName) {
	$("#" + idName).focus(function() {
	    if(document.getElementById(idName).value === ''){ //If, on focus, the field is blank, add a bullet point and a space
	        document.getElementById(idName).value +='• ';
		}
	});

	$("#" + idName).keyup(function(event){ //on keyup, if the user presses enter (keycode 13), add a bulletpoint and space on the new line
		var keycode = (event.keyCode ? event.keyCode : event.which);
	    if(keycode == '13'){
	        document.getElementById(idName).value +='• ';
		}
		var txtval = document.getElementById(idName).value;
		if(txtval.substr(txtval.length - 1) == '\n'){ //Check for only blank lines
			document.getElementById(idName).value = txtval.substring(0,txtval.length - 1);
		}
	});
}

/**
 * A function to remove a bullet point from a textarea if it is the only thing in the text area
 * @param idName The HTML Id of the textarea
 */
function removeOnlyBullets(idName) {
	var txt = document.getElementById(idName);
	if(txt.value == '• ') { //If the only content in the textarea is a bullet point and a space, wipe the textarea
		txt.value = "";
	}
}

/*
 * A function to disable the create button (used when the form is populated via an AJAX call)
 */
function disableCreate() {
	$('#newBtn').prop("disabled", true);
}

/**
 * A function to set the create button to re-enable when the user presses a key while one of hte form inputs has focus
 */
function allowEnableCreate() {
	$(":input").on('keyup', function() {
		$('#newBtn').prop("disabled", false);
	});
}