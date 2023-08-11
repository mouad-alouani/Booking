<?php
	session_start();

	if(@$_SESSION["admin"]!="oui"){
		header("location:login.php");
		exit();
	}

?>

<!DOCTYPE html>
<html>
<head>
	<?php 
		$titre = "ADMIN - Deleted Bookings";
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

			<!-- BOOKINGS SECTION -->
			<section id="deleted_bookings" class="container-fluid my-4">
				<h2>Deleted Bookings</h2>
				<a href="bookings.php"><i class="fa fa-arrow-left" aria-hidden="true"></i> Bookings</a>
				<table class="tableau">
					<tr>
						<th>Room ID</th>
						<th>Check IN</th>
						<th>Check OUT</th>
						<th>Price</th>
						<th>Full Name</th>
						<th>Email</th>
						<th>Phone</th>
					</tr>
				<?php
					include("../connexion.php");
					$req=$pdo->prepare("select * from deleted_bookings order by start_date desc");
					$req->setFetchMode(PDO::FETCH_ASSOC);
					$req->execute();
					$tab=$req->fetchAll();
					for($i=0;$i<count($tab);$i++){
				?>
					<tr>
						<td><?php echo $tab[$i]["room_id"] ?></td>
						<td><?php echo $tab[$i]["start_date"] ?></td>
						<td><?php echo $tab[$i]["end_date"] ?></td>
						<td><?php echo $tab[$i]["price"] . " DH" ?></td>
						<td><?php echo $tab[$i]["first_name"] . " " . $tab[$i]["last_name"] ?></td>
						<td><?php echo $tab[$i]["email"] ?></td>
						<td><?php echo $tab[$i]["phone"] ?></td>
					</tr>
				<?php
						}
				?>
				</table>
			</section>
		</div>
	</div>
</body>
</html>