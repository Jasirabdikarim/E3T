<?php
require 'constants.php';
try{
    $dbHandler = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpassword);
    $dbHandler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch(Exception $ex){
       $error = "Er is een verbinding fout met de database";
  }
  if($_SERVER["REQUEST_METHOD"] == "GET"){
    $search = filter_input(INPUT_GET, "search", FILTER_SANITIZE_SPECIAL_CHARS);
    if(isset($search)){
      try {
        $search = "%$search%";
        $stmt = $dbHandler->prepare("SELECT * FROM Talentprofile WHERE Name LIKE :search OR Description LIKE :search;");
        $stmt->bindParam("search", $search, PDO::PARAM_STR);
        $stmt->bindcolumn("Name", $name);
        $stmt->bindcolumn("Description", $description);
        $stmt->bindcolumn("TalentID", $talent);
        $stmt -> execute();
      } catch(Exception $ex){
         echo $ex;
      }
    }
  else{
    if(isset($dbHandler)){
      try{
        $stmt = $dbHandler->prepare("SELECT * FROM Talentprofile;");
        $stmt->bindcolumn("TalentName", $name);
        $stmt->bindcolumn("Description", $description);
        $stmt->bindcolumn("TalentID", $talent);
        $stmt -> execute();
      } catch(Exception $ex){
          echo $ex;
      }
    }
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
				<div id="searchform">
					<form method="GET" action="talenten.php">
						<input type="text" name="search" id="search"  placeholder="search...">
					</form>
				</div>
				
				<?php
           if(isset($error)){
              echo "<p>$error<p>";
           }
           else{
					while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
           echo "<div class ='purplebar'></div>
					    <div class ='talentprofile'>
                <img src='img/profilepic.png' alt='img' class='talentsprofileimg'>
                  <div class ='talentprofiletext'>
                    <h2>$name</h2>
                    <p>$description</p>
                    <a href='profile.php?id=$talent'>Klik hier</a>
							    </div>
					    </div>";
					}
          if($stmt->rowcount() == 0){
            echo "<div id='result'><p>Geen resultaten</p></div>";
          }
        }
				
				?>

				<div class ="purplebar"></div>
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


