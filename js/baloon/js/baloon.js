// (C) Netlogic, 2003

function CreateBaloon() {
	baloon = document.createElement('DIV');
	baloon.setAttribute('id', 'baloon');

	baloonHeader = document.createElement('DIV');
	baloonHeader.setAttribute('id', 'baloonHeader');
	baloonHeader.setAttribute('class', 'direct');

	baloonBody   = document.createElement('DIV');
	baloonBody.setAttribute('id', 'baloonBody');

	baloonFooter = document.createElement('DIV');
	baloonFooter.setAttribute('id', 'baloonFooter');

	baloonBody.innerText = 'baloon';

	baloon.appendChild(baloonHeader);
	baloon.appendChild(baloonBody);
	baloon.appendChild(baloonFooter);

	baloon.onmouseover   = function(e) { this.style.filter = "Alpha(Opacity='100')"; this.style.cursor = 'pointer'; this.style.MozOpacity = '1';}
	baloon.onmouseout    = function(e) { this.style.filter = "Alpha(Opacity='75')";  this.style.cursor = 'auto'; this.style.MozOpacity = '0.75'; }
	baloon.onselectstart = function(e) { return false; }
	baloon.onclick       = function(e) { this.style.display = 'none'; }

	document.body.appendChild(baloon);

	window.onresize      = function(e) { document.getElementById('baloon').style.display = 'none'; }
}

function ShowBaloon(i) {
	baloon = document.getElementById('baloon');

	document.getElementById('baloonBody').innerHTML = i.getAttribute('notice') && i.getAttribute('notice').length ? i.getAttribute('notice') : 'ERROR';
	baloon.style.display = 'block';

	var xleft=0;
	var xtop=0;
	o = i;

	do {
		xleft += o.offsetLeft;
		xtop  += o.offsetTop;

	} while (o=o.offsetParent);

	xwidth  = i.offsetWidth  ? i.offsetWidth  : i.style.pixelWidth;
	xheight = i.offsetHeight ? i.offsetHeight : i.style.pixelHeight;

	bwidth =  baloon.offsetWidth  ? baloon.offsetWidth  : baloon.style.pixelWidth;

	w = window;

	xbody  = document.compatMode=='CSS1Compat' ? w.document.documentElement : w.document.body;
	dwidth = xbody.clientWidth  ? xbody.clientWidth   : w.innerWidth;
	bwidth = baloon.offsetWidth ? baloon.offsetWidth  : baloon.style.pixelWidth;

	flip = !(xwidth - 10 + xleft + bwidth < dwidth);

	baloon.style.top  = xheight - 10 + xtop + 'px';
	baloon.style.left = (xleft + xwidth - (flip ? bwidth : 0)  - 25) + 'px';

	document.getElementById('baloonHeader').className = flip ? 'baloonHeaderFlip' : 'baloonHeaderDirect';

	i.focus();
	return false;
}