<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
  header("Location: page_login.php");
  exit();
}

// Vérifier si l'ID du ticket est passé en paramètre
if (!isset($_GET['id'])) {
  header("Location: page_liste_tickets.php");
  exit();
}

// Récupérer l'ID du ticket depuis les paramètres
$ticketId = $_GET['id'];

// Connexion à la base de données
$dsn = 'mysql:host=localhost;dbname=PROJET;charset=utf8';
$username = 'root';
$password = '';

try {
  $conn = new PDO($dsn, $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Requête pour récupérer les détails du ticket et les notes associées
  $query = 'SELECT Tickets.NumeroTicket, Tickets.SujetTicket, Priorite.NomPriorite, TypeDemande.NomTypeDemande, StatutTicket.NomStatut, Tickets.MessageTicket, Tickets.DateTicket, Tickets.PieceJointeTicket, Tickets.StatutTicket
            FROM Tickets
            INNER JOIN Priorite ON Tickets.PrioriteTicket = Priorite.IdPriorite
            INNER JOIN TypeDemande ON Tickets.TypeDemandeTicket = TypeDemande.IdTypeDemande
            INNER JOIN StatutTicket ON Tickets.StatutTicket = StatutTicket.IdStatutTicket
            WHERE Tickets.NumeroTicket = :ticketId';

  // Préparation de la requête
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':ticketId', $ticketId);
  $stmt->execute();

  // Récupération du ticket
  $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

  // Vérification si le ticket existe
  if (!$ticket) {
    echo 'Ticket introuvable.';
    exit();
  }

  // Requête pour récupérer les notes associées au ticket
  $queryNotes = 'SELECT Notes.DateNote, Notes.MessageNote, Users.IdentifiantUser, StatutNote.NomStatut AS StatutNote
                FROM Notes
                LEFT JOIN Users ON Notes.IdCreateurNote = Users.IdUser
                LEFT JOIN StatutNote ON Notes.StatutNote = StatutNote.IdStatutNote
                WHERE Notes.IdTicket = :ticketId';

  // Préparation de la requête
  $stmtNotes = $conn->prepare($queryNotes);
  $stmtNotes->bindParam(':ticketId', $ticketId);
  $stmtNotes->execute();

  // Récupération des notes
  $notes = $stmtNotes->fetchAll(PDO::FETCH_ASSOC);

  // Vérifier si l'utilisateur est autorisé à modifier le statut du ticket (par exemple, vérifier s'il est administrateur)
  $userCanChangeStatus = true; // Remplacez cette condition par la vérification appropriée

  // Vérifier si le bouton "Modifier le statut" a été cliqué
  if ($userCanChangeStatus && isset($_POST['confirm-action']) && $_POST['confirm-action'] === 'change-status') {
    try {
      // Inverser le statut du ticket (1 pour Ouvert, 2 pour Fermé)
      $newStatus = ($ticket['StatutTicket'] === 1) ? 2 : 1;

      // Mettre à jour le statut du ticket dans la base de données
      $updateQuery = 'UPDATE Tickets SET StatutTicket = :newStatus WHERE NumeroTicket = :ticketId';
      $updateStmt = $conn->prepare($updateQuery);
      $updateStmt->bindParam(':newStatus', $newStatus, PDO::PARAM_INT);
      $updateStmt->bindParam(':ticketId', $ticketId, PDO::PARAM_INT);
      $updateStmt->execute();

      // Redirection vers la page page_liste_tickets.php
      header("Location: page_liste_tickets.php");
      exit();
    } catch (PDOException $e) {
      echo 'Erreur : ' . $e->getMessage();
      exit();
    }
  }
} catch (PDOException $e) {
  echo 'Erreur : ' . $e->getMessage();
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Détails du Ticket</title>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="styles/styles_globaux.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="page_liste_tickets.php">Projet Tickets</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="page_liste_tickets.php">Liste des Tickets</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="page_ajout_ticket.php">Ajouter un Ticket</a>
        </li>
        <?php if ($ticket['StatutTicket'] === 1) : ?>
          <li class="nav-item">
            <a class="nav-link" href="page_ajout_note.php?id=<?php echo $ticketId; ?>">Ajouter une Note</a>
          </li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="#">Utilisateur connecté : <?php echo $_SESSION['username']; ?></a>
        </li>
        <li class="nav-item">
          <button class="btn-danger logout-btn" data-toggle="modal" data-target="#confirmLogoutModal">Déconnexion</button>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container">
    <h2 class="text-center mt-4">Détails du Ticket <?php echo $ticket['NumeroTicket']; ?></h2>
    <table class="table mt-4">
      <tbody>
        <tr>
          <th>Numéro :</th>
          <td><?php echo $ticket['NumeroTicket']; ?></td>
        </tr>
        <tr>
          <th>Sujet :</th>
          <td><?php echo $ticket['SujetTicket']; ?></td>
        </tr>
        <tr>
          <th>Priorité :</th>
          <td><?php echo $ticket['NomPriorite']; ?></td>
        </tr>
        <tr>
          <th>Type de demande :</th>
          <td><?php echo $ticket['NomTypeDemande']; ?></td>
        </tr>
        <tr>
          <th>Statut :</th>
          <td><?php echo $ticket['NomStatut']; ?></td>
        </tr>
        <tr>
          <th>Message :</th>
          <td><?php echo $ticket['MessageTicket']; ?></td>
        </tr>
        <tr>
          <th>Date :</th>
          <td><?php echo date('Y-m-d', strtotime($ticket['DateTicket'])); ?></td>
        </tr>
        <tr>
          <th>Pièce jointe :</th>
          <td><?php echo $ticket['PieceJointeTicket']; ?></td>
        </tr>
      </tbody>
    </table>

    <?php if ($userCanChangeStatus) : ?>
      <form method="post">
        <input type="hidden" name="confirm-action" value="change-status">
        <button type="submit" class="btn <?php echo ($ticket['StatutTicket'] === 1) ? 'btn-danger' : 'btn-success'; ?>">
          Changer le statut du ticket à <?php echo ($ticket['StatutTicket'] === 1) ? 'Fermé' : 'Ouvert'; ?>
        </button>
      </form>
    <?php endif; ?>

    <h3 class="mt-4">Notes :</h3>
    <?php if (count($notes) > 0) : ?>
      <table class="table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Créateur</th>
            <th>Statut</th>
            <th>Message</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($notes as $note) : ?>
            <tr>
              <td><?php echo $note['DateNote']; ?></td>
              <td><?php echo $note['IdentifiantUser']; ?></td>
              <td><?php echo $note['StatutNote']; ?></td>
              <td><?php echo $note['MessageNote']; ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else : ?>
      <p>Aucune note disponible.</p>
    <?php endif; ?>


    <!-- Bouton "Ajouter une note" -->
    <?php if ($ticket['StatutTicket'] === 1) : ?>
      <a href="page_ajout_note.php?id=<?php echo $ticketId; ?>" class="btn btn-success mb-4">Ajouter une note</a>
    <?php endif; ?>
  </div>

  <!-- Modal de confirmation de déconnexion -->
  <div class="modal fade" id="confirmLogoutModal" tabindex="-1" role="dialog" aria-labelledby="confirmLogoutModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmLogoutModalLabel">Confirmation de déconnexion</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Êtes-vous sûr de vouloir vous déconnecter ?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
          <button type="button" class="btn-danger logout-btn" onclick="logout()">Déconnexion</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    function logout() {
      window.location.href = 'logout.php';
    }
  </script>
</body>
</html>