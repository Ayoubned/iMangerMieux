<?php
require_once('config.php');

$connectionString = "mysql:host=" . _MYSQL_HOST;
if(defined('_MYSQL_PORT')) {
    $connectionString .= ";port=" . _MYSQL_PORT;
}
$connectionString .= ";dbname=" . _MYSQL_DBNAME;

$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);

try {
    $pdo = new PDO($connectionString, _MYSQL_USER, _MYSQL_PASSWORD, $options);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = file_get_contents('sql/database.sql');
    $pdo->exec($sql);
    echo "Database created successfully!";
} catch (PDOException $error) {
    echo "Error: " . $error->getMessage();
}

$pdo = null; 
