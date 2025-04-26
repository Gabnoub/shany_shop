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
        <h2>Modifier un Produit</h2>
            <?php if (!empty($_SESSION['edit'])): ?> 
                <div class="alert">
                    <?= $_SESSION['edit'];
                    unset($_SESSION['edit']);
                    ?>
                </div>
            <?php endif; ?>

            <form action="update-product-logic.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $product['id'] ?>">

            <div>
                <label for="category">Choisir la categorie *</label>
                <select name="category">
                    <option value=null <?= $product['category'] === null ?>></option>
                    <option value=0 <?= $product['category'] === 0 ? 'selected' : '' ?>><?= htmlspecialchars($category_1) ?></option>
                    <option value=1 <?= $product['category'] === 1 ? 'selected' : '' ?>><?= htmlspecialchars($category_2) ?></option>
                    <option value=2 <?= $product['category'] === 2 ? 'selected' : '' ?>><?= htmlspecialchars($category_3) ?></option>
                    <option value=3 <?= $product['category'] === 3 ? 'selected' : '' ?>><?= htmlspecialchars($category_4) ?></option>
                </select>

            </div>
            <div>
                <label for="en_stock">En stock *</label>
                <select name="en_stock">
                    <option value=null <?= $product['en_stock'] === null ? 'selected' : '' ?>></option>
                    <option value=0 <?= $product['en_stock'] === 0 ? 'selected' : '' ?>>Oui</option>
                    <option value=1 <?= $product['en_stock'] === 1 ? 'selected' : '' ?>>Non</option>
                </select>
            </div>
            <label class="required-label" for="title">Titre *</label>
            <input type="text" name="title" value="<?= htmlspecialchars($product['title']) ?>">
            <label for="material">Material</label>
            <input type="text" name="material" value="<?= htmlspecialchars($product['material']) ?>">
            <label for="color">Couleur</label>
            <input type="text" name="color" value="<?= htmlspecialchars($product['color']) ?>">
            <label for="size">Taille</label>
            <input type="text" name="size" value="<?= htmlspecialchars($product['size']) ?>">
            <label class="required-label" for="description1">Description 1 *</label>
            <input type="text" name="description1" value="<?= htmlspecialchars($product['description1']) ?>">
            <label for="bulletpoint1">bulletpoint 1</label>
            <input type="text" name="bulletpoint1" value="<?= htmlspecialchars($product['bulletpoint1']) ?>">
            <label for="bulletpoint2">bulletpoint 2</label>
            <input type="text" name="bulletpoint2" value="<?= htmlspecialchars($product['bulletpoint2']) ?>">
            <label for="bulletpoint3">bulletpoint 3</label>
            <input type="text" name="bulletpoint3" value="<?= htmlspecialchars($product['bulletpoint3']) ?>">
            <label for="bulletpoint4">bulletpoint 4</label>
            <input type="text" name="bulletpoint4" value="<?= htmlspecialchars($product['bulletpoint4']) ?>">
            <label class="required-label" for="description2">Description 2</label>
            <input type="text" name="description2" value="<?= htmlspecialchars($product['description2']) ?>">
            <div class="form__control">    
                <label for="image1">Update Image 1 *</label>
                <?php if (!empty($product['image1'])): ?>
                    <img src="images/<?= htmlspecialchars($product['image1']) ?>" style="height: 40px; width: 40px; object-fit:cover; margin-left: 35px;">
                <?php endif; ?>
                <input type="file" name="image1" id="image1">
                <input type="hidden" name="current_image1" value="<?= $product['image1'] ?>">

                <label for="image2">Update Image 2</label>
                <?php if (!empty($product['image2'])): ?>
                    <img src="images/<?= htmlspecialchars($product['image2']) ?>" style="height: 40px; width: 40px; object-fit:cover; margin-left: 35px;">
                <?php endif; ?>
                <input type="file" name="image2" id="image2">
                <input type="hidden" name="current_image2" value="<?= $product['image2'] ?>">

                <label for="image3">Update Image 3</label>
                <?php if (!empty($product['image3'])): ?>
                    <img src="images/<?= htmlspecialchars($product['image3']) ?>" style="height: 40px; width: 40px; object-fit:cover; margin-left: 35px;">
                <?php endif; ?>
                <input type="file" name="image3" id="image3">
                <input type="hidden" name="current_image3" value="<?= $product['image3'] ?>">

                <label for="image4">Update Image 4</label>
                <?php if (!empty($product['image4'])): ?>
                    <img src="images/<?= htmlspecialchars($product['image4']) ?>" style="height: 40px; width: 40px; object-fit:cover; margin-left: 35px;">
                <?php endif; ?>
                <input type="file" name="image4" id="image4">
                <input type="hidden" name="current_image4" value="<?= $product['image4'] ?>">
            </div>
            <label>Prix en Fcfa *</label>
            <input type="number" step="1" name="price" value="<?= htmlspecialchars($product['price']) ?>">
            <label>Rabais en Fcfa</label>
            <input type="number" step="1" name="discount" value="<?= htmlspecialchars($product['discount']) ?>">
            <button type="submit" name="edit_submit" class="sub__btn">Edit Product</button>
        </form>
    </div>
</section>
<?php
Include '../partials/footer.php';
?>