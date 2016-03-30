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
		unset($_SESSION['id']);
		unset($_SESSION['secondVideo']);
	?>
</head>
<body>
	<header>
	</header>
	
	<section id="page">
		<div id="texte">
			<p>Merci d'avoir participé à l'expérience</p>
			<p>Bisous !</p>
		</div>
		<div id="logo">
			<img src="images/logo.svg" alt="logo">
		</div>
		<a href="index.php">
			<div id="retour">
				<img src="images/retour.svg" alt="changer le nom et prénom">
			</div>
		</a>
	</section>
	
</body>