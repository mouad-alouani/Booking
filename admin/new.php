<?php
	session_start();

	if(@$_SESSION["admin"]!="oui"){
		header("location:login.php");
		exit();
	}

	foreach($_POST as $key=>$val)
		${$key}=htmlspecialchars($val);

	if (isset($submit)) {
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

	        // Insert the informations into the database
	        $sql = $pdo->prepare("INSERT INTO rooms (title, description, image, price) VALUES (?,?,?,?)");
	        $sql->setFetchMode(PDO::FETCH_ASSOC);
			$sql->execute(array($room_name, $description,"/appointments/images/".$filename,$price));
	        
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
		$titre = "ADMIN - New Room";
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
			<section id="new_room" style="margin: 20px;">
				<h2>New Room</h2>
				<form method="post" action="" enctype="multipart/form-data">
					<div class="row">
					    <!-- left column -->
					    <div class="col-md-3">
					        	<div class="text-center">
					        		<img src="" class="img-thumbnail" alt="image">
					        		<br>
					        		<h6>Upload a photo...</h6>
					        			<input type="file" name="file" class="form-control" />
					        	</div>
					    </div>
				      
				    	<!-- edit form column -->
				    	<div class="col-md-9">
				        	<div class="form-group">
				            	<label>Room name:</label>
				            	<div class="col">
				            		<input class="form-control" name="room_name" type="text" required value="">
				            	</div>
				        	</div>
				        	<div class="form-group">
				            	<label>Price in DH:</label>
				            	<div class="col">
				            		<input class="form-control" name="price" type="text" required value="">
				            	</div>
				        	</div>
				        	<div class="form-group">
				            	<label>Description:</label>
				            	<div class="col">
				            		<textarea class="form-control" name="description" rows="5"></textarea>
				            	</div>
				        	</div>
				        	<div class="row">
				          		<div class="cl d-flex justify-content-center">
				          			<button class="btn btn-primary" type="submit" name="submit">Save</button>
				          		</div>
				          	</div>
				    	</div>
				    </div>
				</form>
			</section>
		</div>
	</div>
</body>
</html>