<?php 
	session_start();
	require "config.php";
	require "connectDB.php";

		//require để trong class StudentController có thể thấy dc 2file này
	require "model/Student.php";
	require "model/StudentRepository.php";

	require "model/Subject.php";
	require "model/SubjectRepository.php";

	require "model/Register.php";
	require "model/RegisterRepository.php";

	require 'vendor/autoload.php';
			$c = !empty($_GET["c"]) ? $_GET["c"] : "student";
		$a = !empty($_GET["a"]) ? $_GET["a"] : "list";
	$controller = ucfirst($c) . "Controller"; //StudentController
	require "controller/$controller".".php";
	$controller = new $controller();
	$controller->$a();
	?>