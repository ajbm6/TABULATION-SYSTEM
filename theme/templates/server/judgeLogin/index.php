<?php
	include('../../server/cors.php');
	include( __DIR__.'/controller.php');

	$method = $_SERVER['REQUEST_METHOD'];
	$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));

	switch ($method) {
	  case 'PUT':
	    break;
	  case 'POST':
	  	$data = [
				"username" => $_POST['username'],
				"password" => $_POST['password']
			];
	 	 JudgeLoginCtrl::login($data);
	    break;
	  case 'GET':
	  	break;
	  case 'DELETE':
	    break;
	}
	exit();

?>