<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// We don't have the password or email info stored in sessions, so instead, we can get the results from the database.
$stmt = $con->prepare('SELECT password, email FROM accounts WHERE id = ?');
// In this case we can use the account ID to get the account info.
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $email);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="home.css">
  <title>Profile</title>
</head>
<body>
  

<!--====== Header Section Start ======-->
<header>
  <nav class="navigation">

    <!-- Logo -->
    <div class="logo">
      <h1>Profile</h1>
    </div>
    
    <!-- Navigation -->
    <ul class="menu-list">
      <li><a href="index.html">Home</a></li>
      <li><a href="profile.php">Account</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>

    <div class="humbarger">
      <div class="bar"></div>
      <div class="bar2 bar"></div>
      <div class="bar"></div>
    </div>
  </nav>
  
  <!-- ==== Intro Section Start ==== -->
  <div class="intro-section" id="home">
    <div class="bg-img"></div>
    <div class="intro-content">
      <h1>Profile Page</h1>
	  <div class="content">
			<div><center>
				<p>Your account details are below:</p>
				<table>
					<tr>
						<td>Username:</td>
						<td><?=$_SESSION['name']?></td>
					</tr>
					<tr>
						<td>Password:(Encrypted)</td>
						<td><?=$password?></td>
					</tr>
					<tr>
						<td>Email:</td>
						<td><?=$email?></td>
					</tr>
				</table>
				</center>
			</div>
		</div>
    </div>
  </div>	
</header>

<script>
    $(document).ready(function(){

      //hamburger Toggle
  $('.humbarger').click(function(event){
  $('.menu-list').slideToggle(500);
  event.preventDefault();

  $('.menu-list li a').click(function(event) {
      if ($(window).width() < 768) {
        $('.menu-list').slideUp(500);
        event.preventDefault(); 
      }
    });
  });

  });
</script>
</body>
</html>
