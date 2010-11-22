// Datei löschen
function Image_Delete(get, file) {
	var b = window.confirm('"' + file + '"\n' + tinyMCEPopup.getLang('smimage.message_image_delete', '?'));
	if (b) {
		window.location.href = "index.php?get=" + get + "&file=" + file + "&action=delete";
	}
};

// Datei umbenennen
function Image_Rename(get, file) {
	var ext = GetFileExt(file);
	var s = Prompt.show(tinyMCEPopup.getLang('smimage.image_rename', '?') + ":", GetFileNameWithoutExt(file));
	if (s != null) {
		window.location.href = "index.php?get=" + get + "&file=" + file + "&name=" + s.rtrim() + '.' + ext + "&action=rename";
	}
};

// Datei umbenennen
function Image_Rename2(get, file, obj) {
	var b = window.confirm('"' + file + '"  ->  "' + obj.value + '"' + '\n' + tinyMCEPopup.getLang('smimage.message_image_rename', '?'));
	if (b) {
		window.location.href = "index.php?get=" + get + "&file=" + file + "&name=" + obj.value + "&action=rename";
	}
	else {
		obj.value = file;
		obj.style.backgroundColor = 'transparent';
		obj.style.borderWidth = '0px';
		obj.blur();
	}
};

// Verzeichnis umbenennen
function Folder_Rename(get, folder) {
	var s = Prompt.show(tinyMCEPopup.getLang('smimage.folder_rename', '?') + ":", folder);
	if (s != null) {
		window.location.href = "index.php?get=" + get + "&folder=" + folder + "&name=" + s + "&action=rename";
	}
};

// Verzeichnis umbenennen
function Folder_Rename2(get, folder, obj) {
	var b = window.confirm('"' + folder + '"  ->  "' + obj.value + '"' + '\n' + tinyMCEPopup.getLang('smimage.message_folder_rename', '?'));
	if (b) {
		window.location.href = "index.php?get=" + get + "&folder=" + folder + "&name=" + obj.value + "&action=rename";
	}
	else {
		obj.value = folder;
		obj.style.backgroundColor = 'transparent';
		obj.style.borderWidth = '0px';
		obj.blur();
	}
};

// Verzeichnis löschen
function Folder_Delete(get, folder) {
	var b = window.confirm ('"' + folder + '"\n' + tinyMCEPopup.getLang('smimage.message_folder_delete', '?'));
	if (b) {
		window.location.href = "index.php?get=" + get + "&folder=" + folder + "&action=delete";
	}
};

// Bild drehen
function Image_Rotate(get, file, degrees) {
	var b = window.confirm ('"' + file + '"\n' + tinyMCEPopup.getLang('smimage.message_image_rotate', '?'));
	if (b) {
		window.location.href = "index.php?get=" + get + "&file=" + file + "&degrees=" + degrees + "&action=rotate";
	}
};

// Neuen Ordner erstellen
function NewFolder(get) {
	var s = Prompt.show(tinyMCEPopup.getLang('smimage.folder_new', '?') + ":");
	if (s != null) {
		window.location.href = "index.php?get=" + get + "&name=" + s + "&action=newfolder";
	}
};

// Seite neu laden
function PageReload(get) {
	window.location.href = 'index.php?get=' + get;
};

// Bild-Menü anzeigen/ ausblenden
function ShowImageMenu(id, display) {
	if (document.getElementById(id) != null) { document.getElementById(id).style.display = display; }
};

// Bild einfügen
function InsertImage(server, file, width, height) {
	var win = tinyMCEPopup.editor.plugins.smimage.GetWindow();

	if (win == null) {
		var alt = Prompt.show(tinyMCEPopup.getLang('smimage.image_alt', '?') + ":");
		
		if (alt != null) {
			var html = '<img src="' + server + file + '" width="' + width + '" height="' + height + '" border="0" alt="' + alt + '"/>';

			tinyMCEPopup.execCommand('mceInsertContent', true, html);
			tinyMCEPopup.close();
		}
	}
	else {
		tinyMCEPopup.editor.plugins.smimage.SetWindow(null);

		var id = tinyMCEPopup.editor.plugins.smimage.GetId();
		var s = tinyMCEPopup.editor.settings['document_base_url'];
		var src = server + file;

		if (server == '') { src = src.replace(s, ''); }

		win.document.getElementById(id).value = src;
		if (win.ImageDialog.getImageData) win.ImageDialog.getImageData();
        if (win.ImageDialog.showPreviewImage) win.ImageDialog.showPreviewImage(src);

		tinyMCEPopup.close();
		win.focus();
	}
};

// Verzeichnis anzeigen
function OpenFolder(get) {
	window.location.href = 'index.php?get=' + get;
};

// Eine Verzeichnisebene höher anzeigen
function BackFolder(get) {
	window.location.href = 'index.php?get=' + get + '&action=back';
};

