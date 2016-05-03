// empty arrays to store professors and departments as objects
var profArray = [];
var deptArray = [];
var roomArray = [];

// set initial time at page load (this is used for inactivity timeouts)
var time = new Date().getTime();

function init() {
	// AJAX call to pull professor information
	$.ajax({
		type: 'GET',
		url: '../database/fetch.php',
		data: 'function=fetchAll',
		async: false,
		success: function(response) {
			var dataArray = response;
			var parsedData = $.parseJSON(dataArray);
			// alert(dataArray);
			$.each(parsedData, function(i, val) {
				var professor = new Professor(val.facultyId, val.fName, val.lName, val.title, val.email, val.roomNumber, val.phone, val.departmentId, val.isActive, val.isFaculty, val.about, val.education, val.highlights, val.imageName); 
				if (professor.checkActive()) {
					profArray.push(professor);
				}
			});
		}
	});

	// AJAX call to pull department information
	$.ajax({
		type: 'GET',
		url: '../database/fetch.php',
		data: 'function=fetchDepts',
		async: false,
		success: function(response) {
			var dataArray = response;
			var parsedData = $.parseJSON(dataArray);
			// alert(dataArray);
			$.each(parsedData, function(i, val) {
				var dept = new Department(val.departmentId, val.departmentName, val.departmentAbbr);
				deptArray.push(dept);
			});

			setProfessorDepts();
			populateDeptFilters();
		}
	});

	// AJAX call to pull room information
	$.ajax({
		type: 'GET',
		url: '../database/fetch.php',
		data: 'function=fetchRooms',
		async: false,
		success: function(response) {
			var dataArray = response;
			var parsedData = $.parseJSON(dataArray);
			// alert(dataArray);
			$.each(parsedData, function(i, val) {
				var room = new Room(val.roomNumber, val.roomMap, val.description, val.posX, val.posY);
				roomArray.push(room);
			});
		}
	});
	
	populateGridView();
	filterProfessors();

	// the rest of the init function is for inactivity timeouts
	$(document.body).bind('mousemove keypress', function(e) {
		time = new Date().getTime();
	});

	function refresh() {
		if (new Date().getTime() - time >= 60000) {
			window.location.reload(true);
		} else {
			setTimeout(refresh, 5000);
		}
	}

	setTimeout(refresh, 5000);
}

