<?php
require 'config/database.php';

// Bewertung hinzufügen
if (isset($_POST['submit_rating'])) {
    // Sanitize Inputs
    $product_id = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);
    $user_name = filter_var($_POST['user_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $rating = filter_var($_POST['rating'], FILTER_SANITIZE_NUMBER_INT);
    $comment = filter_var($_POST['comment'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Validiere Eingaben
    if (!$product_id) {
        $_SESSION['review_error'] = "Produkt-ID ist erforderlich";
    } elseif (!$user_name) {
        $_SESSION['review_error'] = "Name ist erforderlich";
    } elseif (!$rating || $rating < 1 || $rating > 5) {
        $_SESSION['review_error'] = "Bewertung (1-5 Sterne) ist erforderlich";
    } else {
        // Prüfe, ob das Produkt existiert
        $check_product = $connection->prepare("SELECT id FROM products WHERE id = ?");
        $check_product->bind_param("i", $product_id);
        $check_product->execute();
        $product_result = $check_product->get_result();
        
        if ($product_result->num_rows === 0) {
            $_SESSION['review_error'] = "Das angegebene Produkt existiert nicht";
        } else {
            // Speichere die Bewertung in der Datenbank
            $sql = "INSERT INTO product_ratings (product_id, user_name, rating, comment) VALUES (?, ?, ?, ?)";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("isis", $product_id, $user_name, $rating, $comment);
            
            if ($stmt->execute()) {
                $_SESSION['review_success'] = "Bewertung wurde erfolgreich hinzugefügt";
                
                // Hole slug für die Weiterleitung
                $get_slug = $connection->prepare("SELECT slug FROM products WHERE id = ?");
                $get_slug->bind_param("i", $product_id);
                $get_slug->execute();
                $slug_result = $get_slug->get_result()->fetch_assoc();
                
                // Leite zur Produktseite zurück
                if ($slug_result && !empty($slug_result['slug'])) {
                    header("Location: " . ROOT_URL . "products/" . $slug_result['slug'] . "?rating_success=1");
                } else {
                    header("Location: " . ROOT_URL . "index.php");
                }
            } else {
                $_SESSION['review_error'] = "Fehler beim Speichern der Bewertung: " . $stmt->error;
                
                // Bei Fehler zurück zum Produkt
                $get_slug = $connection->prepare("SELECT slug FROM products WHERE id = ?");
                $get_slug->bind_param("i", $product_id);
                $get_slug->execute();
                $slug_result = $get_slug->get_result()->fetch_assoc();
                
                if ($slug_result && !empty($slug_result['slug'])) {
                    header("Location: " . ROOT_URL . "products/" . $slug_result['slug'] . "?rating_error=1");
                } else {
                    header("Location: " . ROOT_URL . "index.php");
                }
            }
            exit;
        }
    }
    
    // Bei Validierungsfehlern zurück zur vorherigen Seite
    if (isset($_SESSION['review_error'])) {
        // Speichere eingegebene Daten in Session für Formular-Wiederherstellung
        $_SESSION['review_data'] = [
            'user_name' => $user_name,
            'rating' => $rating,
            'comment' => $comment
        ];
        
        if (isset($_SERVER['HTTP_REFERER'])) {
            header("Location: " . $_SERVER['HTTP_REFERER']);
        } else {
            header("Location: " . ROOT_URL . "index.php");
        }
        exit;
    }
}

// Bewertung bearbeiten (für Admin-Bereich)
if (isset($_POST['edit_review_submit'])) {
    // Nur für authentifizierte Administratoren
    // if (!isset($_SESSION['user_is_admin']) || $_SESSION['user_is_admin'] !== true) {
    //     $_SESSION['review_error'] = "Unzureichende Berechtigung";
    //     header("Location: " . ROOT_URL . "index.php");
    //     exit;
    // }
    
    $review_id = filter_var($_POST['review_id'], FILTER_SANITIZE_NUMBER_INT);
    $status = filter_var($_POST['status'], FILTER_SANITIZE_FULL_SPECIAL_CHARS); // z.B. "approved", "rejected"
    
    if (!$review_id) {
        $_SESSION['review_error'] = "Bewertungs-ID ist erforderlich";
    } else {
        $sql = "UPDATE product_ratings SET status = ? WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("si", $status, $review_id);
        
        if ($stmt->execute()) {
            $_SESSION['review_success'] = "Bewertungsstatus wurde aktualisiert";
            header("Location: " . ROOT_URL . "admin/manage_reviews.php");
        } else {
            $_SESSION['review_error'] = "Fehler beim Aktualisieren des Bewertungsstatus: " . $stmt->error;
            header("Location: " . ROOT_URL . "admin/manage_reviews.php");
        }
        exit;
    }
}

// Bewertung löschen (für Admin-Bereich)
if (isset($_GET['delete_review_id'])) {
    // Nur für authentifizierte Administratoren
    // if (!isset($_SESSION['user_is_admin']) || $_SESSION['user_is_admin'] !== true) {
    //     $_SESSION['review_error'] = "Unzureichende Berechtigung";
    //     header("Location: " . ROOT_URL . "index.php");
    //     exit;
    // }
    
    $review_id = filter_var($_GET['delete_review_id'], FILTER_SANITIZE_NUMBER_INT);
    
    if (!$review_id) {
        $_SESSION['review_error'] = "Ungültige Bewertungs-ID";
    } else {
        $sql = "DELETE FROM product_ratings WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $review_id);
        
        if ($stmt->execute()) {
            $_SESSION['review_success'] = "Bewertung wurde gelöscht";
        } else {
            $_SESSION['review_error'] = "Fehler beim Löschen der Bewertung: " . $stmt->error;
        }
    }
    
    header("Location: " . ROOT_URL . "admin/manage_reviews.php");
    exit;
}
?>