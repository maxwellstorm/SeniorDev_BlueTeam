var loadFile = function(event) {
    var img = document.getElementById('userImage');
    img.src = URL.createObjectURL(event.target.files[0]);
};


function setEmployeeActive(active) {
	$('#results li').removeClass("activeResult");
	active.className += " activeResult";
	document.getElementById('editBtn').disabled = false;

	getInfo(active);
	$('#addEmployee').data('formValidation').resetForm();
}

function formatPhoneNum(phone) {
	if(phone.value.length == 10) {
		phone.value = "(" + phone.value.slice(0,3) + ") " + phone.value.slice(3, 6) + "-" + phone.value.slice(6,10);
		$('#addEmployee').formValidation('revalidateField', 'phone');
	}
}

function setAdminActive(active) {
	$('#results li').removeClass("activeResult");
	active.className += " activeResult";
	document.getElementById('editBtn').disabled = false;

	getAdminInfo(active);
	$('#addAdmin').data('formValidation').resetForm();
}

function getInfo(selected) {
	var facId = $(selected).children('.fId').text();

	$.ajax({
		method: "GET",
		url: "../database/getInfo.php",
		data: {facultyId : facId, page : "employee"}
	}).done(function(response) {
		console.log(response);

		var infoResponse = JSON.parse(response);
		console.log(infoResponse);
		$('#facultyId').val(infoResponse["facultyId"]);
		$('#firstName').val(infoResponse["fName"]);
		$('#lastName').val(infoResponse["lName"]);
		$('#title').val(infoResponse["title"]);
		$('#email').val(infoResponse["email"]);
		$('#phone').val(infoResponse["phone"]);
		$('#room').selectpicker('val', infoResponse["roomNumber"]);
		$('#depts').selectpicker('val', [infoResponse["departmentName"], infoResponse["secondaryDepartmentName"]]);
		
		var isActive = infoResponse['isActive'];
		var isFaculty = infoResponse['isFaculty'];
		if(isActive == 0) {
			document.getElementById('activeNo').checked = true;
			document.getElementById('activeYes').checked = false;
		} else { //isActive == 1
			document.getElementById('activeNo').checked = false;
			document.getElementById('activeYes').checked = true;
		}

		if(isFaculty == 0) {
			document.getElementById('facultyNo').checked = true;
			document.getElementById('facultyYes').checked = false;
		} else { //isActive == 1
			document.getElementById('facultyNo').checked = false;
			document.getElementById('facultyYes').checked = true;
		}

		$('#about').val(infoResponse["about"]);
		$('#education').val(infoResponse["education"]);
		$('#highlights').val(infoResponse["highlights"]);

		var imagePath = infoResponse['imageName'];
		if(imagePath) {
			$('#userImage').attr("src", imagePath);
		} else {
			$('#userImage').attr("src", 'media/no-preview.png');
		}

	})
}

function getAdminInfo(selected) {
	var aId = $(selected).children('.aId').text();

	$.ajax({
		method: "GET",
		url: "../database/getInfo.php",
		data: {adminId : aId, page : "admin"}
	}).done(function(response) {
		var infoResponse = JSON.parse(response);
		console.log(infoResponse);
		$('#adminId').val(infoResponse["AdminId"]);
		$('#firstName').val(infoResponse["fName"]);
		$('#lastName').val(infoResponse["lName"]);
		$('#username').val(infoResponse["username"]);
		$('#accessLevel').val(infoResponse["accessLevel"]);
		$('#department').val(infoResponse["departmentName"]);
	})
}

function getRoomInfo(selectedVal) {
	$.ajax({
		method: "GET",
		url: "../database/getInfo.php",
		data: {room : selectedVal, page : "room"}
	}).done(function(response) {
		var infoResponse = JSON.parse(response);
		console.log(infoResponse);
		$('#room').val(infoResponse["roomNumber"]);
		$('#description').val(infoResponse["description"]);
		$('#posX').val(infoResponse['posX']);
		$('#posY').val(infoResponse['posY']);


		var imagePath = infoResponse['roomMap'];
		if(imagePath) {
			$('#userImage').attr("src", imagePath);
		} else {
			$('#userImage').attr("src", 'media/no-preview.png');
		}

		var s = Snap("#floorPlan");
		makePoint(infoResponse['posX'], infoResponse['posY'], s);
		prepMap(s, 5);
	})
}

