<?php
// manage_reviews.php
include 'partials/header.php';


// Initialisiere Filter-Variablen
$startDate = '';
$endDate = '';
$filterActive = false;

// Verarbeite Filter-Formular
if (isset($_POST['filter_submit'])) {
    if (!empty($_POST['start_date'])) {
        $startDate = $_POST['start_date'];
    }
    if (!empty($_POST['end_date'])) {
        $endDate = $_POST['end_date'];
    }
    $filterActive = !empty($startDate) || !empty($endDate);
}

// SQL-Abfrage bauen
$query = "SELECT r.*, p.title as product_title 
          FROM product_ratings r 
          JOIN products p ON r.product_id = p.id 
        ";
$params = [];
$types = "";

if ($filterActive) {
    // Filter anwenden
    $whereClause = [];
    
    if (!empty($startDate)) {
        $whereClause[] = "DATE(created_at) >= ?";
        $params[] = $startDate;
        $types .= "s";
    }
    
    if (!empty($endDate)) {
        $whereClause[] = "DATE(created_at) <= ?";
        $params[] = $endDate;
        $types .= "s";
    }
    
    if (!empty($whereClause)) {
        $query .= " WHERE " . implode(" AND ", $whereClause);
    }
}

// Nach Datum absteigend sortieren (neueste zuerst)
$query .= " ORDER BY created_at DESC";


// Hole alle Bewertungen aus der Datenbank
// $result = $connection->query($query);


// Prepared Statement vorbereiten
$stmt = $connection->prepare($query);

// Parameter binden, falls vorhanden
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

// Abfrage ausführen
$stmt->execute();
$result = $stmt->get_result();
?>

<a href="index.php" >
        <button style="margin: 1rem;" type="button" class="sub__btn cancel"><i class="uil uil-arrow-left"></i></button>
</a>

<div class="container mt-4">
    <h2>Gestion des avis</h2>
    
<!-- Filter-Formular -->
<div class="filter-form">
        <form method="POST" action="">
            <div class="filter_ord">
                <div class="start_date">
                    <label for="start_date" class="form-label">À partir de - Date:</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo htmlspecialchars($startDate); ?>">
                </div>
                <div class="last_date">
                    <label for="end_date" class="form-label">Jusqu'à - Date:</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo htmlspecialchars($endDate); ?>">
                </div>
                <div class="fil_btns">
                    <button type="submit" name="filter_submit">Filtrer</button>
                    <a href="manage-reviews.php">Annuler</a>
                </div>
            </div>
        </form>
    </div>


    <?php if ($filterActive): ?>
        <div class="actif_filter">
            Filtre actif: 
            <?php 
            if (!empty($startDate)) echo "À partir de: " . htmlspecialchars($startDate) . " ";
            if (!empty($endDate)) echo "Jusqu'à: " . htmlspecialchars($endDate);
            ?>
            <a href="manage-reviews.php" class="btn btn-sm btn-outline-secondary ms-3">Annuler les filtres</a>
        </div>
        <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Produit</th>
                    <th>Client</th>
                    <th>Avis</th>
                    <th>Commentaire</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($review = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $review['id'] ?></td>
                            <td><?= htmlspecialchars($review['product_title']) ?></td>
                            <td><?= htmlspecialchars($review['user_name']) ?></td>
                            <td>
                                <?php 
                                    // Sterne-Darstellung
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $review['rating']) {
                                            echo '★'; // voller Stern
                                        } else {
                                            echo '☆'; // leerer Stern
                                        }
                                    }
                                ?>
                            </td>
                            <td><?= htmlspecialchars($review['comment']) ?></td>
                            
                            <td><?= date('d.m.Y H:i', strtotime($review['created_at'])) ?></td>
                            <td>
                                <a href="edit-rating-logic.php?delete_review_id=<?= $review['id'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Sur de vouloir supprimer l\'avis?')">
                                    Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">Aucun avis trouvé</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../partials/footer.php'; ?>