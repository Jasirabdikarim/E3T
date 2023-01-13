<?php
    session_start();
    if(!isset($_SESSION['Username'])){
        header("Location: inloggen.php");
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
                    <h1>Account</h1>
                    <ul>
                        <?php
                            if($_SESSION['Role'] == "admin"){
                                echo "<li><a href='registeraccount.php'>Account aanmaken</a></li>";
                                echo "<li><a href='manageaccounts.php'>Accounts beheren</a></li>";
                            }
                        ?>
                    </ul>
                </div>
            </main>
        </body>
    </html>