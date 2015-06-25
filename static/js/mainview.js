window.onload = function() {
	put_text_if_no_meetings('Ingen møter');
	preventDefaultAnchorTags();
};

function put_text_if_no_meetings(text) {
	var rooms = document.getElementsByClassName('rooms'),
		i, p;

	for (i = 0; i < rooms.length; ++i) {
		var meetings = rooms[i].children;
		if (meetings.length <= 1) {
			p = document.createElement('p');
			p.innerHTML = text;
			rooms[i].appendChild(p);
		}
	}
}
function preventDefaultAnchorTags() {
	var a = document.getElementsByClassName('room');
	for (var i = 0; i < a.length; ++i) {
		a[i].addEventListener('click', prevDef, false);
	}
}
function prevDef(evt) {
	evt.preventDefault();
}