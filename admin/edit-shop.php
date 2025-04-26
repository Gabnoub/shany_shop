<?php
Include 'partials/header.php';

// get back form data if there was an error
$promo = $_SESSION['add-data']['promo_text'] ?? null;

// delete session data
unset($_SESSION['add-data']);


$sql = "SELECT * FROM shop_infos";
$stmt = $connection->prepare($sql);
$stmt->execute();
$shop_infos = $stmt->get_result()->fetch_assoc();

?>
<!----==========================================  Add product Section ============================================---->
<section class="form__section form__shop">
<?php if (!empty($_SESSION['shop'])): ?> 
                <div class="alert">
                    <?= $_SESSION['shop'];
                    unset($_SESSION['shop']);
                    ?>
                </div>
<?php endif; ?>
<a href="index.php" style="margin-left: 10px;">
     <button type="button" class="sub__btn cancel"><i class="uil uil-arrow-left"></i></button>
</a> 
<h2>Gestion du shop</h2>
        <form action="edit-shop-logic.php" enctype="multipart/form-data" method="POST">
            <label for="promo">Changer le texte de promotion</label>
            <input type="text" name="promo" value="<?= htmlspecialchars($shop_infos['promo']) ?>">

            <label for="decouvrir">Changer le titre de la section "DECOUVRIR"</label>
            <input type="text" name="decouvrir_title" value="<?= htmlspecialchars($shop_infos['decouvrir_title']) ?>">

            <label for="decouvrir">Changer le text de la section "DECOUVRIR"</label>
            <input type="text" name="decouvrir_text" value="<?= htmlspecialchars($shop_infos['decouvrir_text']) ?>">

            <label for="categories">Changer le titre  de la categorie 1</label>
            <input type="text" name="category_1" value="<?= htmlspecialchars($shop_infos['category_1']) ?>">
            <label for="categories">Changer le texte  de la categorie 1</label>
            <input type="text" name="category_text_1" value="<?= htmlspecialchars($shop_infos['category_text_1']) ?>">

            <label for="categories">Changer le titre  de la categorie 2</label>
            <input type="text" name="category_2" value="<?= htmlspecialchars($shop_infos['category_2']) ?>">
            <label for="categories">Changer le texte  de la categorie 2</label>
            <input type="text" name="category_text_2" value="<?= htmlspecialchars($shop_infos['category_text_2']) ?>">

            <label for="categories">Changer le titre  de la categorie 3</label>
            <input type="text" name="category_3" value="<?= htmlspecialchars($shop_infos['category_3']) ?>">
            <label for="categories">Changer le texte  de la categorie 3</label>
            <input type="text" name="category_text_3" value="<?= htmlspecialchars($shop_infos['category_text_3']) ?>">

            <label for="categories">Changer le titre  de la categorie 4</label>
            <input type="text" name="category_4" value="<?= htmlspecialchars($shop_infos['category_4']) ?>">
            <label for="categories">Changer le texte  de la categorie 4</label>
            <input type="text" name="category_text_4" value="<?= htmlspecialchars($shop_infos['category_text_4']) ?>">
            
            <div class="form__control form__shop">
                <label for="thumbnail">changer image 1 du caroussel (height: 600px)</label>
                <?php if (!empty($shop_infos['image_car_1'])): ?>
                    <img src="images/<?= htmlspecialchars($shop_infos['image_car_1']) ?>" style="height: 40px; width: 40px; object-fit:cover; margin-left: 35px;">
                <?php endif; ?>
                <input type="file" name="image_car_1">
                <input type="hidden" name="current_image_car_1" value="<?= $shop_infos['image_car_1'] ?>">

                <label for="thumbnail">changer image 2 du caroussel</label>
                <?php if (!empty($shop_infos['image_car_2'])): ?>
                    <img src="images/<?= htmlspecialchars($shop_infos['image_car_2']) ?>" style="height: 40px; width: 40px; object-fit:cover; margin-left: 35px;">
                <?php endif; ?>
                <input type="file" name="image_car_2">
                <input type="hidden" name="current_image_car_2" value="<?= $shop_infos['image_car_2'] ?>">


                <label for="thumbnail">changer image 3 du caroussel</label>
                <?php if (!empty($shop_infos['image_car_3'])): ?>
                    <img src="images/<?= htmlspecialchars($shop_infos['image_car_3']) ?>" style="height: 40px; width: 40px; object-fit:cover; margin-left: 35px;">
                <?php endif; ?>
                <input type="file" name="image_car_3">
                <input type="hidden" name="current_image_car_3" value="<?= $shop_infos['image_car_3'] ?>">

                <label for="lifestyle">Changer le titre  de la section Lifestyle</label>
                <input type="text" name="title_lif" value="<?= htmlspecialchars($shop_infos['title_lif']) ?>">

                <label for="lifestyle">changer image 1 du lifestyle (width/height: 460px)</label>
                <?php if (!empty($shop_infos['image_lif_1'])): ?>
                    <img src="images/<?= htmlspecialchars($shop_infos['image_lif_1']) ?>" style="height: 40px; width: 40px; object-fit:cover; margin-left: 35px;">
                <?php endif; ?>
                <input type="file" name="image_lif_1">
                <input type="hidden" name="current_image_lif_1" value="<?= $shop_infos['image_lif_1'] ?>">

                <label for="lifestyle">changer image 2 du lifestyle</label>
                <?php if (!empty($shop_infos['image_lif_2'])): ?>
                    <img src="images/<?= htmlspecialchars($shop_infos['image_lif_2']) ?>" style="height: 40px; width: 40px; object-fit:cover; margin-left: 35px;">
                <?php endif; ?>
                <input type="file" name="image_lif_2">
                <input type="hidden" name="current_image_lif_2" value="<?= $shop_infos['image_lif_2'] ?>">

                <label for="lifestyle">changer image 3 du lifestyle</label>
                <?php if (!empty($shop_infos['image_lif_3'])): ?>
                    <img src="images/<?= htmlspecialchars($shop_infos['image_lif_3']) ?>" style="height: 40px; width: 40px; object-fit:cover; margin-left: 35px;">
                <?php endif; ?>
                <input type="file" name="image_lif_3">
                <input type="hidden" name="current_image_lif_3" value="<?= $shop_infos['image_lif_3'] ?>">  

                <label for="thumbnail">changer image de la story (width/height: 460px)</label>
                <?php if (!empty($shop_infos['image_story'])): ?>
                    <img src="images/<?= htmlspecialchars($shop_infos['image_story']) ?>" style="height: 40px; width: 40px; object-fit:cover; margin-left: 35px;">
                <?php endif; ?>
                <input type="file" name="image_story">
                <input type="hidden" name="current_image_story" value="<?= $shop_infos['image_story'] ?>"> 
            </div>
            <label for="story">Changer le texte de la story</label>
            <input type="text" name="text_story" value="<?= htmlspecialchars($shop_infos['text_story']) ?>">

            <label for="info">Changer le titre de la section Info 1</label>
            <input type="text" name="title_info_1" value="<?= htmlspecialchars($shop_infos['title_info_1']) ?>">
            <label for="info">Changer le texte  de la section Info 1</label>
            <input type="text" name="text_info_1" value="<?= htmlspecialchars($shop_infos['text_info_1']) ?>">

            <label for="info">Changer le titre de la section Info 2</label>
            <input type="text" name="title_info_2" value="<?= htmlspecialchars($shop_infos['title_info_2']) ?>">
            <label for="info">Changer le texte  de la section Info 2</label>
            <input type="text" name="text_info_2" value="<?= htmlspecialchars($shop_infos['text_info_2']) ?>">

            <label for="info">Changer le titre de la section Info 3</label>
            <input type="text" name="title_info_3" value="<?= htmlspecialchars($shop_infos['title_info_3']) ?>">
            <label for="info">Changer le texte  de la section Info 3</label>
            <input type="text" name="text_info_3" value="<?= htmlspecialchars($shop_infos['text_info_3']) ?>">

            <button type="submit" name="shop_submit" class="sub__btn">Valider les changements</button>
        </form>
    </div>
</section>
<?php
Include '../partials/footer.php';
?>