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
                <h1>Registreer</h1>
                <?php
                if($_SERVER['REQUEST_METHOD'] == "POST"){
                    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
                    $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
                    $country = filter_input(INPUT_POST, "country", FILTER_SANITIZE_SPECIAL_CHARS);
                    $phone = filter_input(INPUT_POST, "phone", FILTER_SANITIZE_SPECIAL_CHARS);
                    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
                    $description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_SPECIAL_CHARS);
                    if(!$username || !$name || !$country || !$phone || !$email || !$description){
                        $error = "Vul alle velden in";
                    }
                    else{
                        try{
                            $dbHandler = new PDO("mysql:host={$dbhost};dbname={$dbname};charset=utf8;", "{$dbuser}", "{$dbpassword}");
                        }
                        catch (Exception $ex){
                            $error = "Er is een verbindings fout met de database";
                        }
                        if(isset($dbHandler)){
                            $stmt = $dbHandler->prepare("SELECT * FROM `Login` WHERE username = :username");
                            $stmt->bindParam("username", $username, PDO::PARAM_STR);
                            $stmt->bindColumn("TalentID", $talentID, PDO::PARAM_STR);
                            $stmt->bindColumn("LoginID", $LoginID, PDO::PARAM_STR);
                            try {
                                $stmt->execute();
                                $stmt->fetch(PDO::FETCH_ASSOC);
                            }
                            catch(Exception $ex){
                                $error = "Er is een verbindings fout met de database";
                            }
                            if(!isset($error)){
                                if($stmt->rowCount() == 1){
                                    $stmt = $dbHandler->prepare("SELECT * FROM `Talentprofile` WHERE TalentID = :TalentID");
                                    $stmt->bindParam("TalentID", $talentID, PDO::PARAM_STR);
                                    try {
                                        $stmt->execute();
                                    }
                                    catch(Exception $ex){
                                        $error = "Er is een verbindings fout met de database";
                                    }
                                    if(!isset($error)){
                                        if($stmt->rowCount() == 0){
                                            $stmt = $dbHandler->prepare("INSERT INTO `Talentprofile` (LoginID, Name, Country, Phone, Email, Description) VALUES (:LoginID, :Name, :Country, :Phone, :Email, :Description)");
                                            $stmt->bindParam("LoginID", $LoginID, PDO::PARAM_STR);
                                            $stmt->bindParam("Name", $name, PDO::PARAM_STR);
                                            $stmt->bindParam("Country", $country, PDO::PARAM_STR);
                                            $stmt->bindParam("Phone", $phone, PDO::PARAM_STR);
                                            $stmt->bindParam("Email", $email, PDO::PARAM_STR);
                                            $stmt->bindParam("Description", $description, PDO::PARAM_STR);
                                            try {
                                                $stmt->execute();
                                            }
                                            catch(Exception $ex){
                                                $error = "Er is een verbindings fout met de database";
                                            }
                                            if(!isset($error)){
                                                $stmt = $dbHandler->prepare("SELECT * FROM `Talentprofile` WHERE LoginID = :LoginID");
                                                $stmt->bindParam("LoginID", $LoginID, PDO::PARAM_STR);
                                                $stmt->bindColumn("TalentID", $talentID, PDO::PARAM_STR);
                                                try {
                                                    $stmt->execute();
                                                    $stmt->fetch(PDO::FETCH_ASSOC);
                                                }
                                                catch(Exception $ex){
                                                    $error = "Er is een verbindings fout met de database";
                                                }
                                                if(!isset($error)){
                                                    $stmt = $dbHandler->prepare("UPDATE `Login` SET TalentID=:TalentID WHERE LoginID = :LoginID;");
                                                    $stmt->bindParam("LoginID", $LoginID, PDO::PARAM_STR);
                                                    $stmt->bindParam("TalentID", $talentID, PDO::PARAM_STR);
                                                    try {
                                                        $stmt->execute();
                                                        $stmt->fetch(PDO::FETCH_ASSOC);
                                                    }
                                                    catch(Exception $ex){
                                                        $error = "Er is een verbindings fout met de database";
                                                    }
                                                    if(!isset($error)){
                                                        $succes = "Talentenprofiel is aangemaakt";
                                                    }
                                                    else{
                                                        $error = "Er is een verbindings fout met de database";
                                                    }
                                                }
                                                else{
                                                    $error = "Er is een verbindings fout met de database";
                                                }
                                            }
                                        }
                                        else{
                                            $error = "Er bestaat al een talentenprofiel voor deze gebruiker";
                                        }
                                    }
                                }
                                else{
                                    $error = "Er bestaat geen user met dezelfde username";
                                }
                            }
                        }
                    }
                }

                if($_SESSION['Role'] == "admin"){
                    ?>
                    <form action="registertalent.php" method="POST">
                        <div class="formFieldSpacing">
                            <label for="username">Username: </label>
                            <input class="inputField" type="text" name="username">
                        </div>
                        <div class="formFieldSpacing">
                            <label for="name">Naam: </label>
                            <input class="inputField" type="text" name="name">
                        </div>
                        <div class="formFieldSpacing">
                            <label for="country">Land: </label>
                            <input class="inputField" type="text" name="country">
                        </div>
                        <div class="formFieldSpacing">
                            <label for="phone">Telefoonnummer: </label>
                            <input class="inputField" type="text" name="phone">
                        </div>
                        <div class="formFieldSpacing">
                            <label for="email">Email: </label>
                            <input class="inputField" type="email" name="email">
                        </div>
                        <div class="formFieldSpacing">
                            <label for="description">Beschrijving: </label>
                            <textarea name="description" id="description"></textarea>
                        </div>
                        <div class="formFieldSpacing">
                            <input class="submit" type="submit" value="Registreer">
                        </div>
                    </form>
                    <?php
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