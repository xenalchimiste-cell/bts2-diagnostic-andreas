<?php
require 'db.php';

$erreurs = [];
$salle = ['nom' => '', 'capacite' => '', 'etage' => '', 'videoprojecteur' => 0, 'ecran' => 0, 'tableau_blanc' => 0, 'visio' => 0];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $salle['nom'] = trim($_POST['nom']);
    $salle['capacite'] = trim($_POST['capacite']);
    $salle['etage'] = trim($_POST['etage']);
    $salle['videoprojecteur'] = isset($_POST['videoprojecteur']) ? 1 : 0;
    $salle['ecran'] = isset($_POST['ecran']) ? 1 : 0;
    $salle['tableau_blanc'] = isset($_POST['tableau_blanc']) ? 1 : 0;
    $salle['visio'] = isset($_POST['visio']) ? 1 : 0;

    $erreurs = verifier($db, $salle['nom'], $salle['capacite'], $salle['etage'], 0);

    if (count($erreurs) == 0) {
        $req = $db->prepare('INSERT INTO salle (nom, capacite, etage, videoprojecteur, ecran, tableau_blanc, visio) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $req->execute([$salle['nom'], $salle['capacite'], $salle['etage'], $salle['videoprojecteur'], $salle['ecran'], $salle['tableau_blanc'], $salle['visio']]);
        header('Location: index.php?message=' . urlencode('La salle a été ajoutée.'));
        exit;
    }
}

include 'header.php';
?>
<h2>Ajouter une salle</h2>
<?php include 'formulaire.php'; ?>
</body>
</html>
