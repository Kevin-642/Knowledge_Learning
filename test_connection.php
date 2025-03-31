<?php
try {
    $pdo = new \PDO('mysql:host=knowledge2-6781.mysql.b.osc-fr1.scalingo-dbs.com;port=31762;dbname=knowledge2_6781', 'knowledge2_6781', '3qTDzaFh9IIFDjgu8Z_j0PbKAeHrg911cR2DmXFRuw4jDuIts6slApF9HhNtjvRB');
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie à la base de données !";
} catch (\PDOException $e) {
    echo "Échec de la connexion : " . $e->getMessage();
}
