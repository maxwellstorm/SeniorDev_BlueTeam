var profArray = [];

function init() {
	$.ajax({
		type: 'GET',
		url: '../database/fetch.php',
		data: 'function=fetchAll',
		async: false,
		success: function(response) {
			var dataArray = response;
			var parsedData = $.parseJSON(dataArray);
			// alert(dataArray);
			$.each(parsedData, function(i,val) {
				var professor = new Professor(val.facultyId, val.fName, val.lName, val.title, val.email, val.roomNumber, val.phone, val.departmentId, val.isActive, val.isFaculty, val.about, val.education, val.highlights, 'bogaard-thumb.jpg'); 
				profArray.push(professor);
			});
		}
	});
	
	populateGridView();
}

function getProfessorCard(Professor) {
	var card = '<div id="profCard' + Professor.getFacultyId() + '" class="professorCard dropShadow roundCorners">';
	card += '<div class="thumb" style="background-image: url(media/thumbs/' + Professor.getThumb() + ')"></div>';
	card += '<div class="infoPreview">';
	card += '<div class="professorName">' + Professor.getFullName() + '</div>';
	card += '<div>Room ' + Professor.getRoom() + '</div>';
	card += '<div class="moreLink">More Info</div>';
	card += '</div></div>';

	return card;
}

function getProfessorFromArr(facultyId) {
	var profToReturn;
	$.each(profArray, function(i, val) {
		if (val.getFacultyId() == facultyId) {
			profToReturn = val;
		}
	});
	return profToReturn;
}

function populateGridView() {
	$.each(profArray, function(i, val) {
		var card = getProfessorCard(val);
		$('#gridView').append(card);
	});
}

function updateOverlay(Professor) {
	$('#overlayName').text(Professor.getFullName());
	$('#overlayRoom').text('Room ' + Professor.getRoom());
}

function showOverlay() {
	$('#mapOverlay').fadeIn(250);
}

function hideOverlay() {
	$('#mapOverlay').fadeOut(250);
}

$(document).ready(function() {
	init();

	$('.professorCard').click(function() {
		var selectedId = $(this).attr("id").substring(8);
		var professor = getProfessorFromArr(selectedId);
		updateOverlay(professor);

		showOverlay();
	});

	$('#closeOverlay').click(function() {
		hideOverlay();
	});
});