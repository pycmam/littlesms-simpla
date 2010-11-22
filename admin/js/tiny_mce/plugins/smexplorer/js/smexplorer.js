// Datei löschen
function SMExplorer_DeleteFile(get, file) {
	var b = window.confirm('"' + file + '"' + '\n' + tinyMCEPopup.getLang('smexplorer.file_delete', '?'));
	if (b) {
		window.location.href = 'index.php?get=' + get + '&file=' + file + '&action=delete';
	}
};

// Ausgewählte Dateien löschen
function SMExplorer_DeleteFiles(get, max) {
	var b = window.confirm(tinyMCEPopup.getLang('smexplorer.files_delete', '?'));
	if (b) {
		document.form_files.action = 'index.php?get=' + get + '&max=' + max + '&action=delete2';
		document.form_files.submit();
	}
};

// Datei umbenennen
function SMExplorer_RenameFile(get, file, name, obj) {
	var b = window.confirm('"' + file + '"  ->  "' + name + '"' + '\n' + tinyMCEPopup.getLang('smexplorer.file_rename', '?'));
	if (b) {
		window.location.href = 'index.php?get=' + get + '&file=' + file + '&name=' + name + '&action=rename';
	}
	else {
		obj.value = file;
		obj.style.backgroundColor = 'transparent';
		obj.style.borderWidth = '0px';
		obj.blur();
	}
};

// Neuen Ordner erstellen
function SMExplorer_NewFolder(get) {
	var s = Prompt.show(tinyMCEPopup.getLang('smexplorer.folder_new', '?') + ':');
	if (s != null) {
		window.location.href = 'index.php?get=' + get + '&name=' + s + '&action=newfolder';
	}
};

// Verzeichnis umbenennen
function SMExplorer_RenameFolder(get, folder) {
	var s = Prompt.show(tinyMCEPopup.getLang('smexplorer.folder_rename', '?') + ':', folder);
	if (s != null) {
		window.location.href = 'index.php?get=' + get + '&folder=' + folder + '&name=' + s + '&action=rename';
	}
};

// Verzeichnis löschen
function SMExplorer_DeleteFolder(get, folder) {
	var b = window.confirm('"' + folder + '"\n' + tinyMCEPopup.getLang('smexplorer.folder_delete', '?'));
	if (b) {
		window.location.href = 'index.php?get=' + get + '&folder=' + folder + '&action=delete';
	}
};

// Seite neu laden
function SMExplorer_PageReload(get) {
	window.location.href = 'index.php?get=' + get;
};

// Neu sortieren
function SMExplorer_Orderby(get) {
	window.location.href = 'index.php?get=' + get;
};

