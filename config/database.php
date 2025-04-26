<?php
Require 'config/constants.php';

// connect with database
$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (mysqli_errno($connection)){
    die(mysqli_error($connection));
}
$sql = "SELECT * FROM shop_infos";
$stmt = $connection->prepare($sql);
$stmt->execute();
$shop_infos = $stmt->get_result()->fetch_assoc();
$promo = $shop_infos['promo'] ?? '';
$dec_title = $shop_infos['decouvrir_title'] ?? '';
$dec_text = $shop_infos['decouvrir_text'] ?? '';
// $dec_url = $shop_infos['decouvrir_url'] ?? '';
$dec_url = preg_replace('/[^a-zA-Z0-9\-_]/', '-', $dec_title) ?? '';
$category_1 = html_entity_decode($shop_infos['category_1'], ENT_QUOTES, 'UTF-8') ?? '';
$category_2 = html_entity_decode($shop_infos['category_2'], ENT_QUOTES, 'UTF-8') ?? '';
$category_3 = html_entity_decode($shop_infos['category_3'], ENT_QUOTES, 'UTF-8') ?? '';
$category_4 = html_entity_decode($shop_infos['category_4'], ENT_QUOTES, 'UTF-8') ?? '';
$category_text_1 = $shop_infos['category_text_1'] ?? '';
$category_text_2 = $shop_infos['category_text_2'] ?? '';
$category_text_3 = $shop_infos['category_text_3'] ?? '';
$category_text_4 = $shop_infos['category_text_4'] ?? '';
$caroussel_1 = $shop_infos['image_car_1'] ?? '';
$caroussel_2 = $shop_infos['image_car_2'] ?? '';
$caroussel_3 = $shop_infos['image_car_3'] ?? '';
$title_lif = $shop_infos['title_lif'] ?? '';
$lifestyle_1 = $shop_infos['image_lif_1'] ?? '';
$lifestyle_2 = $shop_infos['image_lif_2'] ?? '';
$lifestyle_3 = $shop_infos['image_lif_3'] ?? '';
$story = $shop_infos['image_story'] ?? '';
$text_story = $shop_infos['text_story'] ?? '';
$text_info_1 = $shop_infos['text_info_1'] ?? '';
$text_info_2 = $shop_infos['text_info_2'] ?? '';
$text_info_3 = $shop_infos['text_info_3'] ?? '';
$title_info_1 = $shop_infos['title_info_1'] ?? '';
$title_info_2 = $shop_infos['title_info_2'] ?? '';
$title_info_3 = $shop_infos['title_info_3'] ?? '';
$cat_slug[0] =  preg_replace('/[^a-zA-Z0-9\-_]/', '-', $category_1) ?? '';
$cat_slug[1] =  preg_replace('/[^a-zA-Z0-9\-_]/', '-', $category_2) ?? '';
$cat_slug[2] =  preg_replace('/[^a-zA-Z0-9\-_]/', '-', $category_3) ?? '';
$cat_slug[3] =  preg_replace('/[^a-zA-Z0-9\-_]/', '-', $category_4) ?? '';
?>
