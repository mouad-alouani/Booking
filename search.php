<?php

foreach($_POST as $key=>$val)

		${$key}=htmlspecialchars($val);

// Visitors Counter
	if (!isset($_COOKIE['visitor'])) {

		// Update count file
		$countFile = 'visitor_count.txt';
    	$count = intval(file_get_contents($countFile));
    	$count++; // Increment the count
    	file_put_contents($countFile, $count);

    	// Set the cookie to expire in 30 days
    	setcookie('visitor', 1, time() + (86400 * 30), '/');
	}

if (isset($room)) {

	include("connexion.php");

	$req=$pdo->prepare("SELECT * FROM rooms WHERE room_id = ?;");

	$req->setFetchMode(PDO::FETCH_ASSOC);

	$req->execute(array($room));

	$tab=$req->fetchAll();

}
elseif (isset($start_date)) {

	include("connexion.php");

	$req=$pdo->prepare("SELECT * FROM rooms WHERE room_id NOT IN (SELECT room_id FROM `bookings` WHERE (`start_date` <= ? and ? <= `end_date`) OR (`start_date` <= ? and ? <= `end_date`) OR (? <= `start_date` and `start_date` <= ?) OR (? <= `end_date` and `end_date` <= ?));");

	$req->setFetchMode(PDO::FETCH_ASSOC);

	$req->execute(array($start_date, $start_date, $end_date, $end_date, $start_date, $end_date, $start_date, $end_date));

	$tab=$req->fetchAll();

}

if (isset($start_date)) {
    
}

?>

<!DOCTYPE html>

<html>

<head>

	<title>Search for Room</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" />
	<link href="https://colorlib.com/etc/regform/colorlib-regform-5/vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all" />
	<link rel="stylesheet" type="text/css" href="style.css" />

</head>

<body>

	<nav class="navbar navbar-light bg-light justify-content-center">
	  <a class="navbar-brand justify-content-center" href="/">
	    <img src="https://lesourcesdelatlas.com/wp-content/uploads/2023/03/logo.png" height="70" class="d-inline-block align-top" alt="">
	  </a>
	</nav>

	<?php 
	if (!isset($room)) {
	?>

	<div id="booking" class="section">
		<div class="section-center">
			<div class="container">
				<div class="row" style="margin-right: 0;margin-left: 0;">
					<div class="booking-form p-0" style="padding: 5px 15px;">
						<form class="p-3" id="formulaire" action="search.php" method="POST">
							<div class="row no-margin">
								<div class="col-md-8">
									<div class="row no-margin">
										<div class="col-md-6">
											<div class="form-group" style="margin-bottom: 8px !important">
												<span class="form-label">Check In</span>
												<input class="form-control" type="date" id="start_date" name="start_date" required>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group" style="margin-bottom: 8px !important;">
												<span class="form-label">Check out</span>
												<input class="form-control" type="date" id="end_date" name="end_date" required>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-btn">
										<button class="submit-btn" type="submit">Check availability</button>
									</div>
								</div>
							</div>
							<p id="error_msg" style="color: red; display: none;"></p>
							<script type="text/javascript">
								function validateDates(checkIn, checkOut) {
								  const now = new Date(); // get current date
								  const checkInDate = new Date(checkIn);
								  const checkOutDate = new Date(checkOut);

								  // Check if check-in date is not in the past
								  if (checkInDate < now) {
								    return "Check-in date must be a new date";
								  }

								  // Check if check-out date is greater than check-in date
								  if (checkOutDate < checkInDate) {
								    return "Check-out date must be greater than check-in date";
								  }

								  return true; // Dates are valid
								}

								const form = document.querySelector('#formulaire');
								form.addEventListener('submit', function(event) {
								  event.preventDefault(); // Prevent the form from submitting by default

								  const checkInInput = document.querySelector('#start_date');
								  const checkOutInput = document.querySelector('#end_date');
								  const checkIn = checkInInput.value;
								  const checkOut = checkOutInput.value;
								  const validationResult = validateDates(checkIn, checkOut);

								  if (validationResult === true) {
								    console.log("Dates are valid, submitting form...");
								    form.submit(); // Submit the form
								  } else {
								    console.error(validationResult);
								    alert(validationResult); // Show the error message
								  }
								});
							</script>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php 
	}
	?>

	<div class="mt-5 mb-5 col-sm-12" style="margin-top: 10em !important; position: absolute;">
    	<div class="d-flex justify-content-center row">
        	<div class="col-md-10">

    <?php
	if (isset($start_date)) {
	?>

				<h4 style="text-align: center;">Available Rooms between <?php echo $start_date ?> and <?php echo $end_date ?></h4>

	<?php
		for($i=0;$i<count($tab);$i++){
	?>

				<div class="row p-2 bg-white border rounded" style="margin-top: 20px;">
	                <div class="col-md-3 mt-1"><img class="img-fluid img-responsive rounded product-image" src="<?php echo $tab[$i]["image"] ?>"></div>
	                <div class="col-md-6 mt-1">
	                    <h3><?php echo $tab[$i]["title"] ?></h3>
	                    <div class="d-flex flex-row" style="display: none !important;">
	                        <div class="ratings mr-2"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div><span>
	                        	<?php
								  $min=100;
								  $max=500;
								  echo rand($min,$max);
								?>
	                        </span>
	                    </div>
	                    <div class="mt-1 mb-1 spec-1"><span><?php echo $tab[$i]["price"] ?> DH / DAY</span></div>
	                    <div class="mt-1 mb-1 spec-2">
	                    	<div><span>Wifi</span></div>
	                    	<div><span>Climatisation</span></div>
	                    	<div><span>Coffre fort</span></div>
	                    	<div><span>Douche</span></div>
	                    	<div><span>Produits d'acceuil</span></div>
	                    	<div><span>Sèche-cheveux</span></div>
	                	</div></div>
	                <div class="align-items-center align-content-center col-md-3 border-left mt-1">
	                    <div class="d-flex flex-row align-items-center">
	                        <h6 class="mr-1">Total price: </h6>
	                    </div>
	                    <h5 class="text-success">
	                        	<?php
	                        		$start = new DateTime($start_date);
									$end = new DateTime($end_date);
									$duree = $end->getTimestamp() - $start->getTimestamp();
									$total = floor(($duree / 86400) + 1) * $tab[$i]["price"];
									echo $total." DH";
	                        	?>
	                    </h5>
	                    <form action="search.php" method="POST">
							<input type="hidden" name="date_start" value="<?php echo $start_date ?>">
							<input type="hidden" name="date_end" value="<?php echo $end_date ?>">
							<input type="hidden" name="price" value="<?php echo $total ?>">
							<input type="hidden" name="room" value="<?php echo $tab[$i]["room_id"]?>">
							<input type="hidden" name="room_name" value="<?php echo $tab[$i]["title"]?>">
	                    	<button class="submit-btn" type="submit" name="choose_room">Book</button>
	                    </form>
	                </div>
	            </div>

	<?php

		}
	}

	?>

