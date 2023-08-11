<?php
	session_start();
	if(@$_SESSION["admin"]=="oui"){
		header("location:index.php");
		exit();
	}
	if (@$_SESSION["autoriser"]=="oui") {
		session_destroy();
		header("location:index.php");
		exit();
	}
	foreach($_POST as $key=>$val)
		${$key}=htmlspecialchars($val);
	$message="";
	if(isset($valider)){
		include("../connexion.php");
		$req=$pdo->prepare("select * from admins where login=? and pass=? limit 1");
		$req->setFetchMode(PDO::FETCH_ASSOC);
		$req->execute(array($login,md5($pass)));
		$tab=$req->fetchAll();
		if(count($tab)==0)
			$message="<li>Wrong login or password!</li>";
		else{
			$_SESSION["admin"]="oui";
			$_SESSION["login"]=$login;
			header("location:index.php");
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<?php 
			$titre = "Sign In";
			include("head.php"); 
		?>
	</head>
	<body>
		<?php include("header.php"); ?>
		<section class="ftco-section">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-md-7 col-lg-5">
						<div class="login-wrap p-4 p-md-5">
							<div class="icon d-flex align-items-center justify-content-center">
								<span class="fa fa-user"></span>
							</div>
							<h3 class="text-center mb-4">Sign In</h3>
							<form name="fo" id="login" action="#" method="post" class="login-form">
								<div class="form-group">
									<input type="text" class="form-control rounded-left" placeholder="Username" name="login" required="">
								</div>
								<div class="form-group d-flex">
									<input type="password" class="form-control rounded-left" placeholder="Password" name="pass" required="">
								</div>
								<div class="form-group">
									<button name="valider" type="submit" class="form-control btn btn-primary rounded submit px-3">Login</button>
								</div>
								<div class="message">
									<?php echo $message ?>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>
	</body>
</html>