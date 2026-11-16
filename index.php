<?php
require 'db.php';

$min = '';
$erreur = '';

if (isset($_GET['min'])) {
    $min = $_GET['min'];
}

if ($min == '') {
    $req = $db->query('SELECT * FROM salle ORDER BY nom');
} else if (!ctype_digit($min)) {
    $erreur = 'La capacité doit être un nombre entier.';
    $req = $db->query('SELECT * FROM salle ORDER BY nom');
} else {
    $req = $db->prepare('SELECT * FROM salle WHERE capacite >= ? ORDER BY capacite');
    $req->execute([$min]);
}

$salles = $req->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SalleLib</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>SalleLib</h1>

<form method="get" action="index.php">
    Capacité minimale :
    <input type="text" name="min" value="<?php echo htmlspecialchars($min); ?>">
    <button type="submit">Rechercher</button>
    <a href="index.php">Tout afficher</a>
</form>

<?php if ($erreur != '') { ?>
    <p class="erreur"><?php echo $erreur; ?></p>
<?php } ?>

<p><a href="ajouter.php">Ajouter une salle</a></p>

<table>
    <tr>
        <th>Nom</th>
        <th>Capacité</th>
        <th>Étage</th>
        <th>Équipements</th>
        <th></th>
    </tr>
    <?php foreach ($salles as $salle) { ?>
        <tr>
            <td><?php echo htmlspecialchars($salle['nom']); ?></td>
            <td><?php echo $salle['capacite']; ?></td>
            <td><?php echo $salle['etage']; ?></td>
            <td>
                <?php
                if ($salle['videoprojecteur'] == 1) {
                    echo 'Vidéoprojecteur<br>';
                }
                if ($salle['ecran'] == 1) {
                    echo 'Écran<br>';
                }
                if ($salle['tableau_blanc'] == 1) {
                    echo 'Tableau blanc<br>';
                }
                if ($salle['visio'] == 1) {
                    echo 'Visioconférence<br>';
                }
                ?>
            </td>
            <td>
                <a href="modifier.php?id=<?php echo $salle['id']; ?>">Modifier</a>
                <a href="supprimer.php?id=<?php echo $salle['id']; ?>">Supprimer</a>
            </td>
        </tr>
    <?php } ?>
</table>

<?php if (count($salles) == 0) { ?>
    <p>Aucune salle trouvée.</p>
<?php } ?>

</body>
</html>
