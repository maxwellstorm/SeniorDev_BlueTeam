<?php
	//REMOVE THIS FOR FINAL COMMIT -THIS IS ONLY HERE FOR THE DEV ENVIRONEMNT
	$allowed = true;

	require_once("commonAuth.php");

	if(!$allowed) {
		header("Location: ../public/notAuthorized.html");
		die("Redirecting to notAuthorized.html");
	}

	function filterString($string) {
		$newString = trim($string);
		$newString = stripslashes($newString);
		$newString = strip_tags($newString);
		$newString = html_entity_decode($newString);
		$newString = htmlspecialchars_decode($newString);

		return $newString;
	}
?>