function getDepartmentInfo(selectedVal) {
	$.ajax({
		method: "GET",
		url: "../database/getInfo.php",
		data: {deptId : selectedVal, page : "department"}
	}).done(function(response) {
		var infoResponse = JSON.parse(response);
		console.log(infoResponse);
		$('#deptId').val(infoResponse["departmentId"]);
		$('#deptName').val(infoResponse["departmentName"]);
		$('#deptAbbr').val(infoResponse["departmentAbbr"]);
	})
}

function getfpInfo(selectedVal) {
	$.ajax({
		method: "GET",
		url: "../database/getInfo.php",
		data: {fpId : selectedVal, page : "floorplan"}
	}).done(function(response) {
		var infoResponse = JSON.parse(response);
		console.log(infoResponse);
		$('#fpId').val(infoResponse['fpId']);
		$('#fpName').val(infoResponse['name']);
	})
}

function makePoint(xPos, yPos, s) {
	$('circle').remove();

	if (xPos != null && yPos != null) {
		var newCircle = s.circle(xPos, yPos, 5);
		newCircle.attr('id', 'officeLocation');
	}
}

function prepMap(s, radius) {
	$('#floorPlan').click(function(e)  {
		var parentOffset = $(this).parent().offset(); 

		var relX = e.pageX - parentOffset.left;
		var relY = e.pageY - parentOffset.top;

		//console.log(relX);
		//console.log(relY);

		if($('circle').length == 0) {
			newCircle = s.circle(relX, relY, radius);
			newCircle.attr('id', 'officeLocation');
			$('#posX').val(relX);
			$('#posY').val(relY);
		} else {
			var oldX = $('circle').attr('cx');
			var oldY = $('circle').attr('cy');
			$('circle').remove();
			var newCircle = s.circle(oldX, oldY, radius);
		}

		//console.log(newCircle);

		var moveFunc = function (dx, dy) {
			this.attr({
				transform: this.data('origTransform') + (this.data('origTransform') ? "T" : "t") + [dx, dy]
			});
		};

		var start = function() {
			this.data('origTransform', this.transform().local);
		}

		newCircle.drag(moveFunc, start, function() {
				var thisBox = this.getBBox();
				$('#posX').val(thisBox.x+radius);
				$('#posY').val(thisBox.y+radius);
				$('circle').attr('cx', thisBox.x+radius);
				$('circle').attr('cy', thisBox.y+radius);
				console.log(thisBox.x);
				console.log(thisBox.y);
				//do these need the deltas from the matrix? I don't think so...
		});
	});
}