function getProfessorCard(Professor) {
	var card = '<div id="profCard' + Professor.getFacultyId() + '" data-lastinitial="' + Professor.getLastInitial() + '" data-room="';
	card += Professor.getRoom().replace(/\s/g, '') + '" data-dept="' + Professor.getDepartment() + '" class="professorCard dropShadow roundCorners">';
	if (Professor.getThumb() != null) {
		card += '<div class="thumb" style="background-image: url(' + Professor.getThumb() + ')"></div>';
	} else {
		card += '<div class="thumb"></div>';
	}
	card += '<div class="infoPreview">';
	card += '<div class="professorName">' + Professor.getFullName() + '</div>';
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

function getRoomFromArr(roomNumber) {
	var roomToReturn;
	$.each(roomArray, function(i, val) {
		if (val.getRoomNumber() == roomNumber) {
			roomToReturn = val;
		}
	});
	return roomToReturn;
}

function setProfessorDepts() {
	$.each(profArray, function(i, val) {
		var deptId = val.getDepartmentId();
		var deptName = getDepartmentName(deptId);
		val.setDepartment(deptName);
	});
}

function populateDeptFilters() {
	$.each(deptArray, function(i, val) {
		var filter = getDeptFilter(val);
		$('#deptForm').append(filter);
	});
}

function getDeptFilter(Department) {
	var check = '<div class="deptCheck"><input type="radio" id="' + Department.getDeptName() + '" name="dept" value="' + Department.getDeptName() + '" /> ';
	check += '<label for="' + Department.getDeptName() + '">' + Department.getDeptName() + '</label>';
	check += '</div>';
	return check;
}

function getDepartmentName(deptId) {
	var deptName;
	$.each(deptArray, function(i, val) {
		if (val.getDeptId() == deptId) {
			deptName = val.getDeptName();
		}
	});
	return deptName;
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
			} else if (aLast > bLast) {
				return 1;
			} else { // professors have the same last name, decide on room number
				var aRoom = a.getRoom().replace(/\s/g, '');
				var bRoom = b.getRoom().replace(/\s/g, '');
				if (aRoom < bRoom) {
					return -1;
				} else {
					return 1;
				}
			}
		});
	} else if (by == "room") { // sort by room
		profArray.sort(function(a, b) {
			var aRoom = a.getRoom().replace(/\s/g, '');
			var bRoom = b.getRoom().replace(/\s/g, '');
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
}

function filterProfessors() {
	// set default filter values
	var sort = 'lname';
	var dept = 'All';
	var letter;

	// set sort based on filter toggle
	var sortSelectedId = $('#sortToggle .selectedToggle').attr('id');
	if (sortSelectedId == "nameToggle") { // user wants to sort professors by last name
		sort = 'lname';
	} else { // user wants to sort professors by room number
		sort = 'room';
	}

	// set depts based on filter checkboxes
	dept = $('.deptCheck input.checked').attr('id');

	// set letter based on refine selection
	letter = $('.letter.selected').text();
	
	// BEGIN FILTERING BASED ON VARIABLE VALUES

	// sort array
	sortProfArray(sort);

	// repopulate the view
	resetGridView();

	if (dept != 'All') {
		$('.professorCard').each(function(i, val) {
			// refine by department
			var department = $(this).attr('data-dept');
			if (department != dept) {
				$(this).remove();
			}
		});
	} else {
		resetGridView();
	}

	// begin removing professors from view
	$('.professorCard').each(function(i, val) {
		var currentProf = $(this);

		// refine by last initial
		var lastInitial = $(this).attr('data-lastinitial');
		if (letter != '') { // if the user actually selected a refine letter
			if (lastInitial != letter) {
				currentProf.remove();
			}
		}
	});
}

function updateOverlay(Professor, Room) {
	if (Professor.getThumb() != null) {
		$('#overlayThumb').css('background-image', 'url(' + Professor.getThumb() + ')');
	} else {
		$('#overlayThumb').css('background-image', 'none');
	}
	$('#overlayName').text(Professor.getFullName());
	$('#overlayRoom').text(Professor.getRoom());
	$('#overlayEmail').text(Professor.getEmail());
	$('#overlayPhone').text(Professor.getPhone());

	$('#deptText').text(Professor.getDepartment());

	if (Professor.getAbout() != '') {
		$('#overlayAbout').show();
		$('#aboutText').html(Professor.getAbout());
	} else {
		$('#overlayAbout').hide();	
	}

	if (Professor.getEducation() != '') {
		$('#overlayEducation').show();
		$('#educationText').html(Professor.getEducation());
	} else {
		$('#overlayEducation').hide();	
	}

	if (Professor.getHighlights() != '') {
		$('#overlayHighlights').show();
		$('#highlightsText').html(Professor.getHighlights());
	} else {
		$('#overlayHighlights').hide();
	}

	// update map image
	$('#floorMap').css('background-image', 'url(' + Room.getRoomMap() + ')');

	// update pin tooltip text
	$('#pinName').text(Professor.getFullName());
	$('#pinRoom').text(Professor.getRoom());

	var bgImg = new Image();
	bgImg.src = Room.getRoomMap();
	$(bgImg).on('load', function() {
		var rawBackgroundWidth = bgImg.width;
		var rawBackgroundHeight = bgImg.height;
		
		// get height of map (total mapOverlay height - total floorMap padding)
		var floorMapHeight = $('#mapOverlay').height() - 30;
		// calculate ratio (height of map on screen / raw image height)
		var ratio = floorMapHeight / rawBackgroundHeight;
		// use ratio to find width of map on screen (raw image width * ratio)
		var floorMapWidth = rawBackgroundWidth * ratio;

		var xValue = Room.getPosX() * floorMapWidth;
		var yValue = Room.getPosY() * floorMapHeight;

		setTimeout(function() {
			fadeInPin(xValue, yValue);
		}, 500);
	});
}

function fadeInPin(x, y) {
	var pinWidth = $('#pin').outerWidth();
	var pinHeight = $('#pin').outerHeight();

	$('#pin').css('top', y - pinHeight + 9 + 'px');
	$('#pin').css('left', x - (pinWidth / 2) + 'px');

	$('#pin').animate({
		opacity: 1,
		marginTop: 9
	}, 250);
}

function showOverlay() {
	$('#mapOverlay').fadeIn(250);
}

function hideOverlay() {
	$('#mapOverlay').fadeOut(250);
	$('#pin').animate({
		opacity: 0,
	}, 250, function() {
		$('#pin').css('margin-top', '0');
	});
}

$(document).ready(function() {
	init();

	$('#gridView').on('click', 'div.professorCard', function() {
		var selectedId = $(this).attr("id").substring(8);
		var professor = getProfessorFromArr(selectedId);
		var room = getRoomFromArr(professor.getRoom());
		var update = updateOverlay(professor, room);

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
			$('#sortToggle .toggleSlider').css('left', '0px');
		} else { // user wants to sort professors by room number
			$('#sortToggle .toggleSlider').css('left', '50%');
		}

		filterProfessors();
	});

	$('.deptCheck input').click(function() {
		var selectedId = $(this).attr('id');
		$('.checked').removeClass('checked');
		$(this).addClass('checked');

		filterProfessors();
	});

	$('#refineLink').click(function() {
		if ($('#refineToggle').hasClass('visible')) {
			$('#refineTriangle').removeClass('rotated');
			$('#refineToggle').stop().slideUp(250).removeClass('visible');
			$('.selected').removeClass('selected');
			filterProfessors();
		} else {
			$('#refineTriangle').addClass('rotated');
			$('#refineToggle').stop().slideDown(250).addClass('visible');
		}
	});

	$('.letter').click(function() {
		if ($(this).hasClass('selected')) {
			$(this).removeClass('selected');
		} else {
			$('.selected').removeClass('selected');
			$(this).addClass('selected').addClass('roundCorners');
		}

		filterProfessors();
	});

	$('#accessibilityTab').click(function() {
		$(this).toggleClass('on');
		$('#wrapper').toggleClass('accessible');

		if ($(this).hasClass('on')) { // user just turned on accessibility mode
			$('#accessIcon').attr('src', 'media/accessibility-orange-32.png');
			$(this).removeClass('dropShadow').addClass('innerShadow');
			$('#All').click(); // reset departments to show all, their radio buttons are hidden in accessibility mode
		} else { // user just turned off accessibility mode
			$('#accessIcon').attr('src', 'media/accessibility-gray-32.png');
			$(this).removeClass('innerShadow').addClass('dropShadow');
		}
	});
});