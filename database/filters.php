<?php
	function filterString($string) {
		$newString = trim($string);
		$newString = stripslashes($newString);
		$newString = strip_tags($newString);
		$newString = html_entity_decode($newString); //or maybe this one?
		$newString = htmlspecialchars_decode($newString); //maybe take this out?

		return $newString;
	}

	function validateInt($int) {
		if(!filter_var($int, FILTER_VALIDATE_INT) === false) {
			return $int;
		} else {
			if(filter_var($int, FILTER_VALIDATE_INT) === 0) {
				return $int;
			} else {
				//throw exception, I think
			}
		}
	}

	function validateEmail($email) {
		$newEmail = filter_var($email, FILTER_SANITIZE_EMAIL);

		if(!filter_var($newEmail, FILTER_VALIDATE_EMAIL) === false) {
			return $email;
		} else {
			//throw exception, I think
		}
	}
?>