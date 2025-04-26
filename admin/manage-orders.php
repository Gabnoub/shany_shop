<?php
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
$sql = "SELECT * FROM orders";
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
        $sql .= " WHERE " . implode(" AND ", $whereClause);
    }
}

// Nach Datum absteigend sortieren (neueste zuerst)
$sql .= " ORDER BY created_at DESC";

// Prepared Statement vorbereiten
$stmt = $connection->prepare($sql);

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
    <h2>Gestion des commandes</h2>
        
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
                    <a href="manage-orders.php">Annuler</a>
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
            <a href="manage-orders.php" class="btn btn-sm btn-outline-secondary ms-3">Annuler les filtres</a>
        </div>
        <?php endif; ?>
        
        <!-- Bestellungstabelle -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Commande</th>
                        <th>Date de creation</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['message']); ?></td>
                                <td><?php echo date('d.m.Y H:i', strtotime($row['created_at'])); ?></td>
                                <td>
                                <a href="edit-order-logic.php?delete_order_id=<?= $row['id'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Sur de vouloir supprimer la commande?')">
                                    Supprimer
                                </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Aucune commande trouvée</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
</div>

<?php
// Verbindung schließen
$stmt->close();
$connection->close();
?>
<?php include '../partials/footer.php'; ?>