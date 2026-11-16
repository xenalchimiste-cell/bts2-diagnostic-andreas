<?php
$db = new PDO('sqlite:' . __DIR__ . '/salles.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

function verifier($db, $nom, $capacite, $etage, $id) {
    $erreurs = [];
    if ($nom == '') {
        $erreurs[] = "Le nom est obligatoire.";
    }
    if ($capacite == '' || !ctype_digit($capacite) || $capacite <= 0) {
        $erreurs[] = "La capacité doit être un nombre entier supérieur à 0.";
    }
    if ($etage == '' || !preg_match('/^-?[0-9]+$/', $etage)) {
        $erreurs[] = "L'étage doit être un nombre entier.";
    }
    if ($nom != '') {
        $req = $db->prepare('SELECT id FROM salle WHERE nom = ? AND id != ?');
        $req->execute([$nom, $id]);
        if ($req->fetch()) {
            $erreurs[] = "Une salle avec le nom \"$nom\" existe déjà.";
        }
    }
    return $erreurs;
}
