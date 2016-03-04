<?php require('connexion.php'); ?>
<!DOCTYPE html>
<html>
<html lang="fr">
<head>
	<?php include 'head.php'; ?>
	<title>Vidéo box</title>
</head>
<body>
	<div class="container-fluid">

	<?php 
		// test de connexion à la bdd
		$req = $bdd -> query('SELECT * FROM utilisateur;'); 
		$req -> execute();
		while ($res = $req->fetch()) {
			echo $res['nom']." ".$res['prenom'];
		}
	?>
	<?php include 'footer.php'; ?>
	
	</div>
</body>
</html>