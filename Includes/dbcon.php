<?php
	$host = "localhost";
	$user = "temp";
	$pass = "temp";
	$db = "attendancemsystem";
	
	$conn = new mysqli($host, $user, $pass, $db);
	if($conn->connect_error){
		echo "Seems like you have not configured the database. Failed To Connect to database:" . $conn->connect_error;
	}

	try {
        $conn_pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $conn_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo "Failed To Connect to database: " . $e->getMessage();
    }
?>