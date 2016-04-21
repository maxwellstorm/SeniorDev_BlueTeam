<?php
	function filterString($string) {
		$newString = trim($string);
		$newString = stripslashes($newString);
		$newString = strip_tags($newString);
		$newString = html_entity_decode($newString); //or maybe this one?
		$newString = htmlspecialchars_decode($newString); //maybe take this out?
		$newString = filter_var($str, FILTER_SANITIZE_STRING);

		return $newString;
	}

	function validateInt($int) {
		if(!filter_var($int, FILTER_VALIDATE_INT) === false) {
			//valid, non-zero int
		} else {
			if(filter_var($int, FILTER_VALIDATE_INT) === 0) {
				//valid zero
			} else {
				//invalid int
			}
		}
	}

	function validateEmail($email) {
		$newEmail = filter_var($email, FILTER_SANITIZE_EMAIL);

		if(!filter_var($newEmail, FILTER_VALIDATE_EMAIL) === false) {
			//valid email
		} else {
			//invalid email
		}
	}
?>