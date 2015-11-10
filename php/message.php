<?php
function message($content,$redirect,$type)
{
	if ($redirect=="") {
		$redirect="javascript.history.go(-1)";
	}
	include './message.html';
}
?>