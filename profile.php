<?php
    session_start();
    require 'constants.php';
    if(isset($_GET['id'])){
        $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_SPECIAL_CHARS);
        try{
            $dbHandler = new PDO("mysql:host={$dbhost};dbname={$dbname};charset=utf8;", "{$dbuser}", "{$dbpassword}");
        }
        catch (Exception $ex){
            $error = "Er is een verbindings fout met de database";
            $name = "Profiel";
        }
        if(!isset($error)){
            $stmt = $dbHandler->prepare("SELECT * FROM `Talentprofile` WHERE TalentID = :id");
            $stmt->bindParam("id", $id, PDO::PARAM_INT);
            $stmt->bindColumn("TalentName", $name, PDO::PARAM_STR);
            $stmt->bindColumn("Country", $country, PDO::PARAM_STR);
            $stmt->bindColumn("Phone", $phone, PDO::PARAM_STR);
            $stmt->bindColumn("Email", $email, PDO::PARAM_STR);
            $stmt->bindColumn("Description", $description, PDO::PARAM_STR);
            try{
                $stmt->execute();
            }
            catch (Exception $ex){
                $name = "Profiel";
                $error = "Er is een verbindings fout met de database";
            }
            if(!isset($error)){
                if($stmt->rowCount() == 0){
                    $name = "Profiel";
                    $error = "Geen account gevonden";
                }
                else{
                    $stmt->fetch(PDO::FETCH_ASSOC);
                }
            }
        }
    }
    else{
        header("Location: talenten.php");
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
            <div id="accountMenu">
                <h1><?=$name ?></h1>
                <ul>
                    <?php
                        if(isset($error)){
                            echo "<p>$error</p>";
                        }
                        else{
                            echo "<img src='img/profilepic.png'alt='profielfoto'>";
                            echo "<ul><li>Email: $email</li><li>Telefoon: $phone</li><li>Land: $country</li></ul>";
                            echo "<p>$description</p>";
                        }
                    ?>
                </ul>
            </div>
        </main>
    </body>
</html>