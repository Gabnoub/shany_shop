<?php
require_once 'config/database.php';

if (isset($_POST['variant_submit']) && isset($_POST['id'] )) {
    $id = (int) $_POST['id'];
    $en_stock = filter_var($_POST['en_stock'], FILTER_SANITIZE_NUMBER_INT);

    // validate input
    if ($en_stock === 'null') {
        $_SESSION['variant'] = "Stock status is required";
    } else {
        // Proceed with updating stock status of the variant
        $sql = "UPDATE product_variants SET en_stock = ? WHERE id = ?";
        $stmt = $connection->prepare($sql);
        if (!$stmt) {
            $_SESSION['variant'] = "SQL Error: " . $connection->error;
            header("Location: edit-product-variant.php?id=" . $_POST['id']);
            exit;
        }
        
        $stmt->bind_param(
            "ii",
            $en_stock, $id
        );
    
        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['variant-success'] = "Product variant successfully updated.";
            header("Location: index.php");
            die();
        } else {
            $_SESSION['variant'] = "Error occurred while creating product variant.";
            header("Location: edit-product-variant.php?id=" . $_POST['id']);
            exit;
        }
        exit;
    }

    if (isset($_SESSION['variant'])) {
        header("Location: edit-product-variant.php?id=" . $_POST['id']);
        exit;
    }
}
?>
