<?php

define('APP_DIR', __DIR__ . '/../test-app');

require_once __DIR__ . '/../Framework/bootstrap.php';

// override session handling for unit tests
session_set_save_handler(
	function ($savePath, $sessionName) {
		// open
		return true;
	},
	function () {
		// close
		return true;
	},
	function ($sessionId) {
		// read
		return true;
	},
	function ($sessionId, $sessionData) {
		// write
		return true;
	},
	function ($sessionId) {
		// destroy
		return true;
	},
	function ($lifetime) {
		// gc
		return true;
	}
);