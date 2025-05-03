<?php
require_once 'config/database.php';


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "SELECT * FROM product_variants WHERE id = ?";
    // $test = mysqli_prepare($connection, $sql);
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        $_SESSION['delete-error'] = "SQL Error: " . $connection->error;
        header("Location: add-product-variant.php?id=" . $_POST['id']);
        exit;
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $variant = $stmt->get_result();
    if ($variant->num_rows === 1) {
        // delete the product variant
        $deleteStmt = $connection->prepare("DELETE FROM product_variants WHERE id = ?");
        $deleteStmt->bind_param("i", $id);
        $deleteStmt->execute();
        if ($deleteStmt->execute()) {
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
