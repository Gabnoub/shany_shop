<?php
include 'partials/header.php';


$id = $_GET['id'] ?? null;

if (!$id) {
  echo "Product-ID is missed!";
  exit;
}

$sql = "SELECT * FROM products WHERE id = ?";
// $test = mysqli_prepare($connection, $sql);
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
  echo "Product not found!";
  exit;
}
?>

<!----==========================================  Add product Section ============================================---->
<section class="form__section">
    <div class="add__products">
        <a href="index.php" style="margin-left: 10px;">
            <button type="button" class="sub__btn cancel"><i class="uil uil-arrow-left"></i></button>
        </a>    
        <h2>Créer une variante</h2>
            <?php if (!empty($_SESSION['variant'])): ?> 
                <div class="alert">
                    <?= $_SESSION['variant'];
                    unset($_SESSION['variant']);
                    ?>
                </div>
            <?php endif; ?>

            <form action="add-product-variant-logic.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $product['id'] ?>">
            <div>
                <label class="required-label" for="color">Couleur *</label>
                <select name="color">
                    <option value='null' <?= $product['color'] === null ? 'selected' : '' ?>></option>
                    <option value="argenté" <?= $product['color'] === "argenté" ? 'selected' : '' ?>>Argenté</option>
                    <option value="doré" <?= $product['color'] === "doré" ? 'selected' : '' ?>>Doré</option>
                </select>
            </div>
            <button type="submit" name="variant_submit" class="sub__btn">Edit Product</button>
        </form>
    </div>
</section>
<?php
Include '../partials/footer.php';
?>