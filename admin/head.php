<title><?php echo $titre; ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" />

<script src="https://kit.fontawesome.com/8d3215b40f.js" crossorigin="anonymous"></script>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="css/style.css" />

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