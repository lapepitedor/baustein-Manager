<?php
include('db.php');

// Überprüfen, ob eine Kategorie-ID in der URL übergeben wurde
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];

    // Abrufen der Kategoriedaten, die bearbeitet werden sollen
    $sql = "SELECT * FROM kategorie WHERE id = $edit_id";
    $result_edit = $conn->query($sql);
    $category = $result_edit->fetch_assoc();

    // Überprüfen, ob die Kategoriedaten abgerufen wurden
    if (!$category) {
        echo "Keine Kategorie gefunden.";
        exit();
    }

    // Die Kategorie aktualisieren, wenn das Formular abgeschickt wird
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_category'])) {
        $new_name = $_POST['name'];
        $new_kuerzel = $_POST['edit_category_kuerzel'];

        // Verwendung einer vorbereiteten Anweisung, um SQL-Injection zu vermeiden
        $update_sql = "UPDATE kategorie SET name = ?, kuerzel = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssi", $new_name, $new_kuerzel, $edit_id); // "s" for string, "i" for integer

        if ($stmt->execute()) {
            header("Location: index.php"); // Nach der Aktualisierung zur Hauptseite umleiten
            exit();
        } else {
            echo "Fehler bei der Aktualisierung: " . $stmt->error;
        }
    }
} else {
    echo "Keine Kategorie gefunden.";
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategorie bearbeiten</title>
 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-4">
        <h3>Kategorie bearbeiten</h3>

        <!-- Formular zum Bearbeiten der Kategorie -->
        <?php if (isset($category)): ?>

            <form method="POST">
                <div class="form-group mb-3">
                    <label for="name">Name</label>
                    
                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($category['name']) ?>" required>
                </div>
                <div class="form-group mb-3">
                    <label for="editCategoryKuerzel">Abkürzung</label>
                   
                    <input type="text" class="form-control" id="editCategoryKuerzel" name="edit_category_kuerzel" value="<?= htmlspecialchars($category['kuerzel']) ?>">
                </div>
                <button type="submit" class="btn btn-primary" name="edit_category">Aktualisieren</button>
                <a href="index.php" class="btn btn-secondary">Zurück</a>
            </form>
        <?php else: ?>
            <p>Keine Kategorie gefunden.</p>
        <?php endif; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>