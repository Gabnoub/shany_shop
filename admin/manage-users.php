<?php
include 'partials/header.php';

// SQL-Abfrage bauen
$sql = "SELECT * FROM users";
$params = [];
$types = "";


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
    <h2>Gestion des admins</h2>
        
        <!-- Bestellungstabelle -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Aucun admin trouvé</td>
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