<?php
require 'config/constants.php';
session_start();            // Session starten
session_unset();            // Alle Session-Variablen löschen
session_destroy();          // Die gesamte Session zerstören
unset($_SESSION['user_id']); 
header("Location: " . ROOT_URL . ""); // Zur Login-Seite weiterleiten
exit;
?>