</div>
</div>
</div>

	<?php

	if (isset($room,$price,$date_start,$date_end)) {

		for($i=0;$i<count($tab);$i++){

	?>

	<div class="container" style="margin-top: 120px;">
    <div class="card">
        <div class="card-body">
            <h3 class="card-title"><?php echo $tab[$i]["title"] ?></h3>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-6">
                    <div class="white-box text-center"><img src="<?php echo $tab[$i]["image"] ?>" class="img-responsive" style="width: 100%;"></div>
                </div>
                <div class="col-lg-7 col-md-7 col-sm-6">
                    <h4 class="box-title mt-5">Description:</h4>
                    <p><?php echo $tab[$i]["description"] ?></p>
                    <h2 class="mt-5">
                        <?php echo $price ?> DH <small class="text-success">(<?php echo $tab[$i]["price"] ?> DH/Day)</small>
                    </h2>
                </div>
            </div>
        </div>
        	<div class="form-body">
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items">
                        <h3>Booking Registration Form</h3>
                        <p>Fill in the data below.</p>
                        <form class="requires-validation" method="POST" id="Formulaire" name="book" action="booking.php" novalidate>

                        	<input type="hidden" id="id_field" name="id_room" value="<?php echo $room ?>" />

                            <input type="hidden" name="start" value="<?php echo $date_start ?>">

                            <input type="hidden" name="end" value="<?php echo $date_end ?>">

                            <input type="hidden" name="total_price" value="<?php echo $price ?>">

                            <input type="hidden" name="room_name" value="<?php echo $room_name ?>">

                            <div class="col-md-12">
                               <input class="form-control" type="text" name="first_name" placeholder="First Name" required>
                               
                               <div class="invalid-feedback">Username field cannot be blank!</div>
                            </div>

                            <div class="col-md-12">
                               <input class="form-control" type="text" name="last_name" placeholder="Last Name" required>
                               
                               <div class="invalid-feedback">Username field cannot be blank!</div>
                            </div>

                            <div class="col-md-12">
                                <input class="form-control" type="email" name="email" placeholder="E-mail Address" required>
                                 
                                 <div class="invalid-feedback">Email field cannot be blank!</div>
                            </div>

                            <div class="col-md-12">
                                <input class="form-control" type="phone" name="phone" placeholder="Phone Number" required>
                                 
                                 <div class="invalid-feedback">Phone field cannot be blank!</div>
                            </div>

                            <div class="col-md-12">
                               <input class="form-control" type="text" name="address" placeholder="Address" required>
                               
                               <div class="invalid-feedback">Address field cannot be blank!</div>
                            </div>

                            <div class="col-md-12">
                               <input class="form-control" type="text" name="city" placeholder="City" required>
                               
                               <div class="invalid-feedback">City field cannot be blank!</div>
                            </div>

                            <div class="col-md-12">
                                <select class="form-select mt-3" required name="country">
                                        <option selected disabled value="">Country</option>
                                        <?php 
                                        	// Open the file for reading
											$file = fopen("countries.txt", "r");

											// Create an empty string to hold the HTML code for the drop-down menu
											$options = "";

											// Loop through each line in the file
											while (!feof($file)) {
											  // Read the line and remove any extra whitespace
											  $line = trim(fgets($file));
											  
											  // Add an HTML option element for the country name
											  if (!empty($line)) {
											    $options .= "<option value=\"$line\">$line</option>";
											  }
											}

											// Close the file
											fclose($file);

											// Print the HTML code for the drop-down menu
											echo $options;

                                        ?>
                               </select>
                                
                                <div class="invalid-feedback">Please select a country!</div>
                            </div>                

                            <div class="form-button mt-3">
                                <button id="submit" type="submit" name="book" class="btn btn-primary">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>
    </div>

    <?php
		}
	?>

</div>

    <script type="text/javascript">
    	(function () {
		'use strict'
		const forms = document.querySelectorAll('.requires-validation')
		Array.from(forms)
		  .forEach(function (form) {
		    form.addEventListener('submit', function (event) {
		      if (!form.checkValidity()) {
		        event.preventDefault()
		        event.stopPropagation()
		      }

		      form.classList.add('was-validated')
		    }, false)
		  })
		})()
    </script>

    <?php
		}
	?>

</body>

</html>