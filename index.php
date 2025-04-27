<?php
Include 'partials/header.php';
$_en_stock = 0;

// Last new products fetch en stock - New products Section
$last_products_query = "SELECT * FROM products WHERE en_stock = $_en_stock ORDER BY id DESC LIMIT 12";
$last_products_result = mysqli_query($connection, $last_products_query);


// fetch 4 products order by discount desc - Beststellers Section
$best_products_query = "SELECT * FROM products WHERE en_stock = $_en_stock ORDER BY discount DESC LIMIT 12";
$best_products_result = mysqli_query($connection, $best_products_query);

// fetch first 4 products for all categories
$cat_products = [];


for ($cat = 0; $cat < 4; $cat++) {
  $stmt = $connection->prepare("SELECT * FROM products WHERE category = ? AND en_stock = ? ORDER BY id ASC LIMIT 4");
  $stmt->bind_param("ii", $cat, $_en_stock);
  $stmt->execute();
  $result = $stmt->get_result();
  $cat_products[$cat] = [];

  while ($row = $result->fetch_assoc()) {
    $cat_products[$cat][] = [
      'id' => $row['id'],
      'slug' => $row['slug'],
      'catSlug' => $row['cat_slug'],
      'title' => $row['title'],
      'price' => number_format($row['price'], 0, ',', '.'),
      'finalprice' => number_format($row['final_price'], 0, ',', '.'),
      'image' => 'admin/images/' . $row['image1']
    ];
  }
}
echo "<script>const products = " . json_encode($cat_products) . ";</script>";

?>
    <!--- Caroussel Images  -->
    <div class="caroussel">
        <div class="caroussel__container">
                <img class="caroussel__image active"  src="<?= ROOT_URL ?>admin/images/<?= $caroussel_1 ?>">
                <img class="caroussel__image"  src="<?= ROOT_URL ?>admin/images/<?= $caroussel_2 ?>">
                <img class="caroussel__image"  src="<?= ROOT_URL ?>admin/images/<?= $caroussel_3 ?>">
        </div>
        <div class="progress-bars">
            <div class="progress-bar" onclick="currentSlide(0)"></div>
            <div class="progress-bar" onclick="currentSlide(1)"></div>
            <div class="progress-bar" onclick="currentSlide(2)"></div>
        </div>
        <a class="call__to-action" href="<?= ROOT_URL ?>categories/<?= $dec_url ?>">DÉCOUVRIR</a>
    </div>

<!----==========================================  New Products Section ============================================---->


<section class="new__products animation">
  <div class="np__title">
    <h2 style="font-size: x-large; font-weight:500">Nos Nouveautés</h2>
  </div>
  
  <div class="carousel-container">
    <button class="carousel-button prev-button" aria-label="Produits précédents">
      <i class="uil uil-angle-double-left"></i>
    </button>
    
    <div class="new__products-container">
      <?php while ($product = mysqli_fetch_assoc($last_products_result)): ?>
        <div class="new__product-item icon_wrapper">
          <a class="pr_link" href="<?= ROOT_URL ?>products/<?= $product['slug'] ?>">  
          <img src="admin/images/<?= htmlspecialchars($product['image1']) ?>" class="pr_image">
            <p class="pr__title"><?= htmlspecialchars($product['title']) ?></p>
            <p class="pr__price">
              <?php if ($product['price'] !== $product['final_price']): ?>
                <del style="text-decoration: 1.1px line-through;"><?= number_format($product['price'], 0, ',', '.') ?></del>
                <strong><?= number_format($product['final_price'], 0, ',', '.') ?> CFA</strong>
                <button class="rabatt">- <?= round(100 - (($product['final_price'] * 100) / $product['price'])) ?> %</button>   
              <?php else: ?>
                <strong><?= number_format($product['final_price'], 0, ',', '.') ?> CFA</strong>
              <?php endif; ?>
            </p>
          </a>
        </div>
      <?php endwhile; ?>
    </div>
    
    <button class="carousel-button next-button" aria-label="Produits suivants">
      <i class="uil uil-angle-double-right"></i>
    </button>
  </div>
  <div class="carousel-dots">
    <?php 
    $total = mysqli_num_rows($last_products_result);
    $total_pages = ceil($total / 4);
    for($i = 0; $i < $total_pages; $i++): 
    ?>
      <span class="dot <?= $i === 0 ? 'active' : '' ?>" data-index="<?= $i ?>"></span>
    <?php endfor; ?>
  </div>
</section>

