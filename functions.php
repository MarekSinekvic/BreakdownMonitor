<?php
function clear_duplicate_cookies() {
    // If headers have already been sent, there's nothing we can do
    if (headers_sent()) {
        return;
    }

    $cookies = array();
    foreach (headers_list() as $header) {
        // Identify cookie headers
        if (strpos($header, 'Set-Cookie:') === 0) {
            $cookies[] = $header;
        }
    }
    // Removes all cookie headers, including duplicates
    header_remove('Set-Cookie');

    // Restore one copy of each cookie
    foreach(array_unique($cookies) as $cookie) {
        header($cookie, false);
    }
}
function f_create($path) {
	if (!file_exists($path)) {
		$f = fopen($path, 'w');
		fclose($f);
	}
}
function f_delete($path) {unlink($path);}
function f_rename($path, $name) {rename($path, $name);}
function f_addText($path, $text) {
	$f = fopen($path, 'a');
	fwrite($f, $text);
	fclose($f);
}
function f_getText($path) {
	$f = fopen($path, 'r');
	if (filesize($path) > 0) { $data = fread($f, filesize($path)); }
	fclose($f);
	return $data;
}
function f_rewriteFile($path, $text) {
	unlink($path);
	$f = fopen($path, 'w');
	fwrite($f, $text);
	fclose($f);
}

function f_optionAdd($path, $text, $islast = false) {
	$f = fopen($path, 'a');
	if (filesize($path) == 0) {
		if (!$islast) {
			fwrite($f, $text. "\n");
		} else {
			fwrite($f, $text);
		}
	} 
	fclose($f);
}
function f_optionGet($path, $line) {
	$lines = file($path);
	$lines[$line-1]=substr($lines[$line-1],0,strlen($lines[$line-1])-1);
	return $lines[$line-1];
}
function f_optionRewrite($path, $line, $text, $islast = false) {
	$lines = file($path);
	if (!$islast) {
		$lines[$line-1]=$text. "\n";
	} else {
		$lines[$line-1]=$text;
	}
	unlink($path);
	file_put_contents($path, $lines);
}
?>

<script>
function go(path) {document.location = path;}
</script>