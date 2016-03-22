<?php require('connexion.php'); ?>
<!DOCTYPE html>
<html>
<html lang="fr">
<head>
	<?php include 'head.php'; ?>
	<title>Vidéo box</title>
	<script language="JavaScript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
    <script language="JavaScript" src="//ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>
    <script language="JavaScript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/jwplayer.js"></script>
    <script language="JavaScript" src="js/scriptcam.js"></script>
    <link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>



</head>
<body>
	<div class="container-fluid">
		<div id="webcam">
		</div>


	<?php include 'footer.php'; ?>
	
	</div>
	<script language="JavaScript">
		$(document).ready(function() {
		    $("#webcam").scriptcam(
		        path: '../video/',
		        width: 640,
        		height: 480,
        		onWebcamReady: showHWA
		    );
		});
		function showHWA() {
    		alert($.scriptcam.hardwareacceleration());
		};

	</script>
</body>
</html>