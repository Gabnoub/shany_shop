<?php
require_once 'config/database.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Bildnamen vorher abrufen
    $stmt = $connection->prepare("SELECT image1, image2, image3, image4 FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $images = $result->fetch_assoc();

        // Produkt löschen
        $deleteStmt = $connection->prepare("DELETE FROM products WHERE id = ?");
        $deleteStmt->bind_param("i", $id);

        if ($deleteStmt->execute()) {
            // Bilder löschen (falls vorhanden)
            foreach ($images as $img) {
                if (!empty($img) && file_exists(__DIR__ . '\\images\\' . $img)) {
                    unlink(__DIR__ . '\\images\\' . $img);
                }
            }

            $_SESSION['delete-success'] = "Product successfully deleted.";
        } else {
            $_SESSION['delete-error'] = "Error occured while deleting product";
        }
    } else {
        $_SESSION['delete-error'] = "Product not found.";
    }
} else {
    $_SESSION['delete-error'] = "Product-ID invalid.";
}

header("Location: index.php");
exit;
?>
