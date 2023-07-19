<?php
	// Début de la session
	session_start();

	// Suppression des données de la session
	session_unset();

	// Destruction de la session
	session_destroy();

	// Redirection vers la page de connexion
	header("Location: page_login.php");
	exit();
?>