$(document).ready(function() {
	$("#filter").keyup(function() {
		var searchKeyword = $(this).val();

		if(searchKeyword.length >= 2 || searchKeyword.length == 0) {
			$.ajax({
				method: "GET",
				url: "../database/search.php",
				data: {name : searchKeyword, page : "employee"}
			}).done(function(response) {
				$('#results').replaceWith('<ul multiple class="form-control" id="results">' + response + "</ul>");
			})
		}
	})

	$("#adminFilter").keyup(function() {
		var searchKeyword = $(this).val();

		if(searchKeyword.length >= 2 || searchKeyword.length == 0) {
			$.ajax({
				method: "GET",
				url: "../database/search.php",
				data: {name : searchKeyword, page : "admin"}
			}).done(function(response) {
				$('#results').replaceWith('<ul multiple class="form-control" id="results">' + response + "</ul>");
			})
		}
	})

	allowEnableCreate();

	$('#roomSelect').on('change', function() {
		document.getElementById('editBtn').disabled = false;  		
  		getRoomInfo($(this).val());
  		$('#addRoom').data('formValidation').resetForm();
  		disableCreate();
	});

	$('#deptSelect').on('change', function() {
		document.getElementById('editBtn').disabled = false;
		getDepartmentInfo($(this).val());
		$('#addDepartment').data('formValidation').resetForm();
		disableCreate();
	});

	$('#fpSelect').on('change', function() {
		getfpInfo($(this).val());
		$('#addFloorplan').data('formValidation').resetForm();
	});

	$('#planSelect').on('change', function() {
		//SVG Creation & Form Population
		var imgPath = $(this).val();
		console.log(imgPath);

		var img = $(document.createElement('img'));
		console.log(img);

		img.attr('src', imgPath).on('load', function() {
			width = img[0].naturalWidth;
			var height = img[0].naturalHeight;
			console.log(width);
			console.log(height);
			document.getElementById("imgSrc").value = imgPath;
		$('#svgContainer').replaceWith("<div id='svgContainer'><svg id='floorPlan' width='" + width + "' height='" + height + "'><image xlink:href='" + imgPath + "' src='" + imgPath + "' width='" + width + "' height='" + height + "'/></svg></div>");

			var s = Snap("#floorPlan");
			$('body').bind('touchstart', function() {}); //makes touchscreen taps behave like hover
			//end common creation
				
			if($('#posX').val() != "" && $('#posY').val() != "") {
				var newCircle = s.circle($('#posX').val(), $('#posY').val(), 5);
				newCircle.attr('id', 'officeLocation');
			}

			prepMap(s, 5);
		});
	});

	applyBullets('highlights');
	applyBullets('education');
	
	$('.form-horizontal').formValidation({
		framework: 'bootstrap',

		icon: {
			valid: 'glyphicon glyphicon-ok',
			invalid: 'glyphicon glyphicon-remove',
			validating: 'glyphicon glyphicon-refresh'
		},

		err: {
            container: 'tooltip'
        },

		fields: {
			firstName: {
				validators: {
					notEmpty: {
						message: "A first name is required"
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

			lastName: {
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

			title: {
				validators: {
					stringLength: {
						max: 100,
						message: "The name must be less than 100 characters"
					}
				}
			},

			 email: {
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

			phone: {
				validators: {
					regexp: {
						regexp: /^\([0-9]{3}\) [0-9]{3}\-[0-9]{4}$/,
						message: "Phone number must be in the format (###) ###-####"
					}
				}
			},

			room: {
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

			active: {
				validators: {
					notEmpty: {
						message: "An active status is required"
					}
				}
			},

			faculty: {
				validators: {
					notEmpty: {
						message: "A faculty status is required"
					}
				}
			},

			deptName: {
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

			deptAbbr: {
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

			username: {
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

			accessLevel: {
				validators: {
					notEmpty: {
						message: "An Access Level is required"
					}
				}
			},

			department: {
				validators: {
					notEmpty: {
						message: "A department is required"
					}
				}
			},

			fpName: {
				validators: {
					notEmpty: {
						message: "A name is required"
					}
				}
			}
		}
	})
});

function applyBullets(idName) {
	$("#" + idName).focus(function() {
	    if(document.getElementById(idName).value === ''){
	        document.getElementById(idName).value +='• ';
		}
	});

	$("#" + idName).keyup(function(event){
		var keycode = (event.keyCode ? event.keyCode : event.which);
	    if(keycode == '13'){
	        document.getElementById(idName).value +='• ';
		}
		var txtval = document.getElementById(idName).value;
		if(txtval.substr(txtval.length - 1) == '\n'){
			document.getElementById(idName).value = txtval.substring(0,txtval.length - 1);
		}
	});
}

function removeOnlyBullets(idName) {
	var txt = document.getElementById(idName);
	if(txt.value == '• ') {
		txt.value = "";
	}
}

function disableCreate() {
	$('#newBtn').prop("disabled", true);
}

function allowEnableCreate() {
	//Options = keyup & change
	$(":input").on('keyup', function() {
		$('#newBtn').prop("disabled", false);
	});
}