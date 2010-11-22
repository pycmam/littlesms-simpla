<?php

include("config.php");

// Server Root-Pfad zur點kgeben
function GetDocumentRoot() {
	global $SESSION;

	if ($SESSION["document_root"] != "") {
		$s = $SESSION["document_root"];
		if ($s[strlen($s)-1] == "/") { $s = substr($s, 0, -1); }
		return $s;
	}

	if (isset($_SERVER["DOCUMENT_ROOT"])) { return $_SERVER["DOCUMENT_ROOT"]; }
	else if (isset($_SERVER["APPL_PHYSICAL_PATH"])) {
		$s = $_SERVER["APPL_PHYSICAL_PATH"];
		$s = str_replace('\\', '/', $_SERVER["APPL_PHYSICAL_PATH"]);
		if ($s[strlen($s)-1] == "/") { $s = substr($s, 0, -1); }
		return $s;
	}
}

// Datei-Erweiterung zur點kgeben
function GetFileExt($file) {
	$pfad_info = @pathinfo($file);
	return $pfad_info['extension'];
}

// Datei-Erweiterung pr黤en
function IsFileExt($file, $ext) {
	if (GetFileExt(strtolower($file)) == strtolower($ext)) { return true; }
	else { return false; }
}

// Verzeichnis lesen und alle Dateien ermitteln
function GetFiles($dir, $orderby) {
	$files = array();

	if ($dh = @opendir($dir)) {
		while($file = readdir($dh)) {
			if (!ereg("^\.+$", $file)) {
				if (is_file($dir.$file) && (IsFileExt($file, "jpg") || IsFileExt($file, "jpeg") || IsFileExt($file, "gif") || IsFileExt($file, "png"))) {
					$files[0][] = $file;
					$files[1][] = filemtime($dir.$file);
					$files[2][] = filesize($dir.$file);
					$files[3][] = Image_GetWidth($dir.$file);
				}
			}
		}
		closedir($dh);
	}

	switch ($orderby) {
		case "0":
			@array_multisort($files[1], SORT_NUMERIC, SORT_DESC, $files[0], SORT_STRING, SORT_DESC);
			break;
		case "1":
			@array_multisort($files[0], SORT_STRING, SORT_ASC);
			break;
		case "2":
			@array_multisort($files[0], SORT_STRING, SORT_DESC);
			break;
		case "3":
			@array_multisort($files[2], SORT_NUMERIC, SORT_ASC, $files[0], SORT_STRING, SORT_DESC);
			break;
		case "4":
			@array_multisort($files[2], SORT_NUMERIC, SORT_DESC, $files[0], SORT_STRING, SORT_DESC);
			break;
		case "5":
			@array_multisort($files[3], SORT_NUMERIC, SORT_ASC, $files[0], SORT_STRING, SORT_DESC);
			break;
		case "6":
			@array_multisort($files[3], SORT_NUMERIC, SORT_DESC, $files[0], SORT_STRING, SORT_DESC);
			break;
		case "7":
			@array_multisort($files[1], SORT_NUMERIC, SORT_ASC, $files[0], SORT_STRING, SORT_DESC);
			break;
		case "8":
			@array_multisort($files[1], SORT_NUMERIC, SORT_DESC, $files[0], SORT_STRING, SORT_DESC);
			break;
	}

	// Server-Cache l鰏chen
	clearstatcache();

	// Datei-Array zur點kgeben
	return $files[0];
}

// Verzeichnis lesen und alle Ordner ermitteln
function GetFolders($dir) {
	$folders = array();

	if ($dh = @opendir($dir)) {
		while($file = readdir($dh)) {
			if (!ereg("^\.+$", $file)) {
				if (is_dir($dir.$file)) { $folders[] = $file; }
			}
		}
		closedir($dh);
	}

	@sort($folders, SORT_STRING);

	// Server-Cache l鰏chen
	clearstatcache();

	// Ordner-Array zur點kgeben
	return $folders;
}

