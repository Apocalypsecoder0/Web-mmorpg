<?php
// Base::functions.php
function showPage()
{
	Debug::printMsg("", "showPage()", "Displaying page...");
	$p = basename($_SERVER['PHP_SELF'], ".php");
	$p = TEMPLATES_PATH.$p.".tpl";
	$output = template(TEMPLATES_PATH."header.tpl", $GLOBALS['subs']);
    $output .= template($p, $GLOBALS['subs']);
    $output .= template(TEMPLATES_PATH."footer.tpl", $GLOBALS['subs']);

	echo $output;
}
function addSub($subName, $sub)
{
	$GLOBALS['subs']["{".$subName."}"] = $sub;
}

function template($filepath, $subs)
{
	global $s;
	if(file_exists($filepath))
	{
		$text = file_get_contents($filepath);
	} else {
		print "File '$filepath' not found";
		return false;
	}
	
	// Handle case where $subs is not an array
	if (!is_array($subs)) {
		$subs = array();
	}
	
	foreach($subs as $sub => $repl)
	{
		$text = str_replace($sub, $repl, $text);
	}
	
	ob_start();
		eval("?>".$text);
		$text = ob_get_contents();
	ob_end_clean();
	return $text;
}

?>
