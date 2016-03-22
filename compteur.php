<?php require('connexion.php'); ?>
<!DOCTYPE html>
<html>
<html lang="fr">
<head>
	<?php include 'head.php'; ?>
	<link rel="stylesheet" href="css/style.css">
	<title>Vidéo box</title>
			<script>
/* -- -- -- Début -- -- -- */
var cpt = 3 ;
var x ;
 
function decompte()
{
    if(cpt>=0)
    {
        if(cpt>1)
        {
        } else {
        }
        document.getElementById("Crono").innerHTML = "" + cpt ;
        cpt-- ;
        x = setTimeout("decompte()",1000) ;
    }
    else
    {
        clearTimeout(x) ;
    }
}
/* -- -- -- Fin -- -- -- */
	</script>
</head>

<body onload="decompte();">
		<div id="Crono"></div>
		<img id="logo" src="images/logo.svg" alt="Logo vidéobox" style="width: 120px;position: absolute;left: 8px;bottom: 8px;">
		<img id="rond" src="images/rond.svg" alt="rond">
		<div class="avous"><p>C'est à vous dans ...</p></div>
	</body>
</html>