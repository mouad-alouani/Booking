<?php 
	if (!isset($_SESSION["admin"])) {
?>
	<header class="navbar navbar-light bg-light justify-content-center" style="padding-right: 2%; padding-left: 2%;height: 100px;">
		<a class="navbar-brand justify-content-center" href="#">
			<img src="logo.png" height="70" class="d-inline-block align-top" alt="">
		</a>
	</header>
<?php 
	}

	if (@$_SESSION["admin"]=="oui") {
?>
	<header class="navbar navbar-light bg-light justify-content-between" style="padding-right: 2%; padding-left: 2%;height: 100px;">
		<div>
			<button onclick="showhide()" style="background: none;border: none;font-size: 18pt;outline: none;">
				<i class="fa fa-bars" aria-hidden="true"></i>
			</button>
		</div>
	
		<a class="navbar-brand justify-content-center" href="#">
			<img src="logo.png" height="70" class="d-inline-block align-top" alt="">
		</a>
	
		<div>
			<a href="deconnexion.php" style="color: #1F262D;font-size: 18pt;"><i class="fa fa-sign-out" title="Sign Out"></i></a>
		</div>
	</header>
<?php
	}
?>