<?php
try{
	$conn = new PDO("mysql:host=localhost;dbname=applicant-records","root","");
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		echo "Connected successfully";
		}
		catch(PDOException $e){
			echo "Connection failed: " , $e->getMessage();
		}
