<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
  header("Location: page_login.html"); // Rediriger vers page_login.html
  exit();
}

// Votre code existant continue à partir d'ici
?>

<!DOCTYPE html>
<html>
<head>
  <title>Liste des Tickets</title>

  <!-- Inclusion des fichiers CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="styles/styles_globaux.css">
</head>
<body>
  <!-- Barre de navigation -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="page_liste_tickets.php">Projet Tickets</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="?status=ouvert">Tickets Ouverts</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="?status=ferme">Tickets Fermés</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="?status=tous">Tous les Tickets</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="page_ajout_ticket.php">Ajouter un Ticket</a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="#">Utilisateur connecté : <?php echo $_SESSION['username']; ?></a>
        </li>
        <li class="nav-item">
          <button type="button" class="btn btn-logout btn-danger" onclick="showLogoutModal()">Déconnexion</button>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Fenêtre modale de confirmation de déconnexion -->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="logoutModalLabel">Déconnexion</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Êtes-vous sûr de vouloir vous déconnecter ?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
          <button type="button" class="btn btn-logout btn-danger" onclick="logout()">Déconnexion</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Contenu principal -->
  <div class="container">
    <h2 class="text-center">Liste des Tickets</h2>
    <div class="text-center">
      <a href="?status=ouvert" class="btn btn-primary">Tickets Ouverts</a>
      <a href="?status=ferme" class="btn btn-primary">Tickets Fermés</a>
      <a href="?status=tous" class="btn btn-primary">Tous les Tickets</a>
      <a href="page_ajout_ticket.php" class="btn btn-primary btn-success">Ajouter un Ticket</a>
    </div>
    <?php
    // Connexion à la base de données
    $dsn = 'mysql:host=localhost;dbname=PROJET;charset=utf8';
    $username = 'root';
    $password = '';

    try {
      $conn = new PDO($dsn, $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Récupération du paramètre de tri
      $sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'NumeroTicket';
      $sortOrder = isset($_GET['order']) && strtolower($_GET['order']) === 'desc' ? 'DESC' : 'ASC';

      // Récupération du statut des tickets à afficher
      $status = isset($_GET['status']) && ($_GET['status'] === 'ouvert' || $_GET['status'] === 'ferme') ? $_GET['status'] : 'tous';

      // Récupération des valeurs des listes déroulantes
      $createur = isset($_GET['createur']) ? $_GET['createur'] : '';
      $priorite = isset($_GET['priorite']) ? $_GET['priorite'] : '';
      $typedemande = isset($_GET['typedemande']) ? $_GET['typedemande'] : '';

      // Requête pour récupérer les tickets et les trier
      $query = 'SELECT Tickets.NumeroTicket, Tickets.SujetTicket, Priorite.NomPriorite, TypeDemande.NomTypeDemande, StatutTicket.NomStatut, Tickets.MessageTicket, Tickets.DateTicket, Tickets.PieceJointeTicket, Users.IdentifiantUser AS Createur
                FROM Tickets
                INNER JOIN Priorite ON Tickets.PrioriteTicket = Priorite.IdPriorite
                INNER JOIN TypeDemande ON Tickets.TypeDemandeTicket = TypeDemande.IdTypeDemande
                INNER JOIN StatutTicket ON Tickets.StatutTicket = StatutTicket.IdStatutTicket
                INNER JOIN Users ON Tickets.IdCreateurTicket = Users.IdUser';

      // Conditions pour filtrer les tickets
      $conditions = array();

      if ($status === 'ouvert') {
        $conditions[] = 'StatutTicket.NomStatut = "ouvert"';
      } elseif ($status === 'ferme') {
        $conditions[] = 'StatutTicket.NomStatut = "fermé"';
      }

      if (!empty($createur)) {
        $conditions[] = 'Users.IdentifiantUser = :createur';
      }

      if (!empty($priorite)) {
        $conditions[] = 'Priorite.NomPriorite = :priorite';
      }

      if (!empty($typedemande)) {
        $conditions[] = 'TypeDemande.NomTypeDemande = :typedemande';
      }

      // Construction de la clause WHERE
      if (!empty($conditions)) {
        $query .= ' WHERE ' . implode(' AND ', $conditions);
      }

      // Ajout de la clause ORDER BY
      $query .= ' ORDER BY ' . $sortColumn . ' ' . $sortOrder;

      // Préparation de la requête
      $stmt = $conn->prepare($query);

      // Liaison des valeurs des listes déroulantes à la requête
      if (!empty($createur)) {
        $stmt->bindValue(':createur', $createur);
      }

      if (!empty($priorite)) {
        $stmt->bindValue(':priorite', $priorite);
      }

      if (!empty($typedemande)) {
        $stmt->bindValue(':typedemande', $typedemande);
      }

      $stmt->execute();

      // Récupération des tickets
      $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Vérification s'il y a des tickets
      if (count($tickets) > 0) {
        echo '<table class="table table-striped mt-4">';
        echo '<thead>';
        echo '<tr>';
        echo '<th><a href="?status=' . $status . '&sort=NumeroTicket&order=' . ($sortColumn === 'NumeroTicket' && $sortOrder === 'ASC' ? 'desc' : 'asc') . '">Numéro</a></th>';
        echo '<th><a href="?status=' . $status . '&sort=SujetTicket&order=' . ($sortColumn === 'SujetTicket' && $sortOrder === 'ASC' ? 'desc' : 'asc') . '">Sujet</a></th>';
        echo '<th><a href="?status=' . $status . '&sort=Createur&order=' . ($sortColumn === 'Createur' && $sortOrder === 'ASC' ? 'desc' : 'asc') . '">Créateur</a></th>';
        echo '<th><a href="?status=' . $status . '&sort=PrioriteTicket&order=' . ($sortColumn === 'PrioriteTicket' && $sortOrder === 'ASC' ? 'desc' : 'asc') . '">Priorité</a></th>';
        echo '<th><a href="?status=' . $status . '&sort=TypeDemandeTicket&order=' . ($sortColumn === 'TypeDemandeTicket' && $sortOrder === 'ASC' ? 'desc' : 'asc') . '">Type de demande</a></th>';
        echo '<th><a href="?status=' . $status . '&sort=StatutTicket&order=' . ($sortColumn === 'StatutTicket' && $sortOrder === 'ASC' ? 'desc' : 'asc') . '">Statut</a></th>';
        echo '<th><a href="?status=' . $status . '&sort=MessageTicket&order=' . ($sortColumn === 'MessageTicket' && $sortOrder === 'ASC' ? 'desc' : 'asc') . '">Message</a></th>';
        echo '<th><a href="?status=' . $status . '&sort=DateTicket&order=' . ($sortColumn === 'DateTicket' && $sortOrder === 'ASC' ? 'desc' : 'asc') . '">Date</a></th>';
        echo '<th><a href="?status=' . $status . '&sort=PieceJointeTicket&order=' . ($sortColumn === 'PieceJointeTicket' && $sortOrder === 'ASC' ? 'desc' : 'asc') . '">Pièce jointe</a></th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        // Parcours des tickets
        foreach ($tickets as $ticket) {
          echo '<tr>';
          echo '<td><a href="page_details_ticket.php?id=' . $ticket['NumeroTicket'] . '">' . $ticket['NumeroTicket'] . '</a></td>';
          echo '<td>' . $ticket['SujetTicket'] . '</td>';
          echo '<td>' . $ticket['Createur'] . '</td>';
          echo '<td>' . $ticket['NomPriorite'] . '</td>';
          echo '<td>' . $ticket['NomTypeDemande'] . '</td>';
          echo '<td>' . $ticket['NomStatut'] . '</td>';
          echo '<td>' . $ticket['MessageTicket'] . '</td>';
          echo '<td>' . date('Y-m-d', strtotime($ticket['DateTicket'])) . '</td>';
          echo '<td>' . $ticket['PieceJointeTicket'] . '</td>';
          echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
      } else {
        echo 'Aucun ticket trouvé.';
      }
    } catch (PDOException $e) {
      echo 'Erreur : ' . $e->getMessage();
    }

    $conn = null;
    ?>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    // Fonction pour afficher la fenêtre modale de déconnexion
    function showLogoutModal() {
      $('#logoutModal').modal('show'); // Afficher la fenêtre modale
    }

    // Fonction pour déconnecter l'utilisateur
    function logout() {
      // Soumettre le formulaire de déconnexion
      document.getElementById('logoutForm').submit();
    }
  </script>
  <!-- Formulaire de déconnexion -->
  <form id="logoutForm" action="logout.php" method="post">
    <input type="hidden" name="logout" value="1">
  </form>
</body>
</html>
