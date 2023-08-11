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
		$titre = "ADMIN - Rooms";
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

			<!-- Rooms Section -->
			<section id="rooms" class="container-fluid my-4">
			    <h2>Rooms</h2>
			    <a href="new.php" class="align-right">New Room <i class="fa fa-plus"></i></a>
			    <table class="tableau">
					<tr>
						<th>ID</th>
						<th>Room Name</th>
						<th>Price</th>
						<th>Action</th>
					</tr>
				<?php 
			    	include("../connexion.php");
					$rq=$pdo->prepare("select * from rooms");
					$rq->setFetchMode(PDO::FETCH_ASSOC);
					$rq->execute();
					$tab=$rq->fetchAll();

					for($i=0;$i<count($tab);$i++){
				?>

					<tr>
						<td><?php echo $tab[$i]["room_id"] ?></td>
						<td><?php echo $tab[$i]["title"] ?></td>
						<td><?php echo $tab[$i]["price"] . " DH" ?></td>
						<td>
							<a href="edit.php?id=<?php echo $tab[$i]["room_id"] ?>"><i class="fa fa-edit"></i></a>
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