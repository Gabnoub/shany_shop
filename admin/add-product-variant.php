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
        <h2>Créer/Modifier une variante</h2>
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

            <form action="add-product-variant-logic.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $product['id'] ?>">
            <input type="hidden" name="product_color" value="<?= $product['color'] ?>">
            <div>
                <label class="required-label" for="color">Couleur *</label>
                <select name="color">
                    <option value='null' <?= $product['color'] === null ? 'selected' : '' ?>></option>
                    <option value="argenté" <?= $product['color'] === "argenté" ? 'selected' : '' ?>>Argenté</option>
                    <option value="doré" <?= $product['color'] === "doré" ? 'selected' : '' ?>>Doré</option>
                </select>
            </div>
            <div>
                <label class="required-label" for="en_stock">En stock *</label>
                <select name="en_stock" value="<?= $en_stock ?>">
                    <option value=null <?= $product['en_stock'] === null ? 'selected' : '' ?>></option>
                    <option value=0 <?= $product['en_stock'] === 0 ? 'selected' : '' ?>>Oui</option>
                    <option value=1 <?= $product['en_stock'] === 1 ? 'selected' : '' ?>>Non</option>
                </select>
            </div>
            <button type="submit" name="variant_submit" class="sub__btn">Ajouter</button>
        </form>
    </div>
    
</section>
<a class="del_variant" href="delete-variant.php?id=<?= $id ?>">Supprimer la variante</a>
<?php
Include '../partials/footer.php';
?>