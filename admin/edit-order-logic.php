<?php
require 'config/database.php';


// Bewertung löschen
if (isset($_GET['delete_order_id'])) {

    $order_id = filter_var($_GET['delete_order_id'], FILTER_SANITIZE_NUMBER_INT);
    
    if (!$order_id) {
        $_SESSION['order_error'] = "Invalid Order-ID";
        header("Location: manage-orders.php");
        exit;
    }
    
    try {
        // Optional: Bewertungsdaten für die Protokollierung abrufen
        $get_order = $connection->prepare("SELECT id , message FROM orders WHERE id = ?");
        $get_order->bind_param("i", $order_id);
        $get_order->execute();
        $order_data = $get_order->get_result()->fetch_assoc();
        
        // Löschen der Bewertung
        $sql = "DELETE FROM orders WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $order_id);
        
        if ($stmt->execute()) {
            $_SESSION['order_success'] = "order successfully deleted";
            
        } else {
            $_SESSION['order_error'] = "Error during review delete: " . $stmt->error;
        }
    } catch (Exception $e) {
        $_SESSION['order_error'] = "Error during review delete: " . $e->getMessage();
    }
    
    header("Location: manage-orders.php");
    exit;
}

?>