// Datei-Upload starten
function Upload_Save(get, file) {
	if (document.form1.input1.value != '' && document.form1.edit1.value != '') {
		if (GetFileExt(file.toLowerCase()) != 'jpg' && GetFileExt(file.toLowerCase()) != 'jpeg' && GetFileExt(file.toLowerCase()) != 'gif' && GetFileExt(file.toLowerCase()) != 'png') {
			window.alert(tinyMCEPopup.getLang('smimage.message_upload_3', '?'));
		}
		else {
			document.getElementById('wait').style.display = 'block';
			document.form1.action = "index.php?get=" + get + "&action=upload";
			document.form1.submit();
		}
	}
	else {
		window.alert(tinyMCEPopup.getLang('smimage.message_upload_2', '?'));
	}
};

// Dateiname anzeigen
function Upload_ShowFileName() {
	document.form1.edit1.value = GetFileName(document.form1.input1.value);
	document.form1.edit2.value = '.' + GetFileExt(document.form1.input1.value);
};

// Oberfläche anpassen: Menüpunkt "Bilder"
function SMImage_WindowResize() {
	var obj;

	// Fenstergröße ermitteln und Oberfläche anpassen
	obj = document.getElementById("main");
	if (obj != null) {
		obj.style.height = GetWindowHeight() - 66 + 'px';
		obj.style.width = GetWindowWidth() - 8 + 'px';
	}

	obj = document.getElementById("main_upload");
	if (obj != null) {
		obj.style.height = GetWindowHeight() - document.getElementById("upload").offsetHeight - 70 + 'px';
		obj.style.width = GetWindowWidth() - 8 + 'px';
	}

	obj = document.getElementById('div_table');
	if (obj != null) {
		obj.style.height = GetWindowHeight() - 83 + 'px';
		obj.style.width = GetWindowWidth() + 'px';
	}

	obj = document.getElementById('table');
	if (obj != null) {
		obj.style.width = document.getElementById('div_table').offsetWidth + 'px';
	}

	obj = document.getElementById("wait");
	if (obj != null) {
		obj.style.height = GetWindowHeight() + 'px';
		obj.style.width = GetWindowWidth() + 'px';
	}

	obj = document.getElementById("wait_animation");
	if (obj != null) {
		obj.style.top = GetWindowHeight() / 2 - 45 + 'px';
		obj.style.left = GetWindowWidth() / 2 - 45 + 'px';
	}
};

// Vorschaubild laden und anzeigen
function SMImage_ShowThumbnail(id, src, size, jpg_quality) {
	obj = document.getElementById(id);

	if (obj != null) {
		img = new Image();
		img.src = 'php/thumbnail.php?src=' + src + '&size=' + size + '&jpg_quality=' + jpg_quality;
		document.images[id].src = img.src;
	}
};

// Vorschaubilder laden und anzeigen
function SMImage_LoadThumbnail(a, dir, thumbnail_size, jpg_quality) {
	for (i = 0; i < a.length; i++) {
		if (document.getElementById('thumbnail' + i) != null) {
			document.getElementById('thumbnail' + i).style.display = 'block';
		}
	}

	for (i = 0; i < a.length; i++) {
		SMImage_ShowThumbnail('th' + i, '' + dir + a[i] + '', '' + thumbnail_size + '', '' + jpg_quality + '');
	}
};

// Menü Initialisierung
function SMImage_MenuIni() {
	if (document.getElementById('m11') != null) { document.getElementById('m11').title = tinyMCEPopup.getLang('smimage.menu_hint_11', '?'); }
	if (document.getElementById('m12') != null) { document.getElementById('m12').title = tinyMCEPopup.getLang('smimage.menu_hint_12', '?'); }
	if (document.getElementById('m2') != null) { document.getElementById('m2').title = tinyMCEPopup.getLang('smimage.menu_hint_2', '?'); }
	if (document.getElementById('m3') != null) { document.getElementById('m3').title = tinyMCEPopup.getLang('smimage.menu_hint_3', '?'); }
	if (document.getElementById('m4') != null) { document.getElementById('m4').title = tinyMCEPopup.getLang('smimage.menu_hint_4', '?'); }
	if (document.getElementById('m5') != null) { document.getElementById('m5').title = tinyMCEPopup.getLang('smimage.menu_hint_5', '?'); }
};

// Tabellen Initialisierung
function SMTableIni() {
	var obj = null;
	var a = new Array('smtable_h1', 'smtable_h2', 'smtable_h3', 'smtable_h4');

	for (i = 0; i < a.length; i++) {
		obj = document.getElementById(a[i]);
		if (obj != null) { 
			if (((obj.title*1) % 2) == 0) { SMTable_Header_SetTitle(obj, tinyMCEPopup.getLang('smimage.table_sort_hint_2', '?')); }
			else { SMTable_Header_SetTitle(obj, tinyMCEPopup.getLang('smimage.table_sort_hint_1', '?')); }
		}
	}
};

var edit = false;

// Eingabefeld "Dateiname" markieren
function SMImage_InputFileClick(obj) {
	if (!edit) {
		obj.style.backgroundColor = '#ffffff';
		obj.style.borderWidth = '1px';
	}
	else {
		obj.style.backgroundColor = 'transparent';
		obj.style.borderWidth = '0px';
	}
};

