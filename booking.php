<?php 

foreach($_POST as $key=>$val)
		${$key}=htmlspecialchars($val);

if (isset($id_room)) {

	$date = new DateTime();
	$token = md5($date->getTimestamp().$id_room.$email);

	include("connexion.php");

	
	$req=$pdo->prepare("SELECT room_id FROM `bookings` WHERE (`start_date` <= ? and ? <= `end_date`) OR (`start_date` <= ? and ? <= `end_date`) OR (? <= `start_date` and `start_date` <= ?) OR (? <= `end_date` and `end_date` <= ?); ");
	$req->setFetchMode(PDO::FETCH_ASSOC);
	$req->execute(array($start,$start,$end,$end,$start,$end,$start,$end));
	$tab=$req->fetchAll();
	$error = 'no';
	for($i=0; $i<count($tab); $i++){
		if ($tab[$i]['room_id']==$id_room) {
			$error = "yes";
		}
	}
	
	if ($error == 'no') {
	    $req=$pdo->prepare("SELECT price FROM `rooms` WHERE room_id=? LIMIT 1; ");
    	$req->setFetchMode(PDO::FETCH_ASSOC);
    	$req->execute(array($id_room));
    	$tab=$req->fetchAll();
    	$price = $tab[0]["price"];
		
		$start_date = new DateTime($start);
        $end_date = new DateTime($end);
		$duree = $end_date->getTimestamp() - $start_date->getTimestamp();
		$total_price = floor(($duree / 86400) + 1) * $tab[0]["price"];
		
		$ins=$pdo->prepare("INSERT into bookings(room_id,start_date,end_date,first_name,last_name,email,address,city,country,phone,price,unique_token) values(?,?,?,?,?,?,?,?,?,?,?,?)");
		$ins->execute(array($id_room,$start,$end,$first_name,$last_name,$email,$address,$city,$country,$phone,$total_price,$token));
		$msg = "OK";
		
	} else {
		$msg = "Sorry, the room is not available during the requested time period.";
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
	<title>Booking Page</title>

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
	    <img src="logo.png" height="70" class="d-inline-block align-top" alt="">
	  </a>
	</nav>

	<h2> </h2>
	<br>

	<?php
		if (isset($id_room) && $msg == "OK") {

			// SEND MAIL TO CLIENT

			$client = $email;
	        $subject = "New Registration";
	         
	        $message = '<img src="logo.png" height="70" style="display: block; margin: auto;" alt="Logo">';
	        $message .= '<h3>Your booking is registered!</h3>';
	        $message .= '<h4>Details:</h4>';
	        $message .= '<h5>Room name: '.$room_name.'</h5>';
	        $message .= '<h5>Check in/out: '.$start.' to '.$end.'</h5>';
	        $message .= '<h5>Total price: '.$total_price.' DH</h5>';
	        $message .= '<h5>Phone number: '.$phone.'</h5>';
	        $message .= "<p>If you wish to cancel your booking, follow this link:</p>";
	        $message .= '<a href="annulation.php?id='.$token.'">Cancel your booking</a>';
	         
	        $header = "From: reservation@booking.com\r\n";
	        $header .= "MIME-Version: 1.0\r\n";
	        $header .= "Content-type: text/html\r\n";
	         
	        $retval = mail ($client,$subject,$message,$header);

	        if( $retval == true ) {
	            echo "<h3>The confirmation message is sent to your mailbox...</h3>";
	        }else {
	        	echo "<h3>The confirmation message cannot be sent!</h3>";
	        }

	        // SEND MAIL TO ADMIN

	        $admin = "booking@gmail.com";

	        $message_to_admin = '<img src="logo.png" height="70" style="display: block; margin: auto;" alt="Logo"><h3>Hello Admin, a new booking has been registered!</h3><h4>Details:</h4><h5>Room name : '.$room_name.'</h5><h5>Check in/out: '.$start.' to '.$end.'</h5><h5>Total price: '.$total_price.' DH</h5><h5>Phone number: '.$phone.'</h5><h5>Email: <a href="mailto:'.$email.'">'.$email.'</a></h5>';

	        $header_to_admin = "From: " . $email . "\r\n";
	        $header_to_admin .= "Cc: booking@gmail.com\r\n";
	        $header_to_admin .= "MIME-Version: 1.0\r\n";
	        $header_to_admin .= "Content-type: text/html\r\n";

	        $send = mail ($admin,$subject,$message_to_admin,$header_to_admin);
	?>

	<div class="container" style="margin-top: 200px;text-align: center;">
		<h4>Your booking is registered!</h4>
		<p>If you wish to cancel your booking, check the message sent to your mailbox.</p>
		<a href="search.php">Back to booking page</a>
	</div>
	

	<?php
		} else {
	?>

	<div class="container" style="margin-top: 200px;text-align: center;">
		<h4><?php echo $msg ?></h4>
		<a href="search.php">Back to booking page</a>
	</div>

	<?php
		}
	?>

</body>
</html>