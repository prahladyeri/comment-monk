<?php
/**
* auth.php
* 
* Authentication functions
* 
* @author Prahlad Yeri <prahladyeri@yahoo.com>
* @license MIT
*/

function get_user() {
	if (isset($_SESSION['user'])) {
		return $_SESSION['user'];
	} else {
		return null;
	}
}
