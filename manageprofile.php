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
            <div id="manageLayout">
                <h1>Profielen</h1>
                <table id="manageTable">
                    <tr>
                        <th class='tablePadding'>Naam</th>
                        <th class='tablePadding'>Land</th>
                        <th class='tablePadding'>Telefoon</th>
                        <th class='tablePadding'>Email</th>
                        <th class='tablePadding'>Beschrijving</th>
                    </tr>
                    <?php
                    try{
                        $dbHandler = new PDO("mysql:host={$dbhost};dbname={$dbname};charset=utf8;", "{$dbuser}", "{$dbpassword}");
                    }
                    catch (Exception $ex){
                        $error = "Er is een verbindings fout met de database";
                    }
                    if(isset($dbHandler)){
                        $stmt = $dbHandler->prepare("SELECT * FROM `Talentprofile`");
                        $stmt->bindColumn("TalentID", $id, PDO::PARAM_INT);
                        $stmt->bindColumn("Name", $name, PDO::PARAM_STR);
                        $stmt->bindColumn("Country", $country, PDO::PARAM_STR);
                        $stmt->bindColumn("Phone", $phone, PDO::PARAM_STR);
                        $stmt->bindColumn("Email", $email, PDO::PARAM_STR);
                        $stmt->bindColumn("Description", $description, PDO::PARAM_STR);
                        try {
                            $stmt->execute();
                        }
                        catch(Exception $ex){
                            $error = "Er is een verbindings fout met de database";
                        }
                        if(!isset($error)){
                            while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
                                echo "<tr>
                                                    <td class='tablePadding'>$name</td>
                                                    <td class='tablePadding'>$country</td>
                                                    <td class='tablePadding'>$phone</td>
                                                    <td class='tablePadding'>$email</td>
                                                    <td class='tablePadding'>$description</td>
                                                    <td class='tablePadding'><a href='editprofile.php?id={$id}'>Bewerk</a></td>
                                                    <td class='tablePadding'><a href='deleteprofile.php?id={$id}'>Verwijder</a></td>
                                                </tr>";
                            }
                        }
                    }
                    if(isset($error)){
                        echo "<p class='error'>$error</p>";
                    }
                    $dbHandler = null;
                    ?>
                </table>
            </div>
        </main>
    </body>
</html>