var loadFile = function(event) {
    var img = document.getElementById('userImage');
    img.src = URL.createObjectURL(event.target.files[0]);
};


function setActive(active) {
	$('#results li').removeClass("activeResult");
	active.className += " activeResult";
	document.getElementById('editBtn').disabled = false;

	getInfo(active);
}

function setAdminActive(active) {
	$('#results li').removeClass("activeResult");
	active.className += " activeResult";
	document.getElementById('editBtn').disabled = false;

	getAdminInfo(active);
}

function getInfo(selected) {
	var facId = $(selected).children('.fId').text();

	$.ajax({
		method: "GET",
		url: "../database/getInfo.php",
		data: {facultyId : facId}
	}).done(function(response) {
		var infoResponse = JSON.parse(response);
		console.log(infoResponse);
		$('#facultyId').val(infoResponse["facultyId"]);
		$('#firstName').val(infoResponse["fName"]);
		$('#lastName').val(infoResponse["lName"]);
		$('#title').val(infoResponse["title"]);
		$('#email').val(infoResponse["email"]);
		$('#phone').val(infoResponse["phone"]);
		$('#room').selectpicker('val', infoResponse["roomNumber"]);
		$('#dept').selectpicker('val', [infoResponse["departmentName"], infoResponse["secondaryDepartmentName"]]);
		
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
		url: "../database/getAdminInfo.php",
		data: {adminId : aId}
	}).done(function(response) {
		var infoResponse = JSON.parse(response);
		console.log(infoResponse);
		$('#adminId').val(infoResponse["adminId"]);
		$('#firstName').val(infoResponse["fName"]);
		$('#lastName').val(infoResponse["lName"]);
		//Accomodate for Title?
		$('#username').val(infoResponse["username"]);
		$('#accessLevel').val(infoResponse["accessLevel"]);
		$('#dept').val(infoResponse["departmentName"]);
	})
}

function getRoomInfo(selectedVal) {
	$.ajax({
		method: "GET",
		url: "../database/getRoomInfo.php",
		data: {roomNum : selectedVal}
	}).done(function(response) {
		console.log(response);
		var infoResponse = JSON.parse(response);
		console.log(infoResponse);
		$('#roomNum').val(infoResponse["roomNumber"]);
		$('#description').val(infoResponse["description"]);
		//STILL NEED TO DO ROOM IMAGE STUFF
	})
}

function getDepartmentInfo(selectedVal) {
	$.ajax({
		method: "GET",
		url: "../database/getDepartmentInfo.php",
		data: {deptId : selectedVal}
	}).done(function(response) {
		var infoResponse = JSON.parse(response);
		console.log(infoResponse);
		$('#deptId').val(infoResponse["departmentId"]);
		$('#deptName').val(infoResponse["departmentName"]);
		$('#deptAbbr').val(infoResponse["departmentAbbr"]);
	})
}

$(document).ready(function() {
	$("#filter").keyup(function() {
		var searchKeyword = $(this).val();

		if(searchKeyword.length >= 2 || searchKeyword.length == 0) {
			$.ajax({
				method: "POST",
				url: "../database/search.php",
				data: {name : searchKeyword}
			}).done(function(response) {
				$('#results').replaceWith('<ul multiple class="form-control" id="results">' + response + "</ul>");
			})
		}
	})

	allowEnableCreate();

	$('#roomSelect').on('change', function() {
		document.getElementById('editBtn').disabled = false;  		
  		getRoomInfo($(this).val());
  		disableCreate();
	});

	$('#deptSelect').on('change', function() {
		document.getElementById('editBtn').disabled = false;
		getDepartmentInfo($(this).val());
		disableCreate();
	});

	applyBullets('highlights');
	applyBullets('education');
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