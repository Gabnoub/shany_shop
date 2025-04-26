<?php
require 'config/database.php';


// Bewertung löschen
if (isset($_GET['delete_review_id'])) {

    $review_id = filter_var($_GET['delete_review_id'], FILTER_SANITIZE_NUMBER_INT);
    
    if (!$review_id) {
        $_SESSION['review_error'] = "Invalid Review-ID";
        header("Location: manage-reviews.php");
        exit;
    }
    
    try {
        // Optional: Bewertungsdaten für die Protokollierung abrufen
        $get_review = $connection->prepare("SELECT product_id, user_name FROM product_ratings WHERE id = ?");
        $get_review->bind_param("i", $review_id);
        $get_review->execute();
        $review_data = $get_review->get_result()->fetch_assoc();
        
        // Löschen der Bewertung
        $sql = "DELETE FROM product_ratings WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $review_id);
        
        if ($stmt->execute()) {
            $_SESSION['review_success'] = "Review successfully deleted";
            
            // Optional: Protokollierung der Löschung
            if ($review_data) {
                $admin_id = $_SESSION['user_id'] ?? 0;
                $log_sql = "INSERT INTO activity_log (user_id, action, entity_type, entity_id, details) 
                           VALUES (?, 'delete', 'review', ?, ?)";
                $log_stmt = $connection->prepare($log_sql);
                $details = "Bewertung für Produkt ID: {$review_data['product_id']} von {$review_data['user_name']} gelöscht";
                $log_stmt->bind_param("iis", $admin_id, $review_id, $details);
                $log_stmt->execute();
            }
        } else {
            $_SESSION['review_error'] = "Error during review delete: " . $stmt->error;
        }
    } catch (Exception $e) {
        $_SESSION['review_error'] = "Error during review delete: " . $e->getMessage();
    }
    
    header("Location: manage-reviews.php");
    exit;
}

?>