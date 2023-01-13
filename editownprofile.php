<?php
    session_start();
    require 'constants.php';
    if(!isset($_SESSION['Role'])){
        header("Location: inloggen.php");
    }
    else{
        if($_SESSION['Role'] != "talent"){
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
                    $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
                    $country = filter_input(INPUT_POST, "country", FILTER_SANITIZE_SPECIAL_CHARS);
                    $phone = filter_input(INPUT_POST, "phone", FILTER_SANITIZE_SPECIAL_CHARS);
                    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_SPECIAL_CHARS);
                    $description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_SPECIAL_CHARS);
                    try{
                        $dbHandler = new PDO("mysql:host={$dbhost};dbname={$dbname};charset=utf8;", "{$dbuser}", "{$dbpassword}");
                    }
                    catch (Exception $ex){
                        $error = "Er is een verbindings fout met de database";
                    }
                    if(isset($dbHandler)){
                        $stmt = $dbHandler->prepare("UPDATE `Talentprofile` SET Name = :Name, Country = :Country, Phone = :Phone, Email = :Email, Description = :Description WHERE TalentID= :id;");
                        $stmt->bindParam('id', $id, PDO::PARAM_STR);
                        $stmt->bindParam('Name', $name, PDO::PARAM_STR);
                        $stmt->bindParam('Country', $country, PDO::PARAM_STR);
                        $stmt->bindParam('Phone', $phone, PDO::PARAM_STR);
                        $stmt->bindParam('Email', $email, PDO::PARAM_STR);
                        $stmt->bindParam('Description', $description, PDO::PARAM_STR);

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

                else{
                    $loginID = $_SESSION['LoginID'];
                    try {
                        $dbHandler = new PDO("mysql:host={$dbhost};dbname={$dbname};charset=utf8;", "{$dbuser}", "{$dbpassword}");
                    }
                    catch(Exception $ex){
                        $error = "Er is een verbindings fout met de database";
                    }
                    if(!isset($error)){
                        $stmt = $dbHandler->prepare("SELECT TalentID FROM `Login` WHERE LoginID = :id");
                        $stmt->bindParam("id", $loginID, PDO::PARAM_INT);
                        $stmt->bindColumn("TalentID", $id, PDO::PARAM_INT);
                        try{
                            $stmt->execute();
                        }
                        catch(Exception $ex){
                            $error = "Er is een verbindings fout met de database";
                        }
                        if(!isset($error)){
                            $stmt->fetch(PDO::FETCH_ASSOC);
                        }
                    }
                    if(!$id){
                        echo '<a href="manageprofile.php">Ga terug naar het overzicht en probeer het opnieuw.</a>';
                    }
                    else{

                        $stmt = $dbHandler->prepare("SELECT * FROM `Talentprofile` WHERE TalentID= :id;");
                        $stmt->bindParam('id', $id, PDO::PARAM_STR);
                        $stmt->bindColumn("Name", $name, PDO::PARAM_STR);
                        $stmt->bindColumn("Country", $country, PDO::PARAM_STR);
                        $stmt->bindColumn("Phone", $phone, PDO::PARAM_STR);
                        $stmt->bindColumn("Email", $email, PDO::PARAM_STR);
                        $stmt->bindColumn("Description", $description, PDO::PARAM_STR);
                        $stmt->execute();
                        echo "<form action='editprofile.php' method='POST' id='editform'>";
                        while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
                            echo "<label for='name'>Naam: </label><input type='text' name='name' value='$name'>";
                            echo "<label for='country'>Land: </label><input type='text' name='country' value='$country'>";
                            echo "<label for='phone'>Telefoon: </label><input type='text' name='phone' value='$phone'>";
                            echo "<label for='email'>Email: </label><input type='email' name='email' value='$email'>";
                            echo "<label for='description'>Beschrijving: </label><input type='textarea' name='description' value='$description'>";
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