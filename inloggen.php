<?php
    session_start();
    require 'constants.php';

    if(isset($_SESSION['Username'])){
        header("Location: account.php");
    }

    if($_SERVER['REQUEST_METHOD'] === "POST"){
        $username = filter_input(INPUT_POST, "user", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
        if(!$username || !$password){
            $error = "Voer uw gebruikersnaam en wachtwoord in";
        }
        else{
            try{
                $dbHandler = new PDO ("mysql:host={$dbhost};dbname={$dbname};charset=utf8;", "{$dbuser}", "{$dbpassword}");
            }
            catch (Exception $ex){
                echo $ex;
            }
            if(isset($dbHandler)){
                $stmt = $dbHandler->prepare("SELECT * FROM `Login` WHERE Username = :username;");
                $stmt->bindParam("username", $username, PDO::PARAM_STR);
                $stmt->bindColumn("Password", $hashed, PDO::PARAM_STR);
                $stmt->bindColumn("Role", $role, PDO::PARAM_STR);
                $stmt->bindColumn("LoginID", $id, PDO::PARAM_STR);
                $stmt->execute();
                $stmt->fetch(PDO::FETCH_ASSOC);
                if(!isset($hashed)){
                    $error = "Onjuiste gebruikersnaam en/of wachtwoord";
                }
                else{
                    if(password_verify($password, $hashed)){
                        $_SESSION['Username'] = $username;
                        $_SESSION['LoginID'] = $id;
                        $_SESSION['Role'] = $role;
                        header("Location: account.php");
                    }
                    else{
                        $error = "Onjuiste gebruikersnaam en/of wachtwoord";
                    }
                }
            }
        }
    }
    $dbHandler = null;
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
                <div id="login">
                    <h1>Aanmelden</h1>
                    <form action="inloggen.php" method="POST" enctype="multipart/form-data">
                        <p><label for="user">Gebruikersnaam</label></p>
                        <p><input type="text" name="user" id="user" required></p>
                        <p><label for="password">Wachtwoord</label></p>
                        <p><input type="password" name="password" id="password" required></p>
                        <p><input type="submit" name="submit" value="Aanmelden" class="submit"></p>
                    </form>
                    <?php
                        if(isset($error)){
                            echo "<p>{$error}</p>";
                        }
                    ?>
            </main>

            <footer>
                <h1>test</h1>
            </footer>
            <div id="subfooter">
                <p>Privacy Policy  l  Algemene voorwaarden  l  Disclaimer  l  Cookies</p>
            </div>
        </body>
    </html>