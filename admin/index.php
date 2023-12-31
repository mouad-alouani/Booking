<?php
	session_start();

	if(@$_SESSION["admin"]!="oui"){
		header("location:login.php");
		exit();
	}

	foreach($_POST as $key=>$val)
		${$key}=htmlspecialchars($val);
	
	include("../connexion.php");

	// COUNT ROOMS
	$req_rooms = $pdo->prepare("SELECT COUNT(*) AS num_rooms FROM rooms");
	$req_rooms->execute();
	$result = $req_rooms->fetch(PDO::FETCH_ASSOC);
	$count_rooms = $result['num_rooms'];

	// COUNT BOOKINGS
	$req_bookings = $pdo->prepare("SELECT COUNT(*) AS num_bookings FROM bookings");
	$req_bookings->execute();
	$result = $req_bookings->fetch(PDO::FETCH_ASSOC);
	$count_bookings = $result['num_bookings'];

	// COUNT NEW BOOKINGS
	$req_new_bookings = $pdo->prepare("SELECT COUNT(*) AS num_bookings FROM bookings WHERE start_date > CURDATE()");
	$req_new_bookings->execute();
	$result = $req_new_bookings->fetch(PDO::FETCH_ASSOC);
	$new_bookings = $result['num_bookings'];

	// COUNTER OF VISITORS
	$countFile = '../visitor_count.txt';
    $count_visitors = intval(file_get_contents($countFile));

    // CONVERSION RATE
    $conversion_rate = $count_bookings/$count_visitors*100;
?>
<!DOCTYPE html>
<html>
<head>
	<?php 
		$titre = "ADMIN";
		include("head.php"); 
	?>
</head>
<body>
	<div class="main">
		<!-- Sidebar -->
		<?php 
				include("sidebar.php");	
			?>
		<div class="page-wrapper" id="page-wrapper">
			<?php 
				include("header.php");	
			?>

			<!-- DASHBOARD SECTION -->
			<section id="dashboard" class="container-fluid my-4">
				<h2>Dashboard</h2>

				<div class="row">
					<div class="col-lg-3 col-6">
						<div class="small-box bg-info">
							<div class="inner">
								<h3><?php echo $new_bookings; ?></h3>
								<p>New Bookings</p>
							</div>
							<div class="icon">
								<i class="fa fa-shopping-bag"></i>
							</div>
							<a href="bookings.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
						</div>
					</div>

					<div class="col-lg-3 col-6">
						<div class="small-box bg-success">
							<div class="inner">
								<h3><?php echo number_format($conversion_rate,2); ?><sup style="font-size: 20px">%</sup></h3>
								<p>Conversion Rate</p>
							</div>
							<div class="icon">
								<i class="fa fa-pie-chart"></i>
							</div>
							<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
						</div>
					</div>

					<div class="col-lg-3 col-6">
						<div class="small-box bg-warning">
							<div class="inner">
								<h3><?php echo $count_rooms; ?></h3>
								<p>Rooms</p>
							</div>
							<div class="icon">
								<i class="fa fa-bed"></i>
							</div>
							<a href="rooms.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
						</div>
					</div>

					<div class="col-lg-3 col-6">
						<div class="small-box bg-danger">
							<div class="inner">
								<h3><?php echo $count_visitors; ?></h3>
								<p>Website Visitors</p>
							</div>
							<div class="icon">
								<i class="fa fa-eye"></i>
							</div>
							<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
						</div>
					</div>
				</div>

			</section>

		</div>
	</div>
</body>
</html>