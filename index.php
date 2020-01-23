<?php
// --------------------------Ici ma page d'inscription
session_start();

require("src/connection.php");
 //je verifie si la variable est vide ou rempli
	if(!empty($_POST['pseudo']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_confirm'])){
 
	
 
		$pseudo       = $_POST['pseudo'];
		$email        = $_POST['email'];
		$password     = $_POST['password'];
		$pass_confirm = $_POST['password_confirm'];
 
    //Ici si les mots de passes sont pas identique je renvoi une erreur 
		if($password != $pass_confirm){
				header('Location: index.php?error=1&pass=1');
					exit();
 
		}
 
		//Je vérifie si le mail est déjà utilisé 
		$req = $db->prepare("SELECT count(*) as numberEmail FROM users WHERE email = ?");
		$req->execute(array($email));
 
		while($email_verification = $req->fetch()){
			if($email_verification['numberEmail'] != 0) {
				header('location: index.php?error=1&email=1');
				exit();
 			}
		}
 
		  // HASH pour un identifiant unique
		 $secret = sha1($email).time();
		 //Je réencrypte
		$secret = sha1($secret).time().time();

 		$password = "aq1".sha1($password."1254")."25";
 
		//Ici requete d'envoi à la base de donnée
 		$req = $db->prepare("INSERT INTO users(pseudo, email, password, secret) VALUES(?,?,?,?)");
		$value = $req->execute(array($pseudo, $email, $password, $secret));
			
		header('location: index.php?success=1');
		exit();
 
 	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>PHP et MySQL : la formation ULTIME</title>
	<link rel="icon" type="image/png" href="/logo.png">
	<link rel="stylesheet" type="text/css" href="asset/styles.css">
</head>
 
<body>
	<header>
		<h1>Inscription</h1>
	</header>

	<div class="container">

		<?php
		//Si connecté il m'affiche plus bas l utilisateur connecter
		if(!isset($_SESSION['connect'])){ ?>

		<p id="info">Bienvenue sur mon site, pour en voir plus, inscrivez-vous. Sinon, <a href="connection.php">Connectez-vous.</a></p>

		<?php
		    //Si pas le meme mot de passe j'envoie un echo erreur
			if(isset($_GET['error'])){
		 
				if(isset($_GET['pass'])){
					echo '<p id="error">Les mots de passe ne correspondent pas.</p>';
				}  //J'envoi un echo erreur pour le mail
				else if(isset($_GET['email'])){
					echo '<p id="error">Cette adresse email est déjà utilisée.</p>';
				}
			}//sinon j'envoi ok
			else if(isset($_GET['success'])){
				echo '<p id="success">Inscription prise correctement en compte.</p>';
			}
		 
		?>
	 
	 	<div id="form">
			<form method="POST" action="index.php">
				<table>
					<tr>
						<td>Pseudo</td>
						<td><input type="text" name="pseudo" placeholder="Entre un pseudo" required></td>
					</tr>
					<tr>
						<td>Email</td>
						<td><input type="email" name="email" placeholder="Entre ton mail" required></td>
					</tr>
					<tr>
						<td>Mot de passe</td>
						<td><input type="password" name="password" placeholder="Entre ton pass" required ></td>
					</tr>
					<tr>
						<td>Retaper mot de passe</td>
						<td><input type="password" name="password_confirm" placeholder="Confirm ton pass" required></td>
					</tr>
				</table>
				<div id="button">
					<button type='submit'>Inscription</button>
				</div>
			</form>
		</div>

		<?php } else { ?>
		<!-- Ici je met l utilisateur connecté -->
		<p id="info">
			Bonjour <?= $_SESSION['pseudo'] ?><br>
			<a href="disconnection.php">Déconnexion</a>
		</p>

		<?php } ?>

	</div>
</body>
</html>