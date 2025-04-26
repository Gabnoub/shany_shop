<?php
Require 'constants.php';


// connect with database
$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (mysqli_errno($connection)){
    die(mysqli_error($connection));
}
$sql = "SELECT * FROM shop_infos";
$stmt = $connection->prepare($sql);
$stmt->execute();
$shop_infos = $stmt->get_result()->fetch_assoc();
$promo = html_entity_decode($shop_infos['promo'], ENT_QUOTES, 'UTF-8') ?? ''; 
$dec_title = html_entity_decode($shop_infos['decouvrir_title'], ENT_QUOTES, 'UTF-8') ?? ''; 
$dec_text = html_entity_decode($shop_infos['decouvrir_text'], ENT_QUOTES, 'UTF-8') ?? ''; 
// $dec_url = $shop_infos['decouvrir_url'] ?? '';
$dec_url = preg_replace('/[^a-zA-Z0-9\-_]/', '-', $dec_title) ?? '';
$category_1 = html_entity_decode($shop_infos['category_1'], ENT_QUOTES, 'UTF-8') ?? '';
$category_2 = html_entity_decode($shop_infos['category_2'], ENT_QUOTES, 'UTF-8') ?? '';
$category_3 = html_entity_decode($shop_infos['category_3'], ENT_QUOTES, 'UTF-8') ?? '';
$category_4 = html_entity_decode($shop_infos['category_4'], ENT_QUOTES, 'UTF-8') ?? '';
$category_text_1 = html_entity_decode($shop_infos['category_text_1'], ENT_QUOTES, 'UTF-8') ?? ''; 
$category_text_2 = html_entity_decode($shop_infos['category_text_2'], ENT_QUOTES, 'UTF-8') ?? ''; 
$category_text_3 = html_entity_decode($shop_infos['category_text_3'], ENT_QUOTES, 'UTF-8') ?? ''; 
$category_text_4 = html_entity_decode($shop_infos['category_text_4'], ENT_QUOTES, 'UTF-8') ?? ''; 
$caroussel_1 = $shop_infos['image_car_1'] ?? '';
$caroussel_2 = $shop_infos['image_car_2'] ?? '';
$caroussel_3 = $shop_infos['image_car_3'] ?? '';
$title_lif = html_entity_decode($shop_infos['title_lif'], ENT_QUOTES, 'UTF-8') ?? '';  
$lifestyle_1 = $shop_infos['image_lif_1'] ?? '';
$lifestyle_2 = $shop_infos['image_lif_2'] ?? '';
$lifestyle_3 = $shop_infos['image_lif_3'] ?? '';
$story = html_entity_decode($shop_infos['image_story'], ENT_QUOTES, 'UTF-8') ?? ''; 
$text_story = html_entity_decode($shop_infos['text_story'], ENT_QUOTES, 'UTF-8') ?? '';   
$text_info_1 = html_entity_decode($shop_infos['text_info_1'], ENT_QUOTES, 'UTF-8') ?? '';  
$text_info_2 = html_entity_decode($shop_infos['text_info_2'], ENT_QUOTES, 'UTF-8') ?? ''; 
$text_info_3 = html_entity_decode($shop_infos['text_info_3'], ENT_QUOTES, 'UTF-8') ?? '';  
$title_info_1 = html_entity_decode($shop_infos['title_info_1'], ENT_QUOTES, 'UTF-8') ?? '';  
$title_info_2 = html_entity_decode($shop_infos['title_info_2'], ENT_QUOTES, 'UTF-8') ?? ''; 
$title_info_3 = html_entity_decode($shop_infos['title_info_3'], ENT_QUOTES, 'UTF-8') ?? '';
$cat_slug[0] =  preg_replace('/[^a-zA-Z0-9\-_]/', '-', $category_1) ?? '';
$cat_slug[1] =  preg_replace('/[^a-zA-Z0-9\-_]/', '-', $category_2) ?? '';
$cat_slug[2] =  preg_replace('/[^a-zA-Z0-9\-_]/', '-', $category_3) ?? '';
$cat_slug[3] =  preg_replace('/[^a-zA-Z0-9\-_]/', '-', $category_4) ?? '';
?>
