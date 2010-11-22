function init_balloon()
{
	ValidateForms();
	CreateBaloon();
}

if (window.attachEvent) {
	window.attachEvent("onload", init_balloon);
} else if (window.addEventListener) {
	window.addEventListener("DOMContentLoaded", init_balloon, false);
} else {
	document.addEventListener("DOMContentLoaded", init_balloon, false);
}
