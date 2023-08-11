<?php
	session_start();

	if(@$_SESSION["admin"]!="oui"){
		header("location:login.php");
		exit();
	}

	foreach($_POST as $key=>$val)
		${$key}=htmlspecialchars($val);

	$login = $_SESSION["login"];

	if (isset($update)) {
		include("../connexion.php");
		$req=$pdo->prepare("select * from admins where login=? and pass=? limit 1");
		$req->setFetchMode(PDO::FETCH_ASSOC);
		$req->execute(array($login,md5($old_password)));
		$tab=$req->fetchAll();
		if(count($tab)==0) {
			$message = "Old password is incorrect!";
			$alert = "alert-warning";
		}
		else {
			$upd=$pdo->prepare("UPDATE admins SET pass=? WHERE login=?");
			$upd->setFetchMode(PDO::FETCH_ASSOC);
			$upd->execute(array(md5($new_password),$login));
			$message="Password Updated Successfully";
			$alert = "alert-success";
		}
	}
	
?>

<!DOCTYPE html>
<html>
<head>
	<?php 
		$titre = "ADMIN - Settings";
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
			<section id="settings" style="margin: 20px;">
				<h2>Settings</h2>
				<div class="row">
					<h5>Change Password</h5>
				    <div class="col">
				    	<form method="post" action="">
				        	<div class="form-group">
				            	<label>Old Password:</label>
				            	<div class="col-sm-4">
				            		<input class="form-control" name="old_password" type="password" required value="">
				            	</div>
				        	</div>
				        	<div class="form-group">
				            	<label>New Password:</label>
				            	<div class="col-sm-4">
				            		<input class="form-control" name="new_password" type="password" required value="">
				            	</div>
				        	</div>
				        	<div class="col-sm-4">
				          		<div class="cl d-flex justify-content-left">
				          			<button class="btn btn-primary" type="submit" name="update">Save Changes</button>
				          		</div>
				          	</div>
				          	<br>
				          	<?php 
				          		if (isset($alert) AND isset($message)) {
				          	?>
				          	<p class="alert <?php echo $alert ?>"><?php echo $message ?></p>
				          	<?php 
				          		}
				          	?>
				        </form>
				    </div>
				</div>
			</section>
		</div>
	</div>
</body>
</html>