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
            <h1>Accounts</h1>
            <table>
                <tr>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Bewerk</th>
                    <th>Verwijder</th>
                </tr>
                <?php
                    try{
                        $dbHandler = new PDO("mysql:host={$dbhost};dbname={$dbname};charset=utf8;", "{$dbuser}", "{$dbpassword}");
                    }
                    catch (Exception $ex){
                        $error = "Er is een verbindings fout met de database";
                    }
                    if(isset($dbHandler)){
                        $stmt = $dbHandler->prepare("SELECT * FROM `Login`");
                        $stmt->bindColumn("LoginID", $id);
                        $stmt->bindColumn("Username", $username);
                        $stmt->bindColumn("Role", $role);
                        try {
                            $stmt->execute();
                        }
                        catch(Exception $ex){
                            $error = "Er is een verbindings fout met de database";
                        }
                        if(!isset($error)){
                            while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
                                echo "<tr>
                                        <td>$username</td>
                                        <td>$role</td>
                                        <td><a href='editaccount.php?id={$id}'>Bewerk</a></td>
                                        <td><a href='deleteaccount.php?id={$id}'>Verwijder</a></td>
                                    </tr>";
                            }
                        }
                    }
                ?>
            </table>
        </main>
    </body>
</html>