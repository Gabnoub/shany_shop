<?php
require 'config/database.php';


// POST-Daten abrufen
$message = filter_var($_POST['message'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';


// Daten in die Datenbank einfügen
if(isset($message)){
  $sql = "INSERT INTO orders (message) VALUES (?)";
  $stmt = $connection->prepare($sql);
  $stmt->bind_param("s", $message);
}


if ($stmt->execute()) {
  echo "Bestellung erfolgreich gespeichert.";
} else {
  echo "Fehler beim Speichern der Bestellung: " . $stmt->error;
}

$stmt->close();
$connection->close();

?>