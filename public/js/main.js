var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
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
		$('#firstName').val(infoResponse["fName"]);
		$('#lastName').val(infoResponse["lName"]);
		//Accomodate for Title?
		$('#email').val(infoResponse["email"]);
		$('#phone').val(infoResponse["phone"]);
		$('#room').val(infoResponse["roomNumber"]);
		$('#dept').val(infoResponse["departmentName"]);
		//NEED TO ACCOMODATE FOR SECOND DEPARTMENT
		
		//Need to fix toggling between the 2
		console.log('#faculty' + infoResponse["isFaculty"]);
		$('#active' + infoResponse["isActive"]).attr('checked', 'checked');
		$('#faculty' + infoResponse["isFaculty"]).attr('checked', 'checked');

		$('#about').val(infoResponse["about"]);
		$('#education').val(infoResponse["education"]);
		$('#highlights').val(infoResponse["highlights"]);
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
