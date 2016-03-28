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
</head>
<body>
	<header>
	</header>
	
	<section>
		<a href="enregistrementVideo.php?id=0">
			<div id="gauche">
				<img src="images/smiley01.svg" alt="bon souvenir">
				<p>Enregistrer</p>
			</div>
		</a>
		<a href="enregistrementVideo.php?id=1">
			<div id="droite">
				<img src="images/smiley02.svg" alt="mauvais souvenir">
				<p>Enregistrer</p>
			</div>
		</a>
		<a href="reglageCam.php">
			<div id="retour">
				<img src="images/retour.svg" alt="changer le nom et prénom">
			</div>
		</a>
		<div id="logo">
			<img src="images/logo.svg" alt="logo">
		</div>
	</section>
	
</body>