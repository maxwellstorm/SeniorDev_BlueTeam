var profArray = [];

function init() {
	var prof1 = new Professor('Daniel', 'Bogaard', 'dsbics@rit.edu', '2111', 'bogaard-thumb.jpg');
	var prof2 = new Professor('Catherine', 'Beaton', 'ciiics@rit.edu', '2621', 'beaton-thumb.png');
	var prof3 = new Professor('Catherine', 'Beaton', 'ciiics@rit.edu', '2621', 'beaton-thumb.png');
	var prof4 = new Professor('Catherine', 'Beaton', 'ciiics@rit.edu', '2621', 'beaton-thumb.png');
	profArray.push(prof1);
	profArray.push(prof2);
	profArray.push(prof3);
	profArray.push(prof4);
	populateGridView();
}

function getProfessorCard(Professor) {
	var card = '<div class="professorCard dropShadow roundCorners">';
	card += '<div class="thumb" style="background-image: url(media/thumbs/' + Professor.getThumb() + ')"></div>';
	card += '<div class="infoPreview">';
	card += '<div class="professorName">' + Professor.getFullName() + '</div>';
	card += '<div>Room: ' + Professor.getRoom() + '</div>';
	card += '<div class="moreLink">More Info</div>';
	card += '</div></div>';

	return card;
}

function populateGridView() {
	$.each(profArray, function(i, val) {
		var card = getProfessorCard(val);
		$('#gridView').append(card);
	});
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
		showOverlay();
	});

	$('#closeOverlay').click(function() {
		hideOverlay();
	});
});