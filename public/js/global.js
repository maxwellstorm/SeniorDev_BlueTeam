// empty arrays to store professors, departments, and rooms as objects
var profArray = [];
var deptArray = [];
var roomArray = [];

// set initial time at page load (this is used for inactivity timeouts)
var time = new Date().getTime();

/**
 * A function to initialize the page
 * Pulls information from ajax requests
 * Populates the touchscreen grid view
 */
function init() {
	// AJAX call to pull professor information
	$.ajax({
		type: 'GET',
		url: '../database/fetch.php',
		data: 'function=fetchAll',
		async: false,
		success: function(response) {
			// ajax returns a JSON encoded array of data
			var dataArray = response;
			var parsedData = $.parseJSON(dataArray);
			// loop through JSON data and create Professor objects
			$.each(parsedData, function(i, val) {
				var professor = new Professor(val.facultyId, val.fName, val.lName, val.title, val.email, val.roomNumber, val.phone, val.departmentId, val.isActive, val.isFaculty, val.about, val.education, val.highlights, val.imageName); 
				if (professor.checkActive()) {
					// if the professor is set to active, add it to the global array
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
			// ajax returns a JSON encoded array of data
			var dataArray = response;
			var parsedData = $.parseJSON(dataArray);
			// loop through JSON data and create Department objects
			$.each(parsedData, function(i, val) {
				var dept = new Department(val.departmentId, val.departmentName, val.departmentAbbr);
				// add each department to the global array
				deptArray.push(dept);
			});

			// associate professors with their corresponding departments, populate department filters on the touchscreen
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
			// ajax returns a JSON encoded array of data
			var dataArray = response;
			var parsedData = $.parseJSON(dataArray);
			// loop through JSON data and create Room objects
			$.each(parsedData, function(i, val) {
				var room = new Room(val.roomNumber, val.roomMap, val.description, val.posX, val.posY);
				// add each room to the global array
				roomArray.push(room);
			});
		}
	});
	
	// populate the grid view, set default filters
	populateGridView();
	filterProfessors();

	// BELOW IS USED FOR INACTIVITY TIMEOUTS
	// when the user makes any action
	$(document.body).bind('mousemove keypress touchstart click', function(e) {
		// update the time variable with the time the action was made
		time = new Date().getTime();
	});

	// function to refresh the page
	function refresh() {
		// if it's been 1 minute (60000 milliseconds) with no activity
		if (new Date().getTime() - time >= 60000) {
			// reload the page
			window.location.reload(true);
		} else { // it's been less than a minute since the last check
			// check again in 5 seconds
			setTimeout(refresh, 5000);
		}
	}

	// call the refresh checking function after 5 seconds, it's recursive
	setTimeout(refresh, 5000);
}

/**
 * A function to create a card with supplied professor information
 * @param Professor : the object to set the card information
 * @return card : a single div to be displayed as a card within the grid view
 */
function getProfessorCard(Professor) {
	// create a string of html with data attributes used for filtering
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

	// return the card, it will be displayed in the grid view
	return card;
}

/**
 * A function to pull a specific professor object from the global array
 * @param facultyId : id of the professor to be chosen from the array
 * @return prof : the desired professor object
 */
function getProfessorFromArr(facultyId) {
	var prof;
	// loop through the professor array
	$.each(profArray, function(i, val) {
		// compare each professor's id to the parameter
		if (val.getFacultyId() == facultyId) {
			// it's a match, set it
			prof = val;
		}
	});
	return prof;
}

/**
 * A function to pull a specific room object from the global array
 * @param roomNumber : number of the room to be chosen from the array
 * @return room : the desired room object
 */
function getRoomFromArr(roomNumber) {
	var room;
	// loop through the room array
	$.each(roomArray, function(i, val) {
		// compare each room number to the parameter
		if (val.getRoomNumber() == roomNumber) {
			// it's a match, set it
			room = val;
		}
	});
	return room;
}

/**
 * A function to associate each professor with it's department object
 */
function setProfessorDepts() {
	// loop through the professor array
	$.each(profArray, function(i, val) {
		// get department id form the professor object
		var deptId = val.getDepartmentId();
		// get department name, sending the id
		var deptName = getDepartmentName(deptId);
		// set the department for the professor object
		val.setDepartment(deptName);
	});
}

/**
 * A function to display each department as a filter
 */
function populateDeptFilters() {
	// loop through the department array
	$.each(deptArray, function(i, val) {
		// call the function below
		var filter = getDeptFilter(val);
		// append the filter to the page
		$('#deptForm').append(filter);
	});
}

/**
 * A function to create a department radio button
 * @param Department : the department object to pull information from
 * @return check : the html to be appended as a filter
 */
function getDeptFilter(Department) {
	// create an html string containing a radio input
	var check = '<div class="deptCheck"><input type="radio" id="' + Department.getDeptName() + '" name="dept" value="' + Department.getDeptName() + '" /> ';
	check += '<label for="' + Department.getDeptName() + '">' + Department.getDeptName() + '</label>';
	check += '</div>';

	// return the input, it will be displayed in the list of department filters
	return check;
}

/**
 * A function to get the department name from a supplied id
 * @param deptId : the id of the department object to pull information from
 * @return deptName : the desired department name
 */
function getDepartmentName(deptId) {
	var deptName;
	// loop through the department array
	$.each(deptArray, function(i, val) {
		if (val.getDeptId() == deptId) {
			// get name from the supplied id
			deptName = val.getDeptName();
		}
	});
	return deptName;
}

/**
 * A function to populate the grid view on the touchscreen
 */
function populateGridView() {
	// loop through the professor array
	$.each(profArray, function(i, val) {
		// get the card html string
		var card = getProfessorCard(val);
		// append the card to the grid view
		$('#gridView').append(card);
	});
}

/**
 * A function to reset the grid view on the touchscreen, this is helpful for the filtering process
 */
function resetGridView() {
	// First, remove all cards from the DOM
	$('.professorCard').each(function(i) {
		$(this).remove();
	});

	// Then, repopulate
	populateGridView();
}

/**
 * A function to sort the global professor array
 * @param by : what the array should be sorted by, last name or room
 */
function sortProfArray(by) {
	if (by == "lname") { // sort by last name
		profArray.sort(function(a, b) { // call the built in sort function
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

/**
 * A function to check each filter value and update the grid view accordingly
 */
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

	// if department filter isn't All Departments
	if (dept != 'All') {
		// loop through the professor cards
		$('.professorCard').each(function(i, val) {
			// refine by department by removing elements from the DOM
			var department = $(this).attr('data-dept');
			if (department != dept) {
				$(this).remove();
			}
		});
	} else { // the department filter is All Departments
		resetGridView();
	}

	// begin removing professors from view
	$('.professorCard').each(function(i, val) {
		var currentProf = $(this);

		// refine by last initial
		var lastInitial = $(this).attr('data-lastinitial');
		if (letter != '') { // if the user actually selected a refine letter
			if (lastInitial != letter) { // if the professor's last name doesn't start with the selected letter, remove it
				currentProf.remove();
			}
		}
	});
}

/**
 * A function to update the map overlay after a user selected a professor from the grid
 * @param Professor : the department object to pull information from
 * @param Room : the room object to pull information from
 */
function updateOverlay(Professor, Room) {
	// if the professor thumbnail image is set
	if (Professor.getThumb() != null) {
		// set the overlay's professor image
		$('#overlayThumb').css('background-image', 'url(' + Professor.getThumb() + ')');
	} else {
		// reset the overlay's professor image
		$('#overlayThumb').css('background-image', 'none');
	}
	// set the overlay text values based on the supplied professor
	$('#overlayName').text(Professor.getFullName());
	$('#overlayRoom').text(Professor.getRoom());
	$('#overlayEmail').text(Professor.getEmail());
	$('#overlayPhone').text(Professor.getPhone());

	// set extensive info values, including department
	$('#deptText').text(Professor.getDepartment());

	// if the About section was supplied, set it
	if (Professor.getAbout() != '') {
		$('#overlayAbout').show();
		$('#aboutText').html(Professor.getAbout());
	} else {
		$('#overlayAbout').hide();	
	}

	// if the Education section was supplied, set it
	if (Professor.getEducation() != '') {
		$('#overlayEducation').show();
		$('#educationText').html(Professor.getEducation());
	} else {
		$('#overlayEducation').hide();	
	}

	// if the Highlights section was supplied, set it
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

	// create the floorplan image in memory to check it's raw dimensions
	var bgImg = new Image();
	// set the source to the room's map
	bgImg.src = Room.getRoomMap();
	$(bgImg).on('load', function() {
		// get raw dimensions
		var rawBackgroundWidth = bgImg.width;
		var rawBackgroundHeight = bgImg.height;
		
		// get height of map (total mapOverlay height - total floorMap padding)
		var floorMapHeight = $('#mapOverlay').height() - 30;
		// calculate ratio (height of map on screen / raw image height)
		var ratio = floorMapHeight / rawBackgroundHeight;
		// use ratio to find width of map on screen (raw image width * ratio)
		var floorMapWidth = rawBackgroundWidth * ratio;

		// set x and y values for positioning the pin
		// PosX and PosY are percentages, so multiply them by the dimensions of the floorplan
		var xValue = Room.getPosX() * floorMapWidth;
		var yValue = Room.getPosY() * floorMapHeight;

		// fade in the pin, this MUST be on a half-second delay or the function won't work
		setTimeout(function() {
			fadeInPin(xValue, yValue);
		}, 500);
	});
}

/**
 * A function to fade in the pin on the map
 * @param x : the x-coordinate of the pin
 * @param y : the y-coordinate of the pin
 */
function fadeInPin(x, y) {
	// dynamically check the dimensions of the element containing the pin ToolTip and icon
	var pinWidth = $('#pin').outerWidth();
	var pinHeight = $('#pin').outerHeight();

	// set the pin position, accounting for it's height and width
	$('#pin').css('top', y - pinHeight + 9 + 'px');
	$('#pin').css('left', x - (pinWidth / 2) + 'px');

	// animation to make it fade in and drop slightly
	$('#pin').animate({
		opacity: 1,
		marginTop: 9
	}, 250);
}

/**
 * A function to fade in the map overlay
 */
function showOverlay() {
	$('#mapOverlay').fadeIn(250);
}

/**
 * A function to fade out the map overlay and pin
 */
function hideOverlay() {
	$('#mapOverlay').fadeOut(250);
	$('#pin').animate({
		opacity: 0,
	}, 250, function() { // after it's faded out, slide it back up a bit
		$('#pin').css('margin-top', '0');
	});
}

/**
 * A function that's called immediately as the page begins loading
 * This contains all of the events that the user prompts
 */
$(document).ready(function() {
	// call the initialize function
	init();

	// user taps on a professor card
	$('#gridView').on('click', 'div.professorCard', function() {
		// get the id of the selected professor
		var selectedId = $(this).attr('id').substring(8);
		// get the professor object from the global array
		var professor = getProfessorFromArr(selectedId);
		// get the associated room object
		var room = getRoomFromArr(professor.getRoom());
		// update the map overlay
		updateOverlay(professor, room);
		// then show it
		showOverlay();
	});

	// user taps the BACK button, hide the overlay
	$('#closeOverlay').click(function() {
		hideOverlay();
	});

	// user wants to change how the professors are sorted
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

		// filter professors on any filter action
		filterProfessors();
	});

	// user selected a department filter
	$('.deptCheck input').click(function() {
		var selectedId = $(this).attr('id');
		$('.checked').removeClass('checked');
		$(this).addClass('checked');

		// filter professors on any filter action
		filterProfessors();
	});

	// user tapped the Refine link
	$('#refineLink').click(function() {
		if ($('#refineToggle').hasClass('visible')) { // if the letters are visible, slide them back up and rotate the arrow
			$('#refineTriangle').removeClass('rotated');
			$('#refineToggle').stop().slideUp(250).removeClass('visible');
			$('.selected').removeClass('selected');
			// filter professors on any filter action, this should reset the letter filter
			filterProfessors();
		} else { // the letters weren't visible, slide them down and rotate the arrow
			$('#refineTriangle').addClass('rotated');
			$('#refineToggle').stop().slideDown(250).addClass('visible');
		}
	});

	// user tapped a letter to filter the last name by
	$('.letter').click(function() {
		if ($(this).hasClass('selected')) {
			$(this).removeClass('selected').removeClass('roundCorners');
		} else {
			$('.selected').removeClass('selected');
			$(this).addClass('selected').addClass('roundCorners');
		}

		// filter professors on any filter action
		filterProfessors();
	});

	// user tapped the accessibility icon
	$('#accessibilityTab').click(function() {
		// toggle it on / off
		$(this).toggleClass('on');
		// add the accessible class to the wrapper div, this is for css purposes
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