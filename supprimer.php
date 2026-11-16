<?php
require 'db.php';

$req = $db->prepare('DELETE FROM salle WHERE id = ?');
$req->execute([$_GET['id']]);

header('Location: index.php');
