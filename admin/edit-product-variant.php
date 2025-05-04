<?php
include 'partials/header.php';


$id = $_GET['id'] ?? null;

if (!$id) {
  echo "Product-variant-ID is missed!";
  exit;
}

$sql = "SELECT * FROM product_variants WHERE id = ?";
// $test = mysqli_prepare($connection, $sql);
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$product_variants = $stmt->get_result()->fetch_assoc();

if (!$product_variants) {
  echo "Product variant not found!";
  exit;
}
?>

<!----==========================================  Add product Section ============================================---->
<section class="form__section">
    <div class="add__products">
        <a href="index.php" style="margin-left: 10px;">
            <button type="button" class="sub__btn cancel"><i class="uil uil-arrow-left"></i></button>
        </a>    
        <h2>Modifier une variante</h2>
            <?php if (!empty($_SESSION['variant'])): ?> 
                <div class="alert">
                    <?= $_SESSION['variant'];
                    unset($_SESSION['variant']);
                    ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($_SESSION['delete-error'])): ?> 
                <div class="alert">
                    <?= $_SESSION['delete-error'];
                    unset($_SESSION['delete-error']);
                    ?>
                </div>
            <?php endif; ?>

            <form action="edit-product-variant-logic.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $product_variants['id'] ?>">
                <input type="hidden" name="product_id" value="<?= $product_variants['product_id'] ?>">
                <div>
                    <label class="required-label" for="en_stock">En stock *</label>
                    <select name="en_stock" value="<?= $en_stock ?>">
                        <option value=null <?= $product_variants['en_stock'] === null ? 'selected' : '' ?>></option>
                        <option value=0 <?= $product_variants['en_stock'] === 0 ? 'selected' : '' ?>>Oui</option>
                        <option value=1 <?= $product_variants['en_stock'] === 1 ? 'selected' : '' ?>>Non</option>
                    </select>
                </div>
                <div class="form__control">    
                    <label for="image1">Update Image 1 *</label>
                    <?php if (!empty($product_variants['image1'])): ?>
                        <img src="images/<?= htmlspecialchars($product_variants['image1']) ?>" style="height: 40px; width: 40px; object-fit:cover; margin-left: 35px;">
                    <?php endif; ?>
                    <input type="file" name="image1" id="image1">
                    <input type="hidden" name="current_image1" value="<?= $product_variants['image1'] ?>">

                    <label for="image2">Update Image 2</label>
                    <?php if (!empty($product_variants['image2'])): ?>
                        <img src="images/<?= htmlspecialchars($product_variants['image2']) ?>" style="height: 40px; width: 40px; object-fit:cover; margin-left: 35px;">
                    <?php endif; ?>
                    <input type="file" name="image2" id="image2">
                    <input type="hidden" name="current_image2" value="<?= $product_variants['image2'] ?>">

                    <label for="image3">Update Image 3</label>
                    <?php if (!empty($product_variants['image3'])): ?>
                        <img src="images/<?= htmlspecialchars($product_variants['image3']) ?>" style="height: 40px; width: 40px; object-fit:cover; margin-left: 35px;">
                    <?php endif; ?>
                    <input type="file" name="image3" id="image3">
                    <input type="hidden" name="current_image3" value="<?= $product_variants['image3'] ?>">

                    <label for="image4">Update Image 4</label>
                    <?php if (!empty($product_variants['image4'])): ?>
                        <img src="images/<?= htmlspecialchars($product_variants['image4']) ?>" style="height: 40px; width: 40px; object-fit:cover; margin-left: 35px;">
                    <?php endif; ?>
                    <input type="file" name="image4" id="image4">
                    <input type="hidden" name="current_image4" value="<?= $product_variants['image4'] ?>">
                </div>
                <button type="submit" name="variant_submit" class="sub__btn">Modifier</button>
            </form>
    </div>
    
</section>

<?php
Include '../partials/footer.php';
?>