<?php
	
	
	//võtab ja kopeerib faili sisu
	require("../../config.php");
	
	//var_dump($_GET);
	//echo "<br>";
	//var_dump($_POST);
	
	// MUUTUJAD
	
	$loginEmailError = "";
	$loginPasswordError = "";
	$signupEmailError = "";
	$signupPasswordError = "";
	$firstNameError = "";
	$lastNameError = "";
	$signupEmail = "";


	
	if (isset ($_POST["loginEmail"]) ){
		if (empty ($_POST["loginEmail"]) ){
			$loginEmailError = "Palun sisesta e-post!";		
		}
	}
	
	if (isset ($_POST["loginPassword"]) ){ 
		if (empty ($_POST["loginPassword"]) ){ 
			$loginPasswordError = "Palun sisesta parool!";		
		}
	}
	
	if (isset ($_POST["signupEmail"]) ){ 
		if (empty ($_POST["signupEmail"]) ){ 
			$signupEmailError = "See väli on kohustuslik!";		
		}else{
			//email on olemas
			$signupEmail = $_POST["signupEmail"];
		}
	}
	
	if (isset ($_POST["signupPassword"]) ){ 
		if (empty ($_POST["signupPassword"]) ){ 
			$signupPasswordError = "See väli on kohustuslik!";		
		} else {
			if (strlen($_POST["signupPassword"]) < 8){
				$signupPasswordError = "Parool peab olema vähemalt 8 tähemärki pikk!";
			}
		}
	}
	
	if (isset ($_POST["firstName"]) ){
		if (empty ($_POST["firstName"]) ){
			$firstNameError = "See väli on kohustuslik!";		
		} else {
			//The preg_match() function searches a string for pattern, returning true if the pattern exists, and false otherwise.
			if (!preg_match("/^[a-zA-Z õäöüšž-]*$/",$_POST["firstName"])) { 
				$firstNameError = "Pole nimi!"; 
			}
		}
	}
	
	if (isset ($_POST["lastName"]) ){
		if (empty ($_POST["lastName"]) ){
			$lastNameError = "See väli on kohustuslik!";		
		} else {
			if (!preg_match("/^[a-zA-Z õäöüšž-]*$/",$_POST["lastName"])) { 
				$lastNameError = "Pole nimi!"; 
			}
		}
	}
	
	$gender = "male";
	// KUI Tühi
	// $gender = "";
	
	if ( isset ( $_POST["gender"] ) ) {
		if ( empty ( $_POST["gender"] ) ) {
			$genderError = "See väli on kohustuslik!";
		} else {
			$gender = $_POST["gender"];
		}
	}
	
	// kus tean et ühtegi viga ei olnud ja saan kasutaja andmed salvestada
	
	if ( isset($_POST["signupPassword"]) &&
		isset($_POST["signupEmail"]) &&
		empty($signupEmailError) &&
		empty($signupPasswordError)
		) {
		
		echo "Salvestan...<br>";
		echo "email ".$signupEmail."<br>";
		
		$password = hash("sha512", $_POST["signupPassword"]);
		
		echo "parool ".$_POST["signupPassword"]."<br>";
		echo "räsi ".$password."<br>";
		
		//echo $serverUsername;
		
		$database = "if16_epals";
		$mysqli = new mysqli($serverHost,$serverUsername,$serverPassword,$database);
		
		//käsk
		$stmt = $mysqli->prepare("INSERT INTO user_sample (email, password) VALUES (?, ?)");
		
		//asendan küsimärgid väärtustega
		//iga muutuja kohta üks täht, mis tüüpi muutja on
		// s - string
		// i - integer
		// d - double/float komakohaga arv
		$stmt->bind_param("ss", $signupEmail, $password);
		
		if($stmt->execute()) {
			echo "salvestamine õnnestus";
		} else {
			echo "ERROR".$stmt->error;
		}
		
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Sisselogimise lehekülg</title>
	</head>
	<body>
		<h1>Logi sisse</h1>	
		
		<form method="POST">
			<input name="loginEmail" type="email" placeholder="E-post"> <?php echo $loginEmailError; ?>
			<br><br>
			
			<input name="loginPassword" type="password" placeholder="Parool"> <?php echo $loginPasswordError; ?>
			<br><br>
			
			<input type="submit" value = "Logi sisse">
		</form>
		
		<h1>Loo kasutaja</h1>	
		
		<form method="POST">
			<label>E-Post</label>
			<br>
			<input name="signupEmail" type="email" value="<?=$signupEmail;?>"> <?php echo $signupEmailError; ?>
			<br><br>
			
			<label>Parool</label>
			<br>
			<input name="signupPassword" type="password"> <?php echo $signupPasswordError; ?>
			<br><br>
			
			<label>Eesnimi</label>
			<br>
			<input name="firstName" type="text"> <?php echo $firstNameError; ?>
			<br><br>
			
			<label>Perekonnanimi</label>
			<br>
			<input name="lastName" type="text"> <?php echo $lastNameError; ?>
			<br><br>
		
			<label>Sugu:</label>
			<br><br>
			<?php if($gender == "male") { ?>
				<input type="radio" name="gender" value="male" checked> Mees<br>
			 <?php } else { ?>
				<input type="radio" name="gender" value="male" > Mees<br>
			 <?php } ?>
			 
			 <?php if($gender == "female") { ?>
				<input type="radio" name="gender" value="female" checked> Naine<br>
			 <?php } else { ?>
				<input type="radio" name="gender" value="female" > Naine<br>
			 <?php } ?>
			 
			 <?php if($gender == "other") { ?>
				<input type="radio" name="gender" value="other" checked> Muu<br>
			 <?php } else { ?>
				<input type="radio" name="gender" value="other" > Muu<br>
			 <?php } ?>
			 
			<br><br>
			<input type="submit" value = "Loo kasutaja">
		</form>
		 <!--Mvp ideeks on teha treeningu päeviku rakendus. Kasutajal on võimalik rakendusse sisestada millal treenis, kui kaua, millist lihasrühma (soovi korral ka konkreetselt harjutusi).-->
</html>