// Eingabefeld "Dateiname" nicht markieren
function SMImage_InputFileBlur(obj, file) {
	if (!edit) {
		obj.value = file;
		obj.style.backgroundColor = 'transparent';
		obj.style.borderWidth = '0px';
	}
};

// Eingabe "Dateiname" mit der Enter-Taste übernehmen
function SMImage_InputFileEnter(e, obj, file, get) {
	if (!e) { e = window.event; }

	if (e.keyCode == 13) {
		edit = true;

		if (file != obj.value) { Image_Rename2(get, file, obj); }
		else {
			obj.style.backgroundColor = 'transparent';
			obj.style.borderWidth = '0px';
			edit = false;
			obj.blur();
		}
		edit = false;
	}
};

// Eingabefeld "Verzeichnisname" markieren
function SMImage_InputFolderClick(obj) {
	if (!edit) {
		obj.style.backgroundColor = '#ffffff';
		obj.style.borderWidth = '1px';
	}
	else {
		obj.style.backgroundColor = 'transparent';
		obj.style.borderWidth = '0px';
	}
};

// Eingabefeld "Verzeichnisname" nicht markieren
function SMImage_InputFolderBlur(obj, folder) {
	if (!edit) {
		obj.value = folder;
		obj.style.backgroundColor = 'transparent';
		obj.style.borderWidth = '0px';
	}
};

// Eingabe "Dateiname" mit der Enter-Taste übernehmen
function SMImage_InputFolderEnter(e, obj, folder, get) {
	if (!e) { e = window.event; }

	if (e.keyCode == 13) {
		edit = true;

		if (folder != obj.value) { Folder_Rename2(get, folder, obj); }
		else {
			obj.style.backgroundColor = 'transparent';
			obj.style.borderWidth = '0px';
			edit = false;
			obj.blur();
		}
		edit = false;
	}
};

//. Tools .....................................................................

// Dateiname zurückgeben
function GetFileName(s) {
	var i = s.lastIndexOf("\\") + 1;
	var ss = GetFileExt(s);
	return s.substring(i, s.length - ss.length - 1);
};

// Dateiendung zurückgeben
function GetFileExt(s) {
	var i = s.lastIndexOf(".") + 1;
	return s.substr(i, s.length);
};

// Dateinamen ohne Dateiendung zurückgeben
function GetFileNameWithoutExt(s) {
	var i = s.lastIndexOf(".") + 1;
	return s.substr(0, i-1);
};

// Fensterhöhe zurückgeben
function GetWindowHeight() {
	var h = 0;

	if (self.innerHeight) {
		h = self.innerHeight;
	}
	else if (document.documentElement && document.documentElement.clientHeight) {
		h = document.documentElement.clientHeight;
	}
	else if (document.body) {
		h = document.body.clientHeight;
	}

	return h;
};

// Fensterbreite zurückgeben
function GetWindowWidth() {
	var w = 0;

	if (self.innerWidth) {
		w = self.innerWidth;
	}
	else if (document.documentElement && document.documentElement.clientWidth) {
		w = document.documentElement.clientWidth;
	}
	else if (document.body) {
		w = document.body.clientWidth;
	}

	return w;
};

// Links-Trim
String.prototype.ltrim = function (clist) {
	if (clist) { return this.replace (new RegExp('^[' + clist + ']+'), ''); }
	return this.replace(/^\s+/, '');
};

// Rechts-Trim
String.prototype.rtrim = function (clist) {
	if (clist) { return this.replace (new RegExp('[' + clist + ']+$'), ''); }
	return this.replace(/\s+$/, '');
};

// Bilder vorladen
function ImagePreloader() {
	document.ipreload = new Array();

	if(document.images) {
		for(var i = 0; i < ImagePreloader.arguments.length; i++) {
			document.ipreload[i] = new Image();
			document.ipreload[i].src = "img/" + ImagePreloader.arguments[i];
		}
	}
};

//. Ausführen .................................................................

// Bilder vorladen
ImagePreloader('/jsmbutton/bg.png','/jsmbutton/bg_active.png','/jsmbutton/rbg.png','/jsmbutton/rbg_active.png');
ImagePreloader('image_menu_bg.gif','image_menu_active_bg.gif');
ImagePreloader('table_th_bg.gif','table_th_active_bg.gif','table_th_down_bg.gif', 'table_tr_active_bg.gif','icon_up_9x11.png','icon_down_9x11.png','icon_none_9x11.png');
ImagePreloader('icon_delete_16x16.png','icon_insert_16x16.png','icon_rename_16x16.png','icon_rotate_left_16x16.png','icon_rotate_right_16x16.png');
ImagePreloader('icon_image_16x16.png','icon_folder_16x16.png','icon_folder_back_16x16.png');
ImagePreloader('wait.gif','wait_2.gif');

// HTML-Titel setzen
document.title = document.title + ' (1.5.2)';