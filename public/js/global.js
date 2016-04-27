// empty arrays to store professors and departments as objects
var profArray = [];
var deptArray = [];

// set initial time at page load (this is used for inactivity timeouts)
var time = new Date().getTime();

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
			$.each(parsedData, function(i, val) {
				var professor = new Professor(val.facultyId, val.fName, val.lName, val.title, val.email, val.roomNumber, val.phone, val.departmentId, val.isActive, val.isFaculty, val.about, val.education, val.highlights, val.imageName); 
				if (val.isActive == 1) {
					profArray.push(professor);
				}
			});
		}
	});

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
	
	populateGridView();

	// the rest of the init function is for inactivity timeouts
	$(document.body).bind('mousemove keypress', function(e) {
		time = new Date().getTime();
	});

	function refresh() {
		if (new Date().getTime() - time >= 30000) {
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
	card += '<div class="thumb" style="background-image: url(' + Professor.getThumb() + ')"></div>';
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
		$('#checks').append(filter);
	});
}

function getDeptFilter(Department) {
	var check = '<div class="deptCheck"><input type="checkbox" id="' + Department.getDeptName() + '" name="dept" value="' + Department.getDeptName() + '" checked="checked" /> ';
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
			} else {
				return 1;
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
	var sort = 'name';
	var view = 'grid';
	var depts = [];
	var letter;

	// set sort based on filter toggle
	var sortSelectedId = $('#sortToggle .selectedToggle').attr('id');
	if (sortSelectedId == "nameToggle") { // user wants to sort professors by last name
		// sortProfArray("lname");
		sort = 'lname';
	} else { // user wants to sort professors by room number
		// sortProfArray("room");
		sort = 'room';
	}

	// set view based on filter toggle
	var viewSelectedId = $('#viewToggle .selectedToggle').attr('id');
	if (viewSelectedId == "gridToggle") { // user wants to view professors in a grid
		view = 'grid';
	} else { // user wants to view professors in a list
		view = 'list';
	}

	// set depts based on filter checkboxes
	$('.deptCheck input').each(function(i) {
		var selectedDept = $(this).attr('id');
		if ($(this).attr('checked') == 'checked') {
			depts.push(selectedDept);
		} else {
			depts = $.grep(depts, function(val) {
				return val != selectedDept;
			});
		}
	});

	// set letter based on refine selection
	letter = $('.letter.selected').text();

	// alert(sort + ' ' + view + ' ' + depts + ' ' + letter);
	// BEGIN FILTERING BASED ON VARIABLE VALUES

	// sort array
	sortProfArray(sort);

	// repopulate the view
	resetGridView();

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

		// refine by department
		var department = $(this).attr('data-dept');
		if (depts.indexOf(department) == -1) {
			currentProf.remove();
		}
	});
}

function updateOverlay(Professor) {
	$('#overlayThumb').css('background-image', 'url(' + Professor.getThumb() + ')');
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
			$('#sortToggle .toggleSlider').css('left', '0px');
		} else { // user wants to sort professors by room number
			$('#sortToggle .toggleSlider').css('left', '50%');
		}

		filterProfessors();
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

	$('.deptCheck input').click(function() {
		var selectedId = $(this).attr('id');
		if ($(this).attr('checked') == 'checked') {
			// user just unchecked it
			$(this).removeAttr('checked');
		} else {
			// user just checked it
			$(this).attr('checked', 'checked');
		}

		filterProfessors();
	});

	$('#refineLink').click(function() {
		if ($('#refineToggle').hasClass('visible')) {
			$('#refineToggle').slideUp(250).removeClass('visible');
			$('.selected').removeClass('selected');
			filterProfessors();
		} else {
			$('#refineToggle').slideDown(250).addClass('visible');
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
});