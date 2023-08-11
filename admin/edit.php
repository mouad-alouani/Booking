<?php
	session_start();

	if(@$_SESSION["admin"]!="oui"){
		header("location:login.php");
		exit();
	}

	foreach($_POST as $key=>$val)
		${$key}=htmlspecialchars($val);

	$id_room = htmlspecialchars($_GET['id']);

	if (isset($update)) {
		include("../connexion.php");
		$req=$pdo->prepare("UPDATE rooms SET title=?, description=?, price=? WHERE room_id=?");
		$req->setFetchMode(PDO::FETCH_ASSOC);
		$req->execute(array($room_name,$description,$price,$id));
	}
	elseif (isset($upload)) {
		$current_timestamp = time();
		// Get the file name and temp name
	    $filename = $current_timestamp."_".$_FILES['file']['name'];
	    $tempname = $_FILES['file']['tmp_name'];
	    
	    // Define folder to save uploaded files
	    $folder = "../images/";

	    // Move the uploaded file to the defined folder
	    if(move_uploaded_file($tempname, $folder.$filename)) {
	        // Connect to the database
	        include("../connexion.php");

	        // Insert the filename into the database
	        $sql = $pdo->prepare("UPDATE rooms SET image=? WHERE room_id=?");
	        $sql->setFetchMode(PDO::FETCH_ASSOC);
			$sql->execute(array("/appointments/images/".$filename,$id));
	        
	        $msg = "File uploaded successfully.";
	    } else {
	        $msg = "File upload failed.";
	    }
	}
?>

<!DOCTYPE html>
<html>
<head>
	<?php 
		$titre = "ADMIN - Edit Room";
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
			<section id="edit" style="margin: 20px;">
				<h2>Edit Room</h2>
				<?php
					include("../connexion.php");
					$req=$pdo->prepare("select * from rooms where room_id = $id_room");
					$req->setFetchMode(PDO::FETCH_ASSOC);
					$req->execute();
					$tab=$req->fetchAll();
					for($i=0;$i<count($tab);$i++){
				?>

				<div class="row">
				    <!-- left column -->
				    <div class="col-md-3">
				        	<div class="text-center">
				        		<img src="<?php echo $tab[$i]["image"] ?>" class="img-thumbnail" alt="image">
				        		<br>
				        		<h6>Upload a different photo...</h6>
				        		<form method="post" action="" enctype="multipart/form-data">
				        			<input type="hidden" name="id" value="<?php echo $id_room ?>">
				        			<input type="file" name="file" class="form-control" />
				        			<br>
				        			<input class="btn btn-primary" type="submit" name="upload" value="Upload" />
				        		</form>
				        		<?php 
				        		if (isset($msg)) {
				        		?>
						        <p><?php echo $msg ?></p>
						        <?php 
							    }
							    ?>
				        	</div>
				    </div>
				      
				    <!-- edit form column -->
				    <div class="col-md-9">
				    	<form method="post" action="">
				    		<input type="hidden" name="id" value="<?php echo $id_room ?>">
				        	<div class="form-group">
				            	<label>Room name:</label>
				            	<div class="col">
				            		<input class="form-control" name="room_name" type="text" required value="<?php echo $tab[$i]["title"] ?>">
				            	</div>
				        	</div>
				        	<div class="form-group">
				            	<label>Price in DH:</label>
				            	<div class="col">
				            		<input class="form-control" name="price" type="text" required value="<?php echo $tab[$i]["price"] ?>">
				            	</div>
				        	</div>
				        	<div class="form-group">
				            	<label>Description:</label>
				            	<div class="col">
				            		<textarea class="form-control" name="description" rows="5"><?php echo $tab[$i]["description"] ?></textarea>
				            	</div>
				        	</div>
				        	<div class="row">
				          		<div class="cl d-flex justify-content-center">
				          			<button class="btn btn-primary" type="submit" name="update">Save Changes</button>
				          		</div>
				          	</div>
				        </form>
				    </div>
				</div>

				<?php 		
					}
				?>
			</section>
		</div>
	</div>
</body>
</html>