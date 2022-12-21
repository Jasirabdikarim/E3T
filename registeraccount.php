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
                        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
                        $role = filter_input(INPUT_POST, "role", FILTER_SANITIZE_SPECIAL_CHARS);
                        if(!$username || !$password || !$role){
                            $error = "Vul alle velden in";
                        }
                        else{
                            if($role == "admin" || $role == "talent"){
                                try{
                                    $dbHandler = new PDO("mysql:host={$dbhost};dbname={$dbname};charset=utf8;", "{$dbuser}", "{$dbpassword}");
                                }
                                catch (Exception $ex){
                                    $error = "Er is een verbindings fout met de database";
                                }
                                if(isset($dbHandler)){
                                    $stmt = $dbHandler->prepare("SELECT * FROM `Login` WHERE username = :username");
                                    $stmt->bindParam("username", $username, PDO::PARAM_STR);
                                    try {
                                        $stmt->execute();
                                    }
                                    catch(Exception $ex){
                                        $error = "Er is een verbindings fout met de database";
                                    }
                                    if(!isset($error)){
                                        if($stmt->rowCount() == 0){
                                            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                                            $stmt = $dbHandler->prepare("INSERT INTO `Login` (Username, Password, Role) VALUES (:username, :hashed_password, :role)");
                                            $stmt->bindParam("username", $username, PDO::PARAM_STR);
                                            $stmt->bindParam("hashed_password", $hashed_password, PDO::PARAM_STR);
                                            $stmt->bindParam("role", $role, PDO::PARAM_STR);
                                            try {
                                                $stmt->execute();
                                            }
                                            catch(Exception $ex){
                                                $error = "Er is een verbindings fout met de database";
                                            }
                                            if(!isset($error)){
                                                if($role == "talent"){
                                                    $succes = "<a href='createprofile.php'>Account aangemaakt, klik hier om het talentenprofiel aan te maken</a>";
                                                }
                                                if($role == "admin"){
                                                    $succes = "Account aangemaakt";
                                                }
                                            }
                                        }
                                        else{
                                            $error = "Er bestaat al een user met dezelfde username";
                                        }
                                    }
                                }
                            }
                            else{
                                $error = "Vul alle velden in";
                            }
                        }
                    }

                    if($_SESSION['Role'] == "admin"){
                        ?>
                            <form action="registeraccount.php" method="POST">
                                <div class="formFieldSpacing">
                                    <label for="username">Username: </label>
                                    <input class="inputField" type="text" name="username">
                                </div>
                                <div class="formFieldSpacing">
                                    <label for="password">Password: </label>
                                    <input class="inputField" type="password" name="password">
                                </div>
                                <div class="formFieldSpacing">
                                    <label for="role">Role: </label>
                                    <select class="inputField" name="role">
                                        <option>talent</option>
                                        <option>admin</option>
                                    </select>
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