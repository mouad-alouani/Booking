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
		$titre = "ADMIN - Bookings";
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
			<section id="bookings" class="container-fluid my-4">
				<h2>Bookings</h2>
				<a href="deleted.php">Deleted Bookings <i class="fa fa-trash" aria-hidden="true"></i></a>
				<table class="tableau">
					<tr>
						<th>Room ID</th>
						<th>Check IN</th>
						<th>Check OUT</th>
						<th>Price</th>
						<th>Full Name</th>
						<th>Email</th>
						<th>Phone</th>
						<th>Action</th>
					</tr>
				<?php
					include("../connexion.php");
					$req=$pdo->prepare("select * from bookings order by start_date desc");
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
						<td>
							<a href="delete.php?id=<?php echo $tab[$i]["booking_id"] ?>"><i class="fa fa-trash"></i></a>
						</td>
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