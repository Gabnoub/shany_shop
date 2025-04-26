<?php
Require 'config/database.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT_URL ?>/CSS/style.css?v=<?php echo time(); ?>">
    <!--  Iconscout CDN-->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <!--  Google Fonts CDN-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <title>Shany Shop</title>
    <script src="<?= ROOT_URL ?>/JS/script.js?v=<?php echo time(); ?>" defer></script>
</head>
<body>
<!----========================================== first Section - promo/NAV/Caroussel ============================================---->
<section class="first">
  <!------------------------------------------------------------ Promotion text -------------------------------------------------------------->
  <div class="promotion"><?= $promo ?></div>
  <!------------------------------------------------------------ Navigation Bar --------------------------------------------------------- -->
  <nav class="nav__container">
      <a class="nav__logo" href="<?= ROOT_URL ?>index.php">
          <!-- <img src="assets/logo.png" alt="Logo Shany Shop" class="nav__logo--img"> -->
              SHANY
      </a>
      <ul class="nav__links">
          <li class="nav__item"><a href="<?= ROOT_URL ?>categories/<?= $cat_slug[0] ?>" class="nav__link"><?= $category_1 ?></a></li>
          <li class="nav__item"><a href="<?= ROOT_URL ?>categories/<?= $cat_slug[1] ?>" class="nav__link"><?= $category_2 ?></a></li>
          <li class="nav__item"><a href="<?= ROOT_URL ?>categories/<?= $cat_slug[2] ?>" class="nav__link"><?= $category_3 ?></a></li>
          <li class="nav__item"><a href="<?= ROOT_URL ?>categories/<?= $cat_slug[3] ?>" class="nav__link"><?= $category_4 ?></a></li>
      </ul>
  </nav>
  <button class="logout"><a href="logout.php">Logout</a></button>
</section>