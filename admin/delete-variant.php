<?php
require_once 'config/database.php';

    // Bildnamen vorher abrufen
    // $stmt = $connection->prepare("SELECT image1, image2, image3, image4 FROM products WHERE id = ?");
    // $stmt->bind_param("i", $id);
    // $stmt->execute();
    // $result = $stmt->get_result();

    // if ($result->num_rows === 1) {
    //     $images = $result->fetch_assoc();

    //     // Produkt löschen
    //     $deleteStmt = $connection->prepare("DELETE FROM products WHERE id = ?");
    //     $deleteStmt->bind_param("i", $id);

    //     if ($deleteStmt->execute()) {
    //         // Bilder löschen (falls vorhanden)
    //         foreach ($images as $img) {
    //             if (!empty($img) && file_exists(__DIR__ . '\\images\\' . $img)) {
    //                 unlink(__DIR__ . '\\images\\' . $img);
    //             }
    //         }

    //         $_SESSION['delete-success'] = "Product successfully deleted.";
    //     } else {
    //         $_SESSION['delete-error'] = "Error occured while deleting product";
    //     }

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "SELECT image1, image2, image3, image4 FROM product_variants WHERE id = ?";
    // $test = mysqli_prepare($connection, $sql);
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        $_SESSION['delete-error'] = "SQL Error: " . $connection->error;
        header("Location: add-product-variant.php?id=" . $_POST['id']);
        exit;
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    // $variant = $stmt->get_result();
    if ($result->num_rows === 1) {

        $images = $result->fetch_assoc();
        // delete the product variant
        $deleteStmt = $connection->prepare("DELETE FROM product_variants WHERE id = ?");
        $deleteStmt->bind_param("i", $id);
        $deleteStmt->execute();
        if ($deleteStmt->execute()) {
            // Bilder löschen (falls vorhanden)
            foreach ($images as $img) {
                if (!empty($img) && file_exists(__DIR__ . '\\images\\' . $img)) {
                    unlink(__DIR__ . '\\images\\' . $img);
                }
            }
            $_SESSION['delete-success'] = "Product variant successfully deleted.";
            header("Location: index.php");
            die();
        } else {
            $_SESSION['delete-error'] = "Error occurred while deleting product variant";
            header("Location: add-product-variant.php?id=" . $id);
            exit;
        }
    } elseif ($variant->num_rows > 1) {
        // more than one variant found, please check the database
        $_SESSION['delete-error'] = "More than one variant with the same id. Please check the database.";
        header("Location: add-product-variant.php?id=" . $id);
        exit;
    } else {
        $_SESSION['delete-error'] = "variant not found!";
        header("Location: add-product-variant.php?id=" . $id);
        exit;
    }

} else {
    $_SESSION['delete-error'] = "Product-ID invalid.";
    header("Location: add-product-variant.php?id=" . $id);
    exit;
}
?>
