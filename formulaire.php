<?php foreach ($erreurs as $e) { ?>
    <p class="erreur"><?= htmlspecialchars($e) ?></p>
<?php } ?>

<form method="post">
    <p>
        <label>Nom :</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($salle['nom']) ?>">
    </p>
    <p>
        <label>Capacité :</label>
        <input type="text" name="capacite" value="<?= htmlspecialchars($salle['capacite']) ?>">
    </p>
    <p>
        <label>Étage :</label>
        <input type="text" name="etage" value="<?= htmlspecialchars($salle['etage']) ?>">
    </p>
    <p>
        <label>Équipements :</label><br>
        <input type="checkbox" name="videoprojecteur" <?= $salle['videoprojecteur'] ? 'checked' : '' ?>> Vidéoprojecteur<br>
        <input type="checkbox" name="ecran" <?= $salle['ecran'] ? 'checked' : '' ?>> Écran<br>
        <input type="checkbox" name="tableau_blanc" <?= $salle['tableau_blanc'] ? 'checked' : '' ?>> Tableau blanc<br>
        <input type="checkbox" name="visio" <?= $salle['visio'] ? 'checked' : '' ?>> Visioconférence
    </p>
    <button type="submit">Enregistrer</button>
    <a href="index.php">Annuler</a>
</form>
