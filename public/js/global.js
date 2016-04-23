var profArray = [];
var refined = false;
var refineLetter;

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
	var card = '<div id="profCard' + Professor.getFacultyId() + '" data-lastinitial="' + Professor.getLastInitial() + '" data-room="' + Professor.getRoom().replace(/\s/g, '') + '" class="professorCard dropShadow roundCorners">';
	card += '<div class="thumb" style="background-image: url(media/thumbs/' + Professor.getThumb() + ')"></div>';
	card += '<div class="infoPreview">';
	card += '<div class="professorName">' + Professor.getFullName() + '</div>';
	// card += '<div>Room ' + Professor.getRoom() + '</div>';
	card += '<div>' + Professor.getRoom() + '</div>';
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

function resetGridView() {
	// First, remove all cards in the grid
	$('.professorCard').each(function(i) {
		$(this).remove();
	});

	// Then, repopulate
	populateGridView();
}

function sortProfArray(by) {
	if (by == "lname") { // sort by last name
		profArray.sort(function(a, b) {
			var aLast = a.getLast();
			var bLast = b.getLast();
			if (aLast < bLast) {
				return -1;
			} else {
				return 1;
			}
		});
	} else if (by == "room") { // sort by room
		profArray.sort(function(a, b) {
			var aRoom = a.getRoom();
			var bRoom = b.getRoom();
			if (aRoom < bRoom) {
				return -1;
			} else if (aRoom > bRoom) {
				return 1;
			} else { // professors have same room number, decide on last name
				var aLast = a.getLast();
				var bLast = b.getLast();
				if (aLast < bLast) {
					return -1;
				} else {
					return 1;
				}
			}
		});
	}
	// set the view with different sort
	if (refined) {
		refineList(refineLetter);
	} else {
		resetGridView();
	}
}

function refineList(letter) {
	refined = true;
	refineLetter = letter;
	resetGridView();

	$('.professorCard').each(function(i) {
		var lastInitial = $(this).attr('data-lastinitial');
		if (lastInitial != letter) {
			$(this).remove();
		}
	});
}

function updateOverlay(Professor) {
	$('#overlayName').text(Professor.getFullName());
	$('#overlayEmail').text(Professor.getEmail());
	$('#overlayPhone').text(Professor.getPhone());
	// $('#overlayRoom').text('Room ' + Professor.getRoom());
	$('#overlayRoom').text(Professor.getRoom());
	$('#overlayAbout').text(Professor.getAbout());
	$('#overlayEducation').html(Professor.getEducation());
	$('#overlayHighlights').text(Professor.getHighlights());
}

function showOverlay() {
	$('#mapOverlay').fadeIn(250);
}

function hideOverlay() {
	$('#mapOverlay').fadeOut(250);
}

$(document).ready(function() {
	init();

	$('#gridView').on('click', 'div.professorCard', function() {
		var selectedId = $(this).attr("id").substring(8);
		var professor = getProfessorFromArr(selectedId);
		updateOverlay(professor);

		showOverlay();
	});

	$('#closeOverlay').click(function() {
		hideOverlay();
	});

	$('#sortToggle span').click(function() {
		$('#sortToggle .selectedToggle').removeClass('selectedToggle');
		$(this).addClass('selectedToggle');

		// slide the toggle slider
		var selectedId = $(this).attr('id');
		if (selectedId == "nameToggle") { // user wants to sort professors by last name
			sortProfArray("lname");
			$('#sortToggle .toggleSlider').css('left', '0px');
		} else { // user wants to sort professors by room number
			sortProfArray("room");
			$('#sortToggle .toggleSlider').css('left', '50%');
		}
	});

	$('#viewToggle span').click(function() {
		$('#viewToggle .selectedToggle').removeClass('selectedToggle');
		$(this).addClass('selectedToggle');

		// slide the toggle slider
		var selectedId = $(this).attr('id');
		if (selectedId == "gridToggle") {
			$('#viewToggle .toggleSlider').css('left', '0px');
		} else {
			$('#viewToggle .toggleSlider').css('left', '50%');
		}
	});

	$('#refineLink').click(function() {
		if ($('#refineToggle').hasClass('visible')) {
			$('#refineToggle').slideUp(250).removeClass('visible');
			$('.selected').removeClass('selected');
			refined = false;
			resetGridView();
		} else {
			$('#refineToggle').slideDown(250).addClass('visible');
		}
	});

	$('.letter').click(function() {
		if ($(this).hasClass('selected')) {
			$(this).removeClass('selected');
			refined = false;
			resetGridView();
		} else {
			$('.selected').removeClass('selected');
			$(this).addClass('selected').addClass('roundCorners');
			refineList($(this).text());
		}
	});
});