<?php
//initialise la session
session_start();
//désactive la session
session_unset();
//ici je la détruit
session_destroy();
setcookie('log', '', time()-3444, '/', null, false, true);
//et je redirige vers la page inscription
header('location: index.php');