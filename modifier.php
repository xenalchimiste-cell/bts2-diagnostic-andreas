<?php
require 'db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$req = $db->prepare('SELECT * FROM salle WHERE id = ?');
$req->execute([$id]);
$salle = $req->fetch();

if (!$salle) {
    header('Location: index.php?message=' . urlencode('Cette salle n\'existe pas.'));
    exit;
}

$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $salle['nom'] = trim($_POST['nom']);
    $salle['capacite'] = trim($_POST['capacite']);
    $salle['etage'] = trim($_POST['etage']);
    $salle['videoprojecteur'] = isset($_POST['videoprojecteur']) ? 1 : 0;
    $salle['ecran'] = isset($_POST['ecran']) ? 1 : 0;
    $salle['tableau_blanc'] = isset($_POST['tableau_blanc']) ? 1 : 0;
    $salle['visio'] = isset($_POST['visio']) ? 1 : 0;

    $erreurs = verifier($db, $salle['nom'], $salle['capacite'], $salle['etage'], $id);

    if (count($erreurs) == 0) {
        $req = $db->prepare('UPDATE salle SET nom = ?, capacite = ?, etage = ?, videoprojecteur = ?, ecran = ?, tableau_blanc = ?, visio = ? WHERE id = ?');
        $req->execute([$salle['nom'], $salle['capacite'], $salle['etage'], $salle['videoprojecteur'], $salle['ecran'], $salle['tableau_blanc'], $salle['visio'], $id]);
        header('Location: index.php?message=' . urlencode('La salle a été modifiée.'));
        exit;
    }
}

include 'header.php';
?>
<h2>Modifier la salle</h2>
<?php include 'formulaire.php'; ?>
</body>
</html>
