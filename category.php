<?php
Include 'partials/header.php';
$_en_stock = 0;

$sort = $_GET['sort'] ?? 'default';

switch ($sort) {
    case 'price_asc':
        $orderBy = 'final_price ASC';
        break;
    case 'price_desc':
        $orderBy = 'final_price DESC';
        break;
    case 'title_asc':
        $orderBy = 'title ASC';
        break;
    case 'title_desc':
        $orderBy = 'title DESC';
        break;
    case 'discount_desc':
        $orderBy = 'discount DESC';
        break;
    default:
        $orderBy = 'id DESC';
}

$catslug = $_GET['cat_slug'] ?? null;

if ($catslug === $dec_url){
    if (!isset($_GET['sort'])){
        $_GET['sort'] = 'discount_desc';
        $orderBy = 'discount DESC';
    } 
    $cat_title = $dec_title;
    $cat_text = $dec_text;
    $fetch_products_query = "SELECT * FROM products WHERE en_stock = $_en_stock ORDER BY $orderBy";
    $fetch_products_result = mysqli_query($connection, $fetch_products_query);
    $count = mysqli_num_rows($fetch_products_result);
} else {
    // set $id
    if ($catslug === $cat_slug[0]) {
        $cat_title = $category_1;
        $cat_text = $category_text_1;
    } elseif ($catslug === $cat_slug[1]) {
        $cat_title = $category_2;
        $cat_text = $category_text_2;
    } elseif ($catslug === $cat_slug[2]) {
        $cat_title = $category_3;
        $cat_text = $category_text_3;
    } elseif ($catslug === $cat_slug[3]) {
        $cat_title = $category_4;
        $cat_text = $category_text_4;
    }
    // Verwende vorbereitete Anweisung für die Produktauswahl
    $stmt = $connection->prepare("SELECT * FROM products WHERE cat_slug = ? AND en_stock = ? ORDER BY $orderBy");
    $stmt->bind_param("si", $catslug, $_en_stock);
    $stmt->execute();
    $fetch_products_result = $stmt->get_result();

    // Verwende vorbereitete Anweisung für die Zählung
    $count_stmt = $connection->prepare("SELECT COUNT(*) as count FROM products WHERE cat_slug = ? AND en_stock = ?");
    $count_stmt->bind_param("si", $catslug, $_en_stock);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    $count = $count_result->fetch_assoc()['count'];
}

if (!$fetch_products_result) {
    echo "No product available.";
    exit;
}


?>
<!----========================================== Category Section =================================================---->
<section>
    <div class="home__category">
            <a style="color:black" href="<?= ROOT_URL ?>">Accueil</a>
            <strong><?=  $cat_title ?></strong>
    </div>    
    <div class="category__description">
        <h2><?= $cat_title ?></h2>
        <p><?= $cat_text ?></p>
        <div class="cat_filter">
            <?php if ($count === 1): ?>
                <p class="num__products"><?= $count ?> produit</p>
            <?php else: ?>
                <p class="num__products"><?= $count ?> produits</p>    
            <?php endif; ?>
            <form class="form__category" method="GET" action="">
                <input type="hidden" name="id" value="<?= $id ?>">
                <label for="sort">Trier par:</label>
                <select name="sort" id="sort" onchange="this.form.submit()">
                    <option value="default" <?= (!isset($_GET['sort']) || $_GET['sort'] == 'default') ? 'selected' : '' ?>>Standard</option>
                    <option value="discount_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'discount_desc') ? 'selected' : '' ?>>En promotion</option>
                    <option value="price_asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : '' ?>>Prix: faible à élévé</option>
                    <option value="price_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : '' ?>>Prix: élévé à faible</option>
                    <option value="title_asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'title_asc') ? 'selected' : '' ?>>Titre A-Z</option>
                    <option value="title_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'title_desc') ? 'selected' : '' ?>>Titre Z-A</option>
                </select>
            </form>
        </div>
    </div>
    

    <div class="cat__products-container">
        <?php while($row = $fetch_products_result->fetch_assoc()): ?>
            <div class="cat__product-item">
                <a class="cat__pr_link" href="<?= ROOT_URL ?>products/<?= $row['slug'] ?>"><img src="<?= ROOT_URL ?>admin/images/<?= htmlspecialchars($row['image1']) ?>">
                <p class="cat__pr__title"><?= htmlspecialchars($row['title']) ?></p>
                <?php if ($row['price'] !== $row['final_price']): ?>
                <p class="cat__pr__price"><del style="text-decoration: line-through;"><?= number_format($row['price'], 0, ',', '.') ?></del>  <strong><?= number_format($row['final_price'], 0, ',', '.') ?> CFA</strong></p>
                <button class="rabatt">- <?= round(100 - (($row['final_price'] * 100) / $row['price'])) ?> %</button>
                <?php else: ?>
                <p class="cat__pr__price"><strong><?= $row['price'] ?> CFA</strong></p>
                <?php endif; ?>
                </a>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<?php
Include 'partials/footer.php';
?>