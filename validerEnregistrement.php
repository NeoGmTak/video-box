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
		if(isset($_GET['id'])) {
			$_SESSION['id'] = $_GET['id']; 
		}


	?>
</head>
<body>
	<header>
	</header>
	
	<section id="page">
		<div id="texte">
			<p>Valider l'enregistrement ?</p>
			<div id="choix">
				<a href="<?php if(isset($_SESSION['secondVideo'])){ echo "pageFin.php"; } else if($_SESSION['id'] == 0){ echo "deuxiemeVideoV2.php"; } else { echo "deuxiemeVideoV1.php"; } ?>"><img src="images/ok.svg" alt="ok"></a>
				<a href="enregistrementVideo.php"><img src="images/non.svg" alt="non"></a>
			</div>
		</div>
		<div id="logo">
			<img src="images/logo.svg" alt="logo">
		</div>
	</section>
	
</body>