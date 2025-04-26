<?php
Include 'partials/header.php';

// get back form data if there was an error
$category = $_SESSION['add-data']['category'] ?? null;
$en_stock = $_SESSION['add-data']['en_stock'] ?? null;
$title = $_SESSION['add-data']['title'] ?? null;
$material = $_SESSION['add-data']['material'] ?? null;
$color = $_SESSION['add-data']['color'] ?? null;
$size = $_SESSION['add-data']['size'] ?? null;
$description1 = $_SESSION['add-data']['description1'] ?? null;
$bulletpoint1 = $_SESSION['add-data']['bulletpoint1'] ?? null;
$bulletpoint2 = $_SESSION['add-data']['bulletpoint2'] ?? null;
$bulletpoint3 = $_SESSION['add-data']['bulletpoint3'] ?? null;
$bulletpoint4 = $_SESSION['add-data']['bulletpoint4'] ?? null;
$description2 = $_SESSION['add-data']['description2'] ?? null;
$price = $_SESSION['add-data']['price'] ?? null;
$discount = $_SESSION['add-data']['discount'] ?? null;
// delete session data
unset($_SESSION['add-data']);
?>
<!----==========================================  Add product Section ============================================---->
<section class="form__section">
    <div class="add__products">
    <a href="index.php" style="margin-left: 10px;">
        <button type="button" class="sub__btn cancel"><i class="uil uil-arrow-left"></i></button>
    </a>
    <h2>Ajouter un Produit</h2>
        <?php if (!empty($_SESSION['add'])): ?> 
                <div class="alert">
                    <?= $_SESSION['add'];
                    unset($_SESSION['add']);
                    ?>
                </div>
            <?php endif; ?>

        <form action="add-product-logic.php" enctype="multipart/form-data" method="POST">
            <div>
                <label class="required-label" for="category">Choisir la categorie *</label>
                <select name="category">
                    <option value=null <?= $category === null ? 'selected' : '' ?>></option>
                    <option value=0 <?= $category === 0 ? 'selected' : '' ?>><?= htmlspecialchars($category_1) ?></option>
                    <option value=1 <?= $category === 1 ? 'selected' : '' ?>><?= htmlspecialchars($category_2) ?></option>
                    <option value=2 <?= $category === 2 ? 'selected' : '' ?>><?= htmlspecialchars($category_3) ?></option>
                    <option value=3 <?= $category === 3 ? 'selected' : '' ?>><?= htmlspecialchars($category_4) ?></option>
                </select>

            </div>
            <div>
                <label class="required-label" for="en_stock">En stock *</label>
                <select name="en_stock" value="<?= $en_stock ?>">
                    <option value=null <?= $en_stock === null ? 'selected' : '' ?>></option>
                    <option value=0 <?= $en_stock === 0 ? 'selected' : '' ?>>Oui</option>
                    <option value=1 <?= $en_stock === 1 ? 'selected' : '' ?>>Non</option>
                </select>
            </div>
            <label class="required-label" for="title">Titre *</label>
            <input type="text" name="title" value="<?= htmlspecialchars($title) ?>" placeholder="Titre">
            <label for="material">Material</label>
            <input type="text" name="material" value="<?= htmlspecialchars($material) ?>" placeholder="Material">
            <label for="color">Couleur</label>
            <input type="text" name="color" value="<?= htmlspecialchars($color) ?>" placeholder="Couleur">
            <label for="size">Taille</label>
            <input type="text" name="size" value="<?= htmlspecialchars($size) ?>" placeholder="Taille">
            <label class="required-label" for="description1">Description 1 *</label>
            <input type="text" name="description1" value="<?= htmlspecialchars($description1) ?>" placeholder="Description 1">
            <label for="bulletpoint1">bulletpoint 1</label>
            <input  type="text" name="bulletpoint1" value="<?= htmlspecialchars($bulletpoint1) ?>" placeholder="Bulletpoint 1">
            <label for="bulletpoint2">bulletpoint 2</label>
            <input  type="text" name="bulletpoint2" value="<?= htmlspecialchars($bulletpoint2) ?>" placeholder="Bulletpoint 2">
            <label for="bulletpoint3">bulletpoint 3</label>
            <input type="text" name="bulletpoint3" value="<?= htmlspecialchars($bulletpoint3) ?>" placeholder="Bulletpoint 3">
            <label for="bulletpoint4">bulletpoint 4</label>
            <input type="text" name="bulletpoint4" value="<?= htmlspecialchars($bulletpoint4) ?>" placeholder="Bulletpoint 4">
            <label for="description2">Description 2</label>
            <input type="text" name="description2" value="<?= htmlspecialchars($description2) ?>" placeholder="Description 2">
            <div class="form__control">
                <label for="thumbnail">Add Image 1 *</label>
                <input type="file" id="prImg1" name="image1">
                <label for="thumbnail">Add Image 2</label>
                <input type="file" id="prImg2" name="image2">
                <label for="thumbnail">Add Image 3</label>
                <input type="file" id="prImg3" name="image3">
                <label for="thumbnail">Add Image 4</label>
                <input type="file" id="prImg4" name="image4">
            </div>
            <label>Prix en Fcfa *</label>
            <input type="number" step="1" name="price" value="<?= htmlspecialchars($price) ?>" placeholder="Prix">
            <label>Rabais en Fcfa</label>
            <input type="number" step="1" name="discount" value="<?= htmlspecialchars($discount) ?>" placeholder="Rabais">
            <button type="submit" name="add_submit" class="sub__btn">Add Product</button>
        </form>
    </div>
</section>
<?php
Include '../partials/footer.php';
?>