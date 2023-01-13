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
                <h2>E3T</h2>
            </div>
            <nav>
                <a href="talenten.php">Talenten</a>
                <a href="evenementen.php">Evenementen</a>
                <a href="inloggen.php">Inloggen</a>
            </nav>
        </header>
        <main>
            <div id="registrationForm">
                <h1>Verwijder profiel</h1>
                <?php

                if($_SERVER['REQUEST_METHOD'] == "POST") {
                    $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_SPECIAL_CHARS);
                    $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
                    $country = filter_input(INPUT_POST, "country", FILTER_SANITIZE_SPECIAL_CHARS);
                    $phone = filter_input(INPUT_POST, "phone", FILTER_SANITIZE_SPECIAL_CHARS);
                    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_SPECIAL_CHARS);
                    $description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_SPECIAL_CHARS);
                    try {
                        $dbHandler = new PDO("mysql:host={$dbhost};dbname={$dbname};charset=utf8;", "{$dbuser}", "{$dbpassword}");
                    } catch (Exception $ex) {
                        $error = "Er is een verbindings fout met de database";
                    }
                    if (isset($dbHandler)) {
                        $stmt = $dbHandler->prepare("UPDATE `Login` SET TalentID = null WHERE TalentID = :id;");
                        $stmt->bindParam('id', $id, PDO::PARAM_STR);

                        try {
                            $stmt->execute();
                        } catch (Exception $ex) {
                            echo $ex;
                            $error = "Profiel kon niet bewerkt worden";
                        }
                        if (isset($dbHandler) && !isset($error)) {
                            $stmt = $dbHandler->prepare("DELETE from `Talentprofile` WHERE TalentID= :id;");
                            $stmt->bindParam('id', $id, PDO::PARAM_STR);

                            try {
                                $stmt->execute();
                            } catch (Exception $ex) {
                                echo $ex;
                                $error = "Profiel kon niet bewerkt worden";
                            }
                            if (!isset($error)) {
                                $succes = "Profiel is verwijderd";
                            }

                        }
                    }
                }

                if($_SESSION['Role'] == "admin"){
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
                            $stmt = $dbHandler->prepare("SELECT * FROM `Talentprofile` WHERE TalentID= :id;");
                            $stmt->bindParam('id', $id, PDO::PARAM_STR);
                            $stmt->bindColumn("Name", $name, PDO::PARAM_STR);
                            $stmt->execute();
                            echo "<form action='deleteprofile.php' method='POST' id='editform'>";
                            while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
                                echo "Weet u zeker dat u het profiel van $name wilt verwijderen? ";
                                echo "<input type='hidden' name='id' value='$id'>";
                            }
                            echo '<input type="submit" value="Verwijder">';
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
    </body>
</html>