// Link einfügen
function SMExplorer_InsertFile(server, file, target) {
	var win = tinyMCEPopup.editor.plugins.smexplorer.GetWindow();

	if (win == null) {
		var html = '';
		var i = 0;
		var a = new Array();
		var ed = tinyMCEPopup.editor;
		var obj = ed.selection.getNode();
		var s = ed.selection.getContent();

		if (obj.nodeName == 'A') {
			ed.dom.setAttrib(obj, 'href', server + file);
			ed.dom.setAttrib(obj, 'target', target);
			tinyMCEPopup.close();
			return;
		}
		else if (obj.parentNode.nodeName == 'A') {
			ed.dom.setAttrib(obj.parentNode, 'href', server + file);
			ed.dom.setAttrib(obj.parentNode, 'target', target);
			tinyMCEPopup.close();
			return;
		}
		else if (obj.nodeName == 'IMG') {

			// Allgemeine Universalattribute ermitteln
			a['id'] = obj.getAttribute('id');
			a['class'] = obj.getAttribute('class');
			a['style'] = obj.getAttribute('style');
			a['title'] = obj.getAttribute('title');

			// Grafik-Attribute ermitteln
			a['src'] = obj.getAttribute('mce_src');
			a['align'] = obj.getAttribute('align');
			a['alt'] = obj.getAttribute('alt');
			a['border'] = obj.getAttribute('border');
			a['height'] = obj.getAttribute('height');
			a['width'] = obj.getAttribute('width');
			a['hspace'] = obj.getAttribute('hspace');
			a['longdesc'] = obj.getAttribute('longdesc');
			a['name'] = obj.getAttribute('name');
			a['usemap'] = obj.getAttribute('usemap');
			a['vspace'] = obj.getAttribute('vspace');

			// Border ändern
			a['border'] = '0';

			s = '';
			for (var attr in a) {
				if (i >= 15) { continue; }
				if (a[attr] != null) { s += attr + '="' + a[attr] + '" '; }
				i++;
			}

			html = '<a href="' + server + file + '" target="' + target + '"><img ' + s + '/></a>';
		}
		else if (s != '') {
			html = '<a href="' + server + file + '" target="' + target + '">' + s + '</a>';
		}
		else {
			s = Prompt.show(ed.getLang('smexplorer.insert', '?') + ':');
			
			if (s != null) {
				if (s.length != 0) { html = '<a href="' + server + file + '" target="' + target + '">' + s + '</a>'; }
				else {
					s = file.lastIndexOf('/') + 1;
					html = '<a href="' + server + file + '" target="' + target + '">' + file.substr(s, file.length) + '</a>';
				}
			}
			else { return; }
		}

		ed.execCommand('mceInsertContent', true, html);
		tinyMCEPopup.close();
	}
	else {
		tinyMCEPopup.editor.plugins.smexplorer.SetWindow(null);

		var id = tinyMCEPopup.editor.plugins.smexplorer.GetId();
		var s = tinyMCEPopup.editor.settings['document_base_url'];
		var src = server + file;

		if (server == '') { src = src.replace(s, ''); }

		win.document.getElementById(id).value = src;

		tinyMCEPopup.close();
		win.focus();
	}
};

// Verzeichnis anzeigen
function SMExplorer_OpenFolder(get) {
	window.location.href = 'index.php?get=' + get;
};

// Datei-Upload starten
function SMExplorer_Upload_Save(get) {
	if (document.form_upload.input1.value != '' && document.form_upload.edit1.value != '') {
		document.getElementById('wait').style.display = 'block';
		document.form_upload.action = 'index.php?get=' + get + '&action=upload';
		document.form_upload.submit();
	}
	else {
		window.alert(tinyMCEPopup.getLang('smexplorer.message_upload_3', '?'));
	}
};

// Dateiname anzeigen
function SMExplorer_Upload_ShowFileName() {
	document.form_upload.edit1.value = GetFileName(document.form_upload.input1.value);
	document.form_upload.edit2.value = '.' + GetFileExt(document.form_upload.input1.value);
};

// Upload anzeigen
function SMExplorer_Upload_Show() {
	SMExplorer_View_Close();
	document.getElementById('upload').style.display = 'block';
	SMExplorer_WindowResize();
};

// Upload schließen
function SMExplorer_Upload_Close() {
	document.getElementById('upload').style.display = 'none';
	SMExplorer_WindowResize();
};

// Ansicht anzeigen
function SMExplorer_View_Show(file) {
	var obj = document.getElementById('m6');

	if (obj != null) {
		obj.style.display = 'block';
	}
	SMExplorer_Upload_Close();
	document.getElementById('view').style.display = 'block';
	document.getElementById('view_iframe').src = file;
	SMExplorer_WindowResize();
};

// Ansicht schließen
function SMExplorer_View_Close() {
	var obj = document.getElementById('m6');

	if (obj != null) {
		obj.style.display = 'none';
	}
	document.getElementById('view').style.display = 'none';
	document.getElementById('view_iframe').src = '';
	SMExplorer_WindowResize();
};

// Breite Verzeichnisbaum
var FOLDER_TREE_WIDTH = 220;

// Breite Verzeichnisbaum setzen
function SMExplorer_SetFolderTreeWidth(w) {
	if (w > 100) { FOLDER_TREE_WIDTH = w; } else { FOLDER_TREE_WIDTH = 100; } 
};

