<?php
  // Début de la session
  session_start();

  // Informations de connexion à la base de données
  $dsn = 'mysql:host=localhost;dbname=PROJET;charset=utf8';
  $username = 'root';
  $password = '';

  try {
    // Connexion à la base de données
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des données du formulaire
    $identifiant = $_POST['identifiant'];
    $motDePasse = $_POST['motDePasse'];

    // Requête pour vérifier les informations de connexion dans la table Users
    $stmt = $conn->prepare('SELECT * FROM Users WHERE IdentifiantUser = :identifiant AND MotDePasseUser = :motDePasse');
    $stmt->execute(array('identifiant' => $identifiant, 'motDePasse' => $motDePasse));

    // Récupération du résultat de la requête
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
      // Si les informations de connexion sont valides, enregistre le nom d'utilisateur dans la session
      $_SESSION['username'] = $result['IdentifiantUser'];
      // Retourne une réponse JSON indiquant le succès de la connexion et les informations de l'utilisateur
      echo json_encode(array('success' => true, 'identifiant' => $result['IdentifiantUser'], 'motDePasse' => $result['MotDePasseUser']));
    } else {
      // Si les informations de connexion sont invalides, retourne une réponse JSON indiquant l'échec de la connexion
      echo json_encode(array('success' => false));
    }
  } catch(PDOException $e) {
    // En cas d'erreur lors de la connexion à la base de données, retourne une réponse JSON indiquant l'échec de la connexion
    echo json_encode(array('success' => false));
  }
?>
