var loadFile = function(event) {
    var img = document.getElementById('userImage');
    img.src = URL.createObjectURL(event.target.files[0]);
};


function setActive(active) {
	$('#results li').removeClass("activeResult");
	active.className += " activeResult";

	getInfo(active);
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
		//Accomodate for Title?
		$('#email').val(infoResponse["email"]);
		$('#phone').val(infoResponse["phone"]);
		$('#room').selectpicker('val', infoResponse["roomNumber"]);
		$('#dept').selectpicker('val', infoResponse["departmentName"]);
		//NEED TO ACCOMODATE FOR SECOND DEPARTMENT
		
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

$(document).ready(function() {
	$("#filter").keyup(function() {
		var searchKeyword = $(this).val();

		if(searchKeyword.length >= 3 || searchKeyword.length == 0) {
			$.ajax({
				method: "POST",
				url: "../database/search.php",
				data: {name : searchKeyword}
			}).done(function(response) {
				$('#results').replaceWith('<ul multiple class="form-control" id="results">' + response + "</ul>");
			})
		}
	})
});