<!----========================================== Lifestyle section ============================================---->
<section class="lifestyle animation">
    <h2><?= $title_lif ?></h2>    
        <!-- <div class="lifestyle__images">
            <img src="<?= ROOT_URL ?>admin/images/<?= $lifestyle_1 ?>" class="lifestyle__image">
            <img src="<?= ROOT_URL ?>admin/images/<?= $lifestyle_2 ?>" class="lifestyle__image">
            <img src="<?= ROOT_URL ?>admin/images/<?= $lifestyle_3 ?>" class="lifestyle__image">
        </div> -->
        
  <div class="gallery-container">
    <div class="gallery-slides">
      <!-- Slide 1 -->
      <div class="gallery-slide active">
        <div class="gallery-images">
          <div class="gallery-image">
            <img src="<?= ROOT_URL ?>admin/images/<?= $lifestyle_1 ?>">
          </div>
          <div class="gallery-image">
            <img src="<?= ROOT_URL ?>admin/images/<?= $lifestyle_2 ?>">
          </div>
          <div class="gallery-image">
            <img src="<?= ROOT_URL ?>admin/images/<?= $lifestyle_3 ?>">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!----========================================== Beststellers section ============================================---->
<section class="best__sellers animation">
  <div class="np__title">
    <h2 style="font-size: x-large; font-weight:500">Nos Bestsellers</h2>
  </div>
  
  <div class="carousel-container-bestseller">
    <button class="carousel-button-bestseller prev-button-bestseller" aria-label="Produits précédents">
      <i class="uil uil-angle-double-left"></i>
    </button>
    
    <div class="best__sellers-container">
      <?php while ($product = mysqli_fetch_assoc($best_products_result)): ?>
        <div class="best__sellers-item icon_wrapper">
          <a class="pr_link" href="<?= ROOT_URL ?>products/<?= $product['slug'] ?>">  
          <img src="admin/images/<?= htmlspecialchars($product['image1']) ?>" class="pr_image">
            <p class="pr__title"><?= htmlspecialchars($product['title']) ?></p>
            <p class="pr__price">
              <?php if ($product['price'] !== $product['final_price']): ?>
                <del style="text-decoration: 1.1px line-through;"><?= number_format($product['price'], 0, ',', '.') ?></del>
                <strong><?= number_format($product['final_price'], 0, ',', '.') ?> CFA</strong>
                <button class="rabatt">- <?= round(100 - (($product['final_price'] * 100) / $product['price'])) ?> %</button>   
              <?php else: ?>
                <strong><?= number_format($product['final_price'], 0, ',', '.') ?> CFA</strong>
              <?php endif; ?>
            </p>
          </a>
        </div>
      <?php endwhile; ?>
    </div>
    
    <button class="carousel-button-bestseller next-button-bestseller" aria-label="Produits suivants">
      <i class="uil uil-angle-double-right"></i>
    </button>
  </div>
  
  <div class="carousel-dots-bestseller">
    <?php 
    $total_bestsellers = mysqli_num_rows($best_products_result);
    $total_pages_bestseller = ceil($total_bestsellers / 4);
    for($i = 0; $i < $total_pages_bestseller; $i++): 
    ?>
      <span class="dot-bestseller <?= $i === 0 ? 'active' : '' ?>" data-index="<?= $i ?>"></span>
    <?php endfor; ?>
  </div>
</section>
<!----============================================== About section ============================================----------->
<section class="about animation">
    <div class="about__container">
        <img class="about__image"  src="<?= ROOT_URL ?>admin/images/<?= $story ?>">
        <article class="about__text">
            <h2>Notre histoire</h2>
            <h5><?= $text_story ?></h5>
        </article>
    </div>
</section>
<!----========================================== Categories section ============================================---->
<section class="caterogies animation">
    <h2>Découvrez notre collection exclusive</h2>
    <div class="categories__container">
            <button onclick="currentCat(0)" class="categories__item"><?= $category_1 ?></button>
            <button onclick="currentCat(1)" class="categories__item"><?= $category_2 ?></button>
            <button onclick="currentCat(2)" class="categories__item"><?= $category_3 ?></button>
            <button onclick="currentCat(3)" class="categories__item"><?= $category_4 ?></button>
        <div ID="pbc" class="progress-bar__cat"></div>
    </div>
    <div class="product-container" id="productContainer"></div>
    <button id="exploreBtn" class="open__product">Explorer</button>
</section>
<!----========================================== End ============================================---->
<?php
Include 'partials/footer.php';
?>
<script src="<?= ROOT_URL ?>JS/main.js?v=<?php echo time(); ?>" defer></script>