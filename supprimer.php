<?php
require 'db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$req = $db->prepare('DELETE FROM salle WHERE id = ?');
$req->execute([$id]);

header('Location: index.php?message=' . urlencode('La salle a été supprimée.'));
exit;
