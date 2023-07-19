<?php
// Initialiser les variables des champs du formulaire
$identifiant = "";
$motDePasse = "";
$erreurs = [];

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $identifiant = trim($_POST["identifiant"]);
    $motDePasse = $_POST["motDePasse"];

    // Valider les données
    if (empty($identifiant)) {
        $erreurs[] = "Veuillez entrer un identifiant.";
    }

    if (empty($motDePasse)) {
        $erreurs[] = "Veuillez entrer un mot de passe.";
    }

    // Si aucune erreur, procéder à l'inscription
    if (empty($erreurs)) {
        // Connexion à la base de données
        $dsn = 'mysql:host=localhost;dbname=PROJET;charset=utf8';
        $username = 'root';
        $password = '';

        try {
            $conn = new PDO($dsn, $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Vérifier si l'identifiant existe déjà dans la base de données
            $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM Users WHERE IdentifiantUser = :identifiant");
            $stmt->bindValue(':identifiant', $identifiant);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                $erreurs[] = "Cet identifiant est déjà utilisé. Veuillez en choisir un autre.";
                $message = "Cet identifiant est déjà utilisé. Veuillez en choisir un autre.";
            } else {
                // Insérer le nouvel utilisateur dans la base de données
                $stmt = $conn->prepare("INSERT INTO Users (IdentifiantUser, MotDePasseUser) VALUES (:identifiant, :motDePasse)");
                $stmt->bindValue(':identifiant', $identifiant);
                $stmt->bindValue(':motDePasse', $motDePasse);
                $stmt->execute();

                // Récupérer l'utilisateur nouvellement inséré
                $stmt = $conn->prepare("SELECT * FROM Users WHERE IdentifiantUser = :identifiant");
                $stmt->bindValue(':identifiant', $identifiant);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Création de la session pour le nouvel utilisateur
                session_start();
                $_SESSION['user_id'] = $user['IdUser']; // Vous pouvez stocker d'autres informations de l'utilisateur dans la session si nécessaire
                $_SESSION['username'] = $user['IdentifiantUser']; // Ajoutez cette ligne pour définir la variable de session 'username'

                $message = "Inscription réussie. Vous êtes maintenant inscrit.";
            }
        } catch (PDOException $e) {
            // Gérer les erreurs de connexion à la base de données
            $erreurs[] = "Erreur de connexion à la base de données : " . $e->getMessage();
            $message = implode("\n", $erreurs);
        }

        $conn = null;
    } else {
        // Message d'erreur pour SweetAlert2
        $message = "Erreur lors de l'inscription :";

        // Ajouter les messages d'erreur spécifiques à la variable $message
        foreach ($erreurs as $erreur) {
            $message .= "\n- " . $erreur;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Projet Tickets - Inscription</title>
    <meta charset="UTF-8">

    <!-- Inclusion des fichiers CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles/styles_globaux.css">
    <link rel="stylesheet" href="styles/styles_formulaires.css">
    <!-- Scripts JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
</head>
<body>
<!-- Barre de navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Projet Tickets</a>
</nav>

<!-- Conteneur principal -->
<div class="container">
    <h2>Inscription</h2>

    <!-- Afficher les erreurs éventuelles -->
    <?php if (!empty($erreurs)): ?>
        <div class="alert alert-danger">
            <?php foreach ($erreurs as $erreur): ?>
                <p><?php echo $erreur; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Formulaire d'inscription -->
    <form id="inscriptionForm" method="POST">
        <div class="form-group">
            <label for="identifiant">Identifiant:</label>
            <input type="text" class="form-control" id="identifiant" name="identifiant" required>
        </div>
        <div class="form-group">
            <label for="motDePasse">Mot de passe:</label>
            <input type="password" class="form-control" id="motDePasse" name="motDePasse" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
    </form>
    <div class="text-center mt-2">
      <a href="page_login.php" class="btn btn-success mt-2">Se connecter</a>
    </div>
</div>

<!-- Affichage de l'alerte SweetAlert2 après l'inscription -->
<script>
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($erreurs)): ?>
    Swal.fire({
        icon: 'success',
        title: 'Succès',
        text: <?php echo json_encode($message); ?>,
        confirmButtonText: 'OK'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'page_liste_tickets.php'; // Rediriger vers la page de connexion après l'inscription réussie
        }
    });
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($erreurs)): ?>
    Swal.fire({
        icon: 'error',
        title: 'Erreur',
        html: <?php echo json_encode($message); ?>,
        confirmButtonText: 'OK'
    });
    <?php endif; ?>
</script>

</body>
</html>
