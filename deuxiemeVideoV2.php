<?php require('connexion.php'); ?>
<!DOCTYPE html>
<html>
<html lang="fr">
<head>
	<?php include 'head.php'; ?>
	<link rel="stylesheet" href="css/libs/reset.css">
	<link rel="stylesheet" href="css/general.css" title="apparence">
	<link rel="icon" type="image/png" href="images/favicon/favicon.png">
	<title>Vidéo Box !</title>
	<?php 
		$_SESSION['secondVideo'] = true;
	?>
</head>
<body>
	<header>
	</header>
	
	<section>
		<a href="pageFin.php">
			<div id="gauche">
				<div>
					<p>Terminer l'expérience</p>
				</div>
			</div>
		</a>
		<a href="enregistrementVideo.php">
			<div id="droite">
				<img src="images/smiley02.svg" alt="bon souvenir">
				<p>Enregistrer</p>
			</div>
		</a>
		<div id="logo">
			<img src="images/logo.svg" alt="logo">
		</div>
	</section>
	
</body>