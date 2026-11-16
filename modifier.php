<?php
require 'db.php';

$id = $_GET['id'];

$req = $db->prepare('SELECT * FROM salle WHERE id = ?');
$req->execute([$id]);
$salle = $req->fetch();

if (!$salle) {
    header('Location: index.php');
    exit;
}

$nom = $salle['nom'];
$capacite = $salle['capacite'];
$etage = $salle['etage'];
$videoprojecteur = $salle['videoprojecteur'];
$ecran = $salle['ecran'];
$tableau_blanc = $salle['tableau_blanc'];
$visio = $salle['visio'];
$erreurs = [];

if (isset($_POST['nom'])) {
    $nom = trim($_POST['nom']);
    $capacite = trim($_POST['capacite']);
    $etage = trim($_POST['etage']);

    $videoprojecteur = 0;
    $ecran = 0;
    $tableau_blanc = 0;
    $visio = 0;
    if (isset($_POST['videoprojecteur'])) {
        $videoprojecteur = 1;
    }
    if (isset($_POST['ecran'])) {
        $ecran = 1;
    }
    if (isset($_POST['tableau_blanc'])) {
        $tableau_blanc = 1;
    }
    if (isset($_POST['visio'])) {
        $visio = 1;
    }

    if ($nom == '') {
        $erreurs[] = 'Le nom est obligatoire.';
    }
    if (!ctype_digit($capacite) || $capacite == 0) {
        $erreurs[] = 'La capacité doit être un nombre entier plus grand que 0.';
    }
    if (!is_numeric($etage)) {
        $erreurs[] = 'L\'étage doit être un nombre.';
    }

    $req = $db->prepare('SELECT * FROM salle WHERE nom = ? AND id != ?');
    $req->execute([$nom, $id]);
    if ($req->fetch()) {
        $erreurs[] = 'Ce nom de salle est déjà pris.';
    }

    if (count($erreurs) == 0) {
        $req = $db->prepare('UPDATE salle SET nom = ?, capacite = ?, etage = ?, videoprojecteur = ?, ecran = ?, tableau_blanc = ?, visio = ? WHERE id = ?');
        $req->execute([$nom, $capacite, $etage, $videoprojecteur, $ecran, $tableau_blanc, $visio, $id]);
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modifier une salle</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Modifier une salle</h1>

<?php foreach ($erreurs as $erreur) { ?>
    <p class="erreur"><?php echo $erreur; ?></p>
<?php } ?>

<form method="post" action="modifier.php?id=<?php echo $id; ?>">
    <p>Nom : <input type="text" name="nom" value="<?php echo htmlspecialchars($nom); ?>"></p>
    <p>Capacité : <input type="text" name="capacite" value="<?php echo htmlspecialchars($capacite); ?>"></p>
    <p>Étage : <input type="text" name="etage" value="<?php echo htmlspecialchars($etage); ?>"></p>
    <p>
        <input type="checkbox" name="videoprojecteur" <?php if ($videoprojecteur == 1) { echo 'checked'; } ?>> Vidéoprojecteur<br>
        <input type="checkbox" name="ecran" <?php if ($ecran == 1) { echo 'checked'; } ?>> Écran<br>
        <input type="checkbox" name="tableau_blanc" <?php if ($tableau_blanc == 1) { echo 'checked'; } ?>> Tableau blanc<br>
        <input type="checkbox" name="visio" <?php if ($visio == 1) { echo 'checked'; } ?>> Visioconférence
    </p>
    <button type="submit">Modifier</button>
    <a href="index.php">Retour</a>
</form>

</body>
</html>
