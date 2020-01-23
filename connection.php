<!-- -----------------Ici ma page de connection -->
<?php
session_start();
//si l utilisateur est connecté je le redirige
if(isset($_SESSION['connect'])){
	header('location: index.php');
	exit();
}

require('src/connection.php');


if(!empty($_POST['email']) && !empty($_POST['password'])){

	
	$email 		= $_POST['email'];
	$password 	= $_POST['password'];
	$error		= 1;

	
	$password = "aq1".sha1($password."1254")."25";

	echo $password;
//Ici je vais chercher les donnée de l'utilisateur
	$req = $db->prepare('SELECT * FROM users WHERE email = ?');
	$req->execute(array($email));

	while($user = $req->fetch()){
 //je verifie si c'est le bon pass
		if($password == $user['password']){
//je passe ici a 0 pour que la redirection du bas ne sois pas executer
			$error = 0;
// si ok J'utilise les sessions et  je crée une session pour l utilisateur
			$_SESSION['connect'] = 1;
			$_SESSION['pseudo']	 = $user['pseudo'];
            //pour la connection auto + + params de secu
			if(isset($_POST['connect'])) {
				setcookie('log', $user['secret'], time() + 365*24*3600, '/', null, false, true);
			}

			header('location: connection.php?success=1');
			exit();
		}

	}
        //sinon je le redirige

	if($error == 1){
		header('location: connection.php?error=1');
		exit();
	}

}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Connexion</title>
	<link rel="stylesheet" type="text/css" href="asset/styles.css">
</head>
<body>
	<header>
		<h1>Connexion</h1>
	</header>

	<div class="container">
		<p id="info">Bienvenue sur mon site,si vous n'êtes pas inscrit, <a href="index.php">inscrivez-vous.</a></p>
	 	
		<?php
			if(isset($_GET['error'])){
				echo'<p id="error">Nous ne pouvons pas vous authentifier.</p>';
			}
			else if(isset($_GET['success'])){
				echo'<p id="success">Vous êtes maintenant connecté.</p>';
			}
		?>

	 	<div id="form">
			<form method="POST" action="connection.php">
				<table>
					<tr>
						<td>Email</td>
						<td><input type="email" name="email" placeholder="Ton mail" required></td>
					</tr>
					<tr>
						<td>Mot de passe</td>
						<td><input type="password" name="password" placeholder="Ton pass" required ></td>
					</tr>
				</table>
				<p><label><input type="checkbox" name="connect" checked>Connexion automatique</label></p>
				<div id="button">
					<button type='submit'>Connexion</button>
				</div>
			</form>
		</div>
	</div>
</body>
</html>