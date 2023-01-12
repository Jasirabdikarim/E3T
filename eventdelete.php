<?php
session_start();
if (!isset($_SESSION['Username'])) {
    header("Location: inloggen.php");
}
require 'constants.php';
try {
    $dbHandler = new PDO ("mysql:host={$dbhost};dbname={$dbname};charset=utf8;", "{$dbuser}", "{$dbpassword}");
} catch (Exception $ex) {
    echo $ex;
}
if (isset($dbHandler)) {
    $stmt = $dbHandler->prepare("DELETE FROM Event WHERE EventID = :EventID");
    $stmt->bindParam("EventID", $_GET['id'], PDO::PARAM_STR);
    $stmt->execute();

    header("Location: evenementen.php");
    exit();
}
$dbHandler = null;
?>
