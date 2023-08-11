<?php 

foreach($_POST as $key=>$val)
		${$key}=htmlspecialchars($val);

if (isset($_GET['id'])) {
	$id = htmlspecialchars($_GET['id']);
}

if (isset($id)) {
	include("connexion.php");

	$req=$pdo->prepare("SELECT * FROM `bookings` WHERE `unique_token` = ?;");
	$req->setFetchMode(PDO::FETCH_ASSOC);
	$req->execute(array($id));
	$tab=$req->fetchAll();
} elseif (isset($annuler, $token)) {
	include("connexion.php");

	$date = new DateTime();
	$date = $date->format('Y-m-d');

	if ($start > $date) {
		$del=$pdo->prepare("DELETE FROM bookings WHERE `unique_token` = ?;");
		$del->execute(array($token));

		if ($del == true) {
			$msg = 'OK';
		}
		else {
			$msg = "NO";
		}
	} else {
		$msg = "<h3>You cannot cancel this booking :(</h3>";
	}
	
	
} else {
	header('Location: /appointments/search.php');
  	exit();
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Cancel booking</title>

	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" />
	<link href="https://colorlib.com/etc/regform/colorlib-regform-5/vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all" />
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>

	<nav class="navbar navbar-light bg-light justify-content-center fixed-top">
	  <a class="navbar-brand justify-content-center" href="/">
	    <img src="logo.png" height="70" class="d-inline-block align-top" alt="Logo">
	  </a>
	</nav>

	<div class="container" style="margin-top: 200px;text-align: center;">

	<?php

	if (isset($id)) {

		for($i=0;$i<count($tab);$i++){	

	?>

		<h3>Booking Details:</h3>

		<div class="reservation">

			<p>Check-In: <?php echo $tab[$i]["start_date"] ?></p>
			<p>Check-Out: <?php echo $tab[$i]["end_date"] ?></p>
			<p>Full Name: <?php echo $tab[$i]["first_name"]." ".$tab[$i]["last_name"] ?></p>
			<p>Email: <?php echo $tab[$i]["email"] ?></p>
			<p>Price: <?php echo $tab[$i]["price"]?> DH</p>

			<h3>Are you sure you want to cancel your booking?</h3>

			<form action="annulation.php" method="POST">
				<input type="hidden" name="start" value="<?php echo $tab[$i]["start_date"] ?>">
				<input type="hidden" name="end" value="<?php echo $tab[$i]["end_date"] ?>">
				<input type="hidden" name="room_name" value="<?php echo $tab[$i]["title"] ?>">
				<input type="hidden" name="total_price" value="<?php echo $tab[$i]["price"] ?>">
				<input type="hidden" name="email" value="<?php echo $tab[$i]["email"] ?>">
				<input type="hidden" name="token" value="<?php echo $id ?>">
				<input type="submit" name="annuler" value="Cancel">
			</form>

		</div>

	<?php

		}
	}

	if ($msg == "OK") {

		$admin = 'admin@booking.com'; //Admin Email Here
	        $subject = "Cancelled Registration";
	         
	        $message = '<img src="https://booking.com/logo.png" height="70" style="display: block; margin: auto;" alt="Logo">';
	        $message .= '<h3>A booking has been canceled!</h3>';
	        $message .= '<h4>Details:</h4>';
	        $message .= '<h5>Room name: '.$room_name.'</h5>';
	        $message .= '<h5>Check in/out: '.$start.' to '.$end.'</h5>';
	        $message .= '<h5>Total price: '.$total_price.' DH</h5>';
	        $message .= '<h5>Email: '.$email.'</h5>';
	        	         
	        $header = "From: reservation@booking.com\r\n";
	        $header .= "Cc: booking@gmail.com\r\n";
	        $header .= "MIME-Version: 1.0\r\n";
	        $header .= "Content-type: text/html\r\n";
	         
	        $retval = mail ($admin,$subject,$message,$header);

	?>

		<h3>Your booking has been successfully canceled!</h3>
		<a href="search.php">Back to booking page</a>

	<?php
	}
	elseif ($msg == "NO") {
	 	echo "<h3>Your booking has not been canceled :(</h3>";
		echo "<p>Try again later.</p>";
	}
	else {
		echo $msg;
	}
	?>

	</div>

</body>
</html>
