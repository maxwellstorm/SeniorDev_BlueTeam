var postData = "function=getData()";


function fetchAllProfessors() {
	$.ajax({
		type: "GET",
		data: postData,
		url: "../../database/data.php",
		success: function(data) {
			alert("success");
		}
	});
}