<?php

	try{

		$pdo=new PDO("mysql:host=localhost;dbname=booking","username","password");
		$pdo->exec("set names utf8mb4");

	}

	catch(PDOException $e){

		echo $e->getMessage();

	}

?>