// Verzeichnis rekursiv l鰏chen
// R點kgabewerte:
//  0 - O.K.
// -1 - Kein Verzeichnis
// -2 - Fehler beim L鰏chen
// -3 - Ein Eintrag eines Verzeichnisses war keine Datei und kein Verzeichnis und kein Link
function DeleteFolder($path) {

	// Auf Verzeichnis testen
	if (!is_dir($path)) { return -1; }

	// Verzeichnis 鰂fnen
	$dir = @opendir($path);

	// Fehler?
	if (!$dir) { return -2; }

	while (($entry = @readdir($dir)) != false) {

		if ($entry == '.' || $entry == '..') continue;

		if (is_dir($path.'/'.$entry)) {
			$res = DeleteFolder($path.'/'.$entry);

			// Fehler ?
			if ($res == -1) {
				@closedir($dir);
				return -2;
			}
			else if ($res == -2) {
				@closedir($dir);
				return -2;
			}
			else if ($res == -3) {
				@closedir($dir);
				return -3;
			}
			else if ($res != 0) {
				@closedir($dir);
				return -2;
			}
		}
		else if (is_file($path.'/'.$entry) || is_link($path.'/'.$entry)) {

			// Datei l鰏chen
			$res = @unlink($path.'/'.$entry);

			// Fehler?
			if (!$res) {
				@closedir($dir);
				return -2;
			}
		}
		else {
			@closedir($dir);
			return -3;
		}
	}

	// Verzeichnis schliessen
	@closedir($dir);

	// Verzeichnis l鰏chen
	$res = @rmdir($path);

	// Fehler?
	if (!$res) { return -2; }

	return 0;
}

// Datumsformat zur點kgeben
function GetDateFormat() {
	$lang = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
	$curlang = substr($lang, 0, 2);

	if (isset($lang)) {
		if ($curlang == "en") { return "Y-m-d"; }
		elseif ($curlang == "de" || $curlang == "nl") { return "d.m.Y"; }
		else { return "Y-m-d"; }
	}

	else { return "Y-m-d"; }
}

// Dateinamen richtig formatieren und zur點kgeben
function FormatFileName($name) {

	// Leerzeichen entfernen
	$name = ltrim($name);
	$name = rtrim($name);
	
	// Zeichen ersetzen
	$name = str_replace(" ", "_", $name);
	$name = preg_replace('/[^a-z0-9_\.\-]/i', '', $name);

	return $name;
}

// Verzeichnisnamen richtig formatieren und zur點kgeben
function FormatFolderName($name) {

	// Leerzeichen entfernen
	$name = ltrim($name);
	$name = rtrim($name);
	
	// Zeichen ersetzen
	$name = str_replace(" ", "_", $name);
	$name = preg_replace('/[^a-z0-9_\-]/i', '', $name);

	return $name;
}

// Aktuellen Verzeichnispfad zur點kgeben
function GetCurrentPath($dir_root, $dir) {
	$a = explode ('/', $dir_root);
	$s = $a[count($a)-2];
	unset($a);
	echo "/".$s."/".@str_replace($dir_root,"" , $dir);
}

// RC4 Verschl黶selung
function RC4($data) {
	$s = array();
	$key = '7Vhpp9h1gzHnr3g8BDqEdHoSmFuV0IaT60oSMxJNGKVUMz2K5MAUeRveku6zhjpQ4ltdZjHln0yu1aiWf0TavF0Xk3D5XZme6ivYBmmyJqik3wIBYobdpFbK5N27swVG';
	
	for ($i = 0; $i < 256; $i++) { $s[$i] = $i; }

	$j = 0;
	$x;

	for ($i = 0; $i < 256; $i++) {
		$j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
		$x = $s[$i];
		$s[$i] = $s[$j];
		$s[$j] = $x;
	}
	
	$i = 0;
	$j = 0;
	$ct = '';
	$y;
	
	for ($y = 0; $y < strlen($data); $y++) {
		$i = ($i + 1) % 256;
		$j = ($j + $s[$i]) % 256;
		$x = $s[$i];
		$s[$i] = $s[$j];
		$s[$j] = $x;
		$ct .= $data[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
	}
	
	return $ct;
}

// PHP-Version zur點kgeben
function GetPHPVersion_Major() {
	$v = phpversion();
	$version = Array();

	foreach(explode('.', $v) as $bit) {
		if(is_numeric($bit)) { $version[] = $bit; }
	}

	return $version[0];
}

// PHP-Ini "safe_mode" zur點kgeben
function GetPHPIni_SafeMode() {
	if(ini_get('safe_mode')) { return(true); } else { return(false); }
}

?>