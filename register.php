<?php
session_start();

$DATABASE_HOST = 'localhost'; 
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';


$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
    
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if ( !isset($_POST['username'], $_POST['password'], $_POST['mail']) ) {
	// Could not get the data that should have been sent.
	exit('Please fill all the fields!');
}

if ($stmt = $con->prepare('SELECT id FROM accounts WHERE username = ? OR mail = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
	$stmt->bind_param('ss', $_POST['username'], $_POST['mail']);
	$stmt->execute();
	$stmt->store_result();

	if ($stmt->num_rows > 0) {
		echo 'Username or Email already exists!';
	} else {
		if ($password = password_hash($_POST['password'], PASSWORD_DEFAULT)) {
			// Insert new account into the database
			if ($stmt = $con->prepare('INSERT INTO accounts (username, password, mail) VALUES (?, ?, ?)')) {
				// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
				$stmt->bind_param('sss', $_POST['username'], $password, $_POST['mail']);
				$stmt->execute();
				echo 'Registration successful! You can now login.';
			} else {
				echo 'Registration failed: ' . $con->error;
			}
		}
	}

	$stmt->close();
} else {
	echo 'Registration failed: ' . $con->error;
}

$con->close();
?>