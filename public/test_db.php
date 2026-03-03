<?php
try {
    $dbh = new PDO('mysql:host=TON_HOTE_IONOS;dbname=TON_NOM_DB', 'TON_USER', 'TON_PASS');
    echo "Connexion réussie !";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
