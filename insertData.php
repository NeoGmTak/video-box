<?php 
	require 'connexion.php';
	if(isset($_POST['nom']) && isset($_POST['prenom'])){
		$insert = $bdd -> prepare('INSERT INTO utilisateur (nom, prenom) VALUES (:nom, :prenom)');
		$insert -> execute(array(
							'nom' => $_POST['nom'],
							'prenom' => $_POST['prenom']
						)); 
	}
	// Insérer le nom de la page php 
	header('Location: ');