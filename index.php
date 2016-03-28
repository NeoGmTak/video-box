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
		<p class="text">Démarer l'expérience !</p>
		<div id="lien" href=""><img id="play" src="images/play.svg" alt="Boutton démarrer"></div>
		<video id="videoA" controls>
                <source src="video/video.mp4" type="video/mp4">
                    </video>
		<img id="logo" src="images/logo.svg" alt="Logo vidéobox">
	</section>
	<script>
        function effacer() {
            $('#A').css('display', 'none');
            document.getElementById('videoA').pause();
        }
        $( "#lien" ).click(function() {
                $('video').css('display', 'block');
                $('#A').css('display', 'block');
                $('#A > video').css('display', 'block');
                var video = document.getElementById('videoA');
                video.currentTime = 0;
                video.play();
                if (video.requestFullscreen) {
                  video.requestFullscreen();
                } else if (video.mozRequestFullScreen) {
                  video.mozRequestFullScreen();
                } else if (video.webkitRequestFullscreen) {
                  video.webkitRequestFullscreen();
                }

                 video.onended = function(e) {
                    window.location.href = 'formulaire.php'; 
             };
});
    </script>

	</body>
</html>