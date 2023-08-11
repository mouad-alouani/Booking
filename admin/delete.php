<?php
	session_start();

	if(@$_SESSION["admin"]!="oui"){
		header("location:login.php");
		exit();
	}

	foreach($_POST as $key=>$val)
		${$key}=htmlspecialchars($val);

	// Delete Booking
	if (isset($booking_a_supprimer)) {
		include("../connexion.php");
		$req=$pdo->prepare("delete from bookings where booking_id=? limit 1");
		$req->setFetchMode(PDO::FETCH_ASSOC);
		$req->execute(array($booking_a_supprimer));
		header("location:index.php");
		exit();
	}
	elseif (!isset($_GET['id'])) {
		header("location:index.php");
		exit();
	}
	else {
		$id_a_supprimer = htmlspecialchars($_GET['id']);
	}
?>

<!DOCTYPE html>
<html>
<head>
	<?php 
		$titre = "ADMIN - Delete";
		include("head.php"); 
	?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
	<script>
		function showhide(){
			var x = document.getElementById("sidebar");
			var y = document.getElementById("page-wrapper");
			if (x.style.display === "none") {
				document.querySelectorAll('.tohide').forEach(elm => elm.style.display="inline");
				x.style.display = "inline";
				x.style.width = "190px";
				y.style.marginLeft = "190px";
				x.style.transition= "all 0.5s ease 0s";
			}
			else {
				document.querySelectorAll('.tohide').forEach(elm => elm.style.display="none");
				x.style.width = "auto";
				y.style.marginLeft = "0px";
				y.style.transition= "all 0.5s ease 0s";
				x.style.transition= "all 0.5s ease 0s";	
			}
		}
	</script>
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
			<section id="supprimer" style="margin: 20px;">
				<h3>You are about to delete the following booking:</h3>
				<table class="tableau">
					<tr>
						<th>#</th>
						<th>Value</th>
					</tr>
				<?php
					include("../connexion.php");
					$req=$pdo->prepare("select * from bookings where booking_id = $id_a_supprimer");
					$req->setFetchMode(PDO::FETCH_ASSOC);
					$req->execute();
					$tab=$req->fetchAll();
					for($i=0;$i<count($tab);$i++){
				?>

					<tr>
						<td>Room ID</td>
						<td><?php echo $tab[$i]["room_id"] ?></td>
					</tr>
					<tr>
						<td>Check IN</td>
						<td><?php echo $tab[$i]["start_date"] ?></td>
					</tr>
					<tr>
						<td>Check OUT</td>
						<td><?php echo $tab[$i]["end_date"] ?></td>
					</tr>
					<tr>
						<td>Price</td>
						<td><?php echo $tab[$i]["price"] . " DH" ?></td>
					</tr>
					<tr>
						<td>Full Name</td>
						<td><?php echo $tab[$i]["first_name"] . " " . $tab[$i]["last_name"] ?></td>
					</tr>
					<tr>
						<td>Email</td>
						<td><?php echo $tab[$i]["email"] ?></td>
					</tr>
					<tr>
						<td>Phone Number</td>
						<td><?php echo $tab[$i]["phone"] ?></td>
					</tr>

				<?php 		
					}
				?>
				</table>
				<br>
				<h4 style="text-align: center;">Are you sure you want to delete this booking?</h4>
				<br>
				<form action="supprimer.php" method="POST" style="text-align: center;">
					<input type="hidden" name="booking_a_supprimer" value="<?php echo $id_a_supprimer ?>">
					<input class="btn btn-primary" type="submit" name="valider" value="Supprimer" />
				</form>
			</section>
		</div>
	</div>
</body>
</html>