// Oberfläche an Fenstergröße anpassen
function SMExplorer_WindowResize() {
	var obj;

	obj = document.getElementById('main_left');
	if (obj != null) {
		obj.style.height = GetWindowHeight() - 58 + 'px';
		obj.style.width = FOLDER_TREE_WIDTH + 'px';
	}

	obj = document.getElementById('main_right');
	if (obj != null) {
		obj.style.height = GetWindowHeight() - 58 + 'px';
		obj.style.width = GetWindowWidth() - (FOLDER_TREE_WIDTH + 6) + 'px';
	}

	obj = document.getElementById('splitter_vertical');
	if (obj != null) {
		obj.style.height = GetWindowHeight() - 58 + 'px';
	}

	obj = document.getElementById('msg');
	if (obj != null) {
		obj.style.top = (GetWindowHeight()/2)-(obj.offsetHeight/2) + 'px';
		obj.style.left = (GetWindowWidth()/2)-(obj.offsetWidth/2) + 'px';
	}

	obj = document.getElementById('upload');
	if (obj != null && obj.style.display == 'block') {
		jSMT.Resize(226, obj.offsetHeight + 58);
	}
	else if (obj != null && (obj.style.display == 'none' || obj.style.display == '')) { jSMT.Resize(226, 58); }

	obj = document.getElementById('view');
	if (obj != null && obj.style.display == 'block') {
		jSMT.Resize(226, obj.offsetHeight + 58);
	}

	obj = document.getElementById('upload_input_1');
	if (obj != null) {
		obj.size = Math.round((GetWindowWidth() - (FOLDER_TREE_WIDTH + 100))/10);
	}

	obj = document.getElementById('upload_edit_1');
	if (obj != null) {
		obj.style.width = GetWindowWidth() - (FOLDER_TREE_WIDTH + 77) + 'px';
	}

	obj = document.getElementById('wait');
	if (obj != null) {
		obj.style.height = GetWindowHeight() + 'px';
		obj.style.width = GetWindowWidth() + 'px';
	}

	obj = document.getElementById('wait_animation');
	if (obj != null) {
		obj.style.top = GetWindowHeight()/2-45 + 'px';
		obj.style.left = GetWindowWidth()/2-45 + 'px';
	}
};

// Menü Initialisierung
function SMExplorer_MenuIni() {
	if (document.getElementById('m1') != null) { document.getElementById('m1').title = tinyMCEPopup.getLang('smexplorer.menu_hint_1', '?'); }
	if (document.getElementById('m2') != null) { document.getElementById('m2').title = tinyMCEPopup.getLang('smexplorer.menu_hint_2', '?'); }
	if (document.getElementById('m3') != null) { document.getElementById('m3').title = tinyMCEPopup.getLang('smexplorer.menu_hint_3', '?'); }
	if (document.getElementById('m4') != null) { document.getElementById('m4').title = tinyMCEPopup.getLang('smexplorer.menu_hint_4', '?'); }
	if (document.getElementById('m5') != null) { document.getElementById('m5').title = tinyMCEPopup.getLang('smexplorer.menu_hint_5', '?'); }
	if (document.getElementById('m6') != null) { document.getElementById('m6').title = tinyMCEPopup.getLang('smexplorer.menu_hint_6', '?'); }
	if (document.getElementById('m7') != null) { document.getElementById('m7').title = tinyMCEPopup.getLang('smexplorer.menu_hint_7', '?'); }
};

var edit = false;
var edit_fext = 0;

// Eingabefeld markieren
function SMExplorer_InputClick(obj, fext) {
	edit_fext = fext;

	if (!edit) {
		obj.style.backgroundColor = '#ffffff';
		obj.style.borderWidth = '1px';
		if (!edit_fext) {obj.value = GetFileNameWithoutExt(obj.value); }
	}
	else {
		obj.style.backgroundColor = 'transparent';
		obj.style.borderWidth = '0px';
	}
};

// Datei übernehmen
function SMExplorer_InputBlur(obj, file) {
	if (!edit) {
		obj.value = file;
		obj.style.backgroundColor = 'transparent';
		obj.style.borderWidth = '0px';
	}
};

// Eingabe mit der Enter-Taste übernehmen
function SMExplorer_InputEnter(e, obj, file, get) {
	if (!e) { e = window.event; }

	if (e.keyCode == 13) {
		edit = true;
		if (!edit_fext && file != (obj.value + '.' + GetFileExt(file))) { SMExplorer_RenameFile(get, file, obj.value + '.' + GetFileExt(file), obj); }
		else if (edit_fext && file != obj.value) { SMExplorer_RenameFile(get, file, obj.value, obj); }
		else {
			obj.style.backgroundColor = 'transparent';
			obj.style.borderWidth = '0px';
			edit = false;
			obj.blur();
		}
		edit = false;
	}
};

// Alle Dateien auswählen/ Auswahl entfernen
function SMExplorer_CheckAll(max) {
	var obj = document.getElementById('th_checkbox');
	var obj2;
	var obj3 = document.getElementById('m5');

	if (obj.checked) {
		for (var i = 0; i < max; i++) {
			obj = document.getElementById('td_checkbox'+i);
			obj2 = document.getElementById('fn_input'+i);

			with (obj) {
				checked = true;
				value = '1';
			}
			obj2.style.color = '#ff0000';
			obj3.style.display = 'block';
		}
	}
	else {
		for (var i = 0; i < max; i++) {
			obj = document.getElementById('td_checkbox'+i);
			obj2 = document.getElementById('fn_input'+i);

			with (obj) {
				checked = false;
				value = '0';
			}
			obj2.style.color = '';
			obj3.style.display = 'none';
		}
	}
};

// Datei auswählen/ Auswahl entfernen
function SMExplorer_Check(max) {
	var b = false;
	var obj;
	var obj2;
	var obj3 = document.getElementById('m5');

	for (var i = 0; i < max; i++) {
		obj = document.getElementById('td_checkbox'+i);
		obj2 = document.getElementById('fn_input'+i);

		if (obj != null && obj2 != null) {
			if (obj.checked) {
				obj.value = '1';
				obj2.style.color = '#ff0000';
				b = true;
			}
			else {
				obj.value = '0';
				obj2.style.color = '';
			}
		}
	}
	if (b) { obj3.style.display = 'block'; }
	else { obj3.style.display = 'none'; }
};

//. Tools .....................................................................

// Dateiname zurückgeben
function GetFileName(s) {
	var i = s.lastIndexOf('\\') + 1;
	var ss = GetFileExt(s);
	return s.substring(i, s.length - ss.length - 1);
};

// Dateiendung zurückgeben
function GetFileExt(s) {
	var i = s.lastIndexOf('.') + 1;
	return s.substr(i, s.length);
};

// Dateinamen ohne Dateiendung zurückgeben
function GetFileNameWithoutExt(s) {
	var i = s.lastIndexOf('.') + 1;
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
	if (clist) { return this.replace (new RegExp ('^[' + clist + ']+'), ''); }
	return this.replace (/^\s+/, '');
};

// Rechts-Trim
String.prototype.rtrim = function (clist) {
	if (clist) { return this.replace (new RegExp ('[' + clist + ']+$'), ''); }
	return this.replace (/\s+$/, '');
};

// Bilder vorladen
function ImagePreloader() {
	document.ipreload = new Array();

	if(document.images) {
		for(var i = 0; i < ImagePreloader.arguments.length; i++) {
			document.ipreload[i] = new Image();
			document.ipreload[i].src = 'img/' + ImagePreloader.arguments[i];
		}
	}
};

//. Ausführen .................................................................

// Bilder vorladen
ImagePreloader('/jsmbutton/bg.png','/jsmbutton/bg_active.png','/jsmbutton/rbg.png','/jsmbutton/rbg_active.png');
ImagePreloader('/jsmtable/th_active_bg.gif','/jsmtable/th_bg.gif','/jsmtable/th_down_bg.gif','/jsmtable/tr_active_bg.gif');
ImagePreloader('/jsmtable/icon_down_9x11.png','/jsmtable/icon_none_9x11.png','/jsmtable/icon_up_9x11.png');
ImagePreloader('icon_tree_16x16.png','icon_folder_16x16.png','icon_file_16x16.png','icon_image_16x16.png','icon_delete_16x16.png','icon_insert_16x16.png');
ImagePreloader('icon_delete_folder_24x24.png','icon_delete_file_24x24.png','icon_new_folder_24x24.png','icon_reload_24x24.png','icon_rename_folder_24x24.png','icon_separator.png','icon_upload_24x24.png');
ImagePreloader('wait.gif','wait_2.gif');

// HTML-Titel setzen
document.title = document.title + ' (1.4.2)';