<?php
require 'db.php';

$min = isset($_GET['min']) ? trim($_GET['min']) : '';
$erreur = '';

if ($min != '') {
    if (!ctype_digit($min)) {
        $erreur = "La capacité minimale doit être un nombre entier positif.";
        $salles = $db->query('SELECT * FROM salle ORDER BY nom')->fetchAll();
    } else {
        $req = $db->prepare('SELECT * FROM salle WHERE capacite >= ? ORDER BY capacite');
        $req->execute([$min]);
        $salles = $req->fetchAll();
    }
} else {
    $salles = $db->query('SELECT * FROM salle ORDER BY nom')->fetchAll();
}

include 'header.php';
?>

<?php if (isset($_GET['message'])) { ?>
    <p class="ok"><?= htmlspecialchars($_GET['message']) ?></p>
<?php } ?>

<?php if ($erreur != '') { ?>
    <p class="erreur"><?= $erreur ?></p>
<?php } ?>

<form method="get" action="index.php">
    Capacité minimale :
    <input type="text" name="min" value="<?= htmlspecialchars($min) ?>">
    <button type="submit">Rechercher</button>
    <a href="index.php">Tout afficher</a>
</form>

<p><a class="bouton" href="ajouter.php">+ Ajouter une salle</a></p>

<table>
    <tr>
        <th>Nom</th>
        <th>Capacité</th>
        <th>Étage</th>
        <th>Équipements</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($salles as $salle) { ?>
        <tr>
            <td><?= htmlspecialchars($salle['nom']) ?></td>
            <td><?= $salle['capacite'] ?></td>
            <td><?= $salle['etage'] ?></td>
            <td>
                <?php
                $equipements = [];
                if ($salle['videoprojecteur']) $equipements[] = 'Vidéoprojecteur';
                if ($salle['ecran']) $equipements[] = 'Écran';
                if ($salle['tableau_blanc']) $equipements[] = 'Tableau blanc';
                if ($salle['visio']) $equipements[] = 'Visioconférence';
                echo count($equipements) > 0 ? implode(', ', $equipements) : 'Aucun';
                ?>
            </td>
            <td>
                <a href="modifier.php?id=<?= $salle['id'] ?>">Modifier</a>
                <a href="supprimer.php?id=<?= $salle['id'] ?>" onclick="return confirm('Supprimer cette salle ?')">Supprimer</a>
            </td>
        </tr>
    <?php } ?>
</table>

<?php if (count($salles) == 0) { ?>
    <p>Aucune salle trouvée.</p>
<?php } ?>

</body>
</html>
