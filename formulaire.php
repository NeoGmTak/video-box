<?php require('connexion.php'); ?>
<!DOCTYPE html>
<html>
<html lang="fr">
<head>
	<?php include 'head.php'; ?>
	<link rel="stylesheet" href="css/style.css">
	<title>Vidéo box</title>
</head>
<body>

	<section class="accueil">
		<div class="formulaire">
			<form action="insertData.php" method="POST">
				<input type="text" placeholder="Nom :" name="nom" /><br />
				<input type="text" placeholder="Prénom :" name="prenom" /><br />
				<input type="submit" value="Valider" /><br />
			</form>
		</div>
	</section>

	</body>
</html>