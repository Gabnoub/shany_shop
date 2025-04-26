<?php
Include 'partials/header.php';

// Abfrage
// $fetch_products_query = "SELECT id, title, category, en_stock, image1, price, final_price FROM products";
// $fetch_products_result = mysqli_query($connection, $fetch_products_query);


$where = [];
if (isset($_GET['category']) && $_GET['category'] !== '') {
    $cat = (int) $_GET['category'];
    $where[] = "category = $cat";
}
if (isset($_GET['en_stock']) && $_GET['en_stock'] !== '') {
    $stock = (int) $_GET['en_stock'];
    $where[] = "en_stock = $stock";
}
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$fetch_products_query = "SELECT id, title, category, en_stock, image1, price, final_price, cat_slug FROM products $where_sql";
$fetch_products_result = mysqli_query($connection, $fetch_products_query);


?>
<!----==========================================  message Section ============================================---->
<?php if (!empty($_SESSION['add-success'])): ?>
    <div class="alert success">
        <h3>
        <?= $_SESSION['add-success'];
        unset($_SESSION['add-success']);
        ?>
        </h3>
    </div>
<?php endif ?>

<?php if (isset($_SESSION['delete-success'])): ?>
    <div class="alert success"><?= $_SESSION['delete-success']; unset($_SESSION['delete-success']); ?></div>
    <?php elseif (isset($_SESSION['delete-error'])): ?>
    <div class="alert error"><?= $_SESSION['delete-error']; unset($_SESSION['delete-error']); ?></div>
<?php endif; ?>
<!----==========================================  Dashboard Section ============================================---->
<section class="dashboard">
    <p style="font-size: large;"><strong>Admin-Dashboard</strong></p>
    <div class="adBtn">
        <form>
            <label  for="admin_choises"><strong>Gestion des taches</strong>  - Choisir l'option</label>
            <select name="admin_choices" onchange="this.form.submit()">
                <option value="null"></option>
                <option value="addproduct.php">Ajouter un produit</option>
                <option value="manage-reviews.php">Gestion des avis</option>
                <option value="manage-orders.php">Gestion des commandes</option>
                <option value="manage-users.php">Gestion des admins</option>
                <option value="edit-shop.php">Gestion de la boutique</option>
            </select>
        </form>
    </div>
<?php

if (isset($_GET['admin_choices'])) {
    $redirect = $_GET['admin_choices'];
    
    if (isset($redirect)) {
        header("Location: $redirect");
        exit;
    }
}
?>
    <form class="filter_admin" method="GET" style="margin-bottom: 1rem;">
        <select name="category">
            <option value="">Toutes les catégories</option>
            <option value="0"><?= $category_1 ?></option>
            <option value="1"><?= $category_2 ?></option>
            <option value="2"><?= $category_3 ?></option>
            <option value="3"><?= $category_4 ?></option>
        </select>

        <select name="en_stock">
            <option value="">Stock: Tous</option>
            <option value="0">En stock</option>
            <option value="1">Rupture</option>
        </select>

        <button type="submit">Filtrer</button>
    </form>

    <div class="gestion">
        <p style="font-size: large;"><strong>Gestion des produits</strong></p>
        <table>
            <thead>
                <tr>
                    <th>ID</th>  
                    <th>Titre</th>
                    <th>Categorie</th>
                    <th>En stock</th>
                    <th>Prix</th>
                    <th>Prix affiché</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php while($row = $fetch_products_result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td style="display:flex;">
                        <img src="images/<?= htmlspecialchars($row['image1']) ?>" style="height:40px; width:40px; object-fit:cover; vertical-align:middle; margin-right:10px;">
                        <?= htmlspecialchars($row['title']) ?>
                    </td>
                    <td><?= $row['cat_slug'] ?></td>
                    <td><?= $shany_en_stock[htmlspecialchars($row['en_stock'])] ?></td>
                    <td><?= $row['price'] ?></td>
                    <td><?= $row['final_price'] ?></td>
                    <td>
                    <a href="edit-product.php?id=<?= $row['id'] ?>">
                        <button style="background-color:rgb(170, 110, 6); color:white; cursor:pointer; padding:0.5rem 1rem; border-radius:0.3rem;">Modifier</button>
                    </a>
                    </td>
                    <td>
                    <a href="delete-product.php?id=<?= $row['id'] ?>" onclick="return confirm('Sur de vouloir supprimer ce produit?')">
                        <button style="background-color:rgb(232, 51, 51);; color:white; cursor:pointer; padding:0.5rem 1rem; border-radius:0.3rem;">Supprimer</button>
                    </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    
</section>

<?php
Include '../partials/footer.php';
?>