<?php
session_start();
require 'constants.php';
if(!isset($_SESSION['Role'])){
    header("Location: inloggen.php");
}
else{
    if($_SESSION['Role'] != "admin"){
        header("Location: inloggen.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/normalize.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <title>E3T</title>
    </head>
    <body>
        <header>
            <div id="headerContent">
                <h2><a href="index.php">E3T</a></h2>
            </div>
            <nav>
                <a href="talenten.php">Talenten</a>
                <a href="evenementen.php">Evenementen</a>
                <a href="inloggen.php">Inloggen</a>
            </nav>
        </header>
        <main>
            <div id="registrationForm">
                <h1>Bewerk profiel</h1>
                <?php

                if($_SERVER['REQUEST_METHOD'] == "POST"){
                    $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_SPECIAL_CHARS);
                    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
                    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
                    try{
                        $dbHandler = new PDO("mysql:host={$dbhost};dbname={$dbname};charset=utf8;", "{$dbuser}", "{$dbpassword}");
                    }
                    catch (Exception $ex){
                        $error = "Er is een verbindings fout met de database";
                    }
                    if(isset($dbHandler)){
                        if($username && $password){
                            $hashed = password_hash($password, PASSWORD_BCRYPT);
                            $stmt = $dbHandler->prepare("UPDATE `Login` SET Username = :Username, Password = :Password WHERE LoginID= :id;");
                            $stmt->bindParam('id', $id, PDO::PARAM_STR);
                            $stmt->bindParam('Username', $username, PDO::PARAM_STR);
                            $stmt->bindParam('Password', $hashed, PDO::PARAM_STR);
                        }
                        elseif($username){
                            $stmt = $dbHandler->prepare("UPDATE `Login` SET Username = :Username WHERE LoginID= :id;");
                            $stmt->bindParam('id', $id, PDO::PARAM_STR);
                            $stmt->bindParam('Username', $username, PDO::PARAM_STR);
                        }
                        elseif($password){
                            $hashed = password_hash($password, PASSWORD_BCRYPT);
                            $stmt = $dbHandler->prepare("UPDATE `Login` SET Password = :Password WHERE LoginID= :id;");
                            $stmt->bindParam('id', $id, PDO::PARAM_STR);
                            $stmt->bindParam('Password', $hashed, PDO::PARAM_STR);
                        }
                        else{
                            $error = "Niets veranderd";
                        }

                        try {
                            $stmt->execute();
                        }
                        catch(Exception $ex){
                            echo $ex;
                            $error = "Profiel kon niet bewerkt worden";
                        }
                        if(!isset($error)){
                            $succes = "Profiel is aangepast";
                        }

                    }
                }

                if($_SESSION['Role'] == "admin"){
                    ?>




                    <?php
                    if(isset($_GET['id'])){
                        $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_SPECIAL_CHARS);
                        if(!$id){
                            echo '<a href="manageprofile.php">Ga terug naar het overzicht en probeer het opnieuw.</a>';
                        }
                        else{
                            try {
                                $dbHandler = new PDO("mysql:host={$dbhost};dbname={$dbname};charset=utf8;", "{$dbuser}", "{$dbpassword}");
                            }
                            catch(Exception $ex){
                                echo $ex;
                            }
                            $stmt = $dbHandler->prepare("SELECT * FROM `Login` WHERE LoginID = :id;");
                            $stmt->bindParam('id', $id, PDO::PARAM_STR);
                            $stmt->bindColumn("Username", $username, PDO::PARAM_STR);
                            $stmt->execute();
                            echo "<form action='editaccount.php' method='POST' id='editform'>";
                            while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
                                echo "<label for='username'>Username: </label><input type='text' name='username' value='$username'>";
                                echo "<label for='password'>Wachtwoord: </label><input type='password' name='password'>";
                                echo "<input type='hidden' name='id' value='$id'>";
                            }
                            echo '<input type="submit" value="Submit">';
                        }
                    }

                    if(isset($error)){
                        echo "<p class='error'>{$error}</p>";
                    }
                    if(isset($succes)){
                        echo "<p class='succes'>{$succes}</p>";
                    }
                }
                $dbHandler = null;
                ?>
            </div>
        </main>
        <footer>
            <div id="item1">
                <h3>Informatie</h3>
                <ul>
                    <li>Veelgestelde Vragen</li>
                    <li>Over Ons</li>
                    <li>Vacetures</li>
                    <li>Terms of Service</li>
                    <li>Privacy Policy</li>
                    <li>Recenties</li>
                </ul>
            </div>
            <div id="item2">
                <h3>Contact</h3>
                <ul>
                    <li>Straatnaam 1</li>
                    <li>1234PV</li>
                    <li>06-12345678</li>
                    <li>support@E3T.com</li>
                </ul>
            </div>
            <div id="item3">
                <h3>Social Media</h3>
                <img src="img/logos.png" alt="social media logos">
            </div>
        </footer>
        <div id="subfooter";>
            <p>Privacy Policy  l  Algemene voorwaarden  l  Disclaimer  l  Cookies</p>
        </div>
    </body>
</html>