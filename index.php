<?php
include('db.php');

// Retrieve all categories
$sql = "SELECT * FROM kategorie";
$result = $conn->query($sql);

// Add new kategorie
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_category'])) {

    $new_category_name = $_POST['new_category'];
    $new_category_kuerzel = $_POST['new_category_kuerzel'];

    $sql = "INSERT INTO kategorie (name,kuerzel) VALUES ('$new_category_name','$new_category_kuerzel')";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php"); // Reload after adding
        exit();
    } else {
        echo "Erreur: " . $conn->error;
    }
}

// for delete a kategorie
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM kategorie WHERE id = $delete_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php"); // Reload after suppression
        exit();
    } else {
        echo "Erreur de suppression: " . $conn->error;
    }
}

//update a kategorie
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $sql = "SELECT * FROM kategorie WHERE id = $edit_id";
    $result_edit = $conn->query($sql);
    $category = $result_edit->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_category'])) {
        $new_name = $_POST['edit_category'];
        $new_kuerzel = $_POST['edit_category_kuerzel'];
        $update_sql = "UPDATE categories SET name = '$new_name', kuerzel = '$new_kuerzel' WHERE id = $edit_id";
        if ($conn->query($update_sql) === TRUE) {
            header("Location: index.php"); // Reload the page
            exit();
        } else {
            echo "Error of update: " . $conn->error;
        }
    }
}

// retrieve baustein for a category
$bausteine_result = [];
if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];
    $sql_bausteine = "SELECT * FROM bausteine WHERE category_id = $category_id";
    $bausteine_result = $conn->query($sql_bausteine);
}

// delete a Bausteine
if (isset($_GET['delete_baustein_id'])) {
    $baustein_id = $_GET['delete_baustein_id'];

    // Request
    $sql_delete = "DELETE FROM bausteine WHERE id = $baustein_id";
    if ($conn->query($sql_delete) === TRUE) {
        header("Location: index.php?category_id=$category_id");
        exit();
    } else {
        echo "Erreur: " . $conn->error;
    }
}


// Add baustein to category
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_baustein']) && isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];
    $new_baustein_name = $_POST['new_baustein'];
    $new_baustein_description = $_POST['new_baustein_description'];

    $sql_baustein = "INSERT INTO bausteine (name,description, category_id) VALUES ('$new_baustein_name','$new_baustein_description', '$category_id')";
    if ($conn->query($sql_baustein) === TRUE) {
        header("Location: index.php?category_id=$category_id");
        exit();
    } else {
        echo "Erreur: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategorieliste</title>
    <!-- Inclure Bootstrap CSS -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Inclure Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">


</head>

<body>

    <div class="container mt-4">

        <div class="row">
            <div class="col-6">
                <h3>Kategorien</h3>

                <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addCategoryModal">Neue Kategorie hinzufügen</button>

                <!-- kategorie list-->
                <ul class="list-group mt-3">
                    <?php if ($result->num_rows > 0): ?>

                        <?php while ($row = $result->fetch_assoc()): ?>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="index.php?category_id=<?= $row['id'] ?>"><?= $row['name'] ?> (<?= $row['kuerzel'] ?>)</a>

                                <div class="btn-group ml-auto">

                                    <a href="index.php?delete_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Sind Sie sicher, dass Sie diese Kategorie löschen wollen ?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>

                                    <a href="edit_category.php?edit_id=<?= $row['id'] ?>" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li class="list-group-item"><strong>Keine Kategorie vorhanden</strong></li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="col-6">
                <h3>Bausteine</h3>
                <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addBausteinModal">Neue Baukasten hinzufügen</button>

                <?php if (isset($category_id)): ?>

                   <!-- list baukasten -->

                    <?php if ($bausteine_result->num_rows > 0): ?>
                        <ul class="list-group ">
                            <?php while ($baustein = $bausteine_result->fetch_assoc()): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center"><?= $baustein['name'] ?>

                                    <div class="btn-group ml-auto">

                                        <a href="index.php?delete_baustein_id=<?= $baustein['id'] ?>&category_id=<?= $category_id ?>" class="btn btn-danger btn-sm" title="löschen" onclick="return confirm('Sind Sie sicher, dass Sie diesen Baustein löschen wollen?');">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>

                                        <a href="index.php?edit_baustein_id=<?= $baustein['id'] ?>" class="btn btn-warning btn-sm" title="edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <a href="index.php?copy_baustein_id=<?= $baustein['id'] ?>" class="btn btn-info btn-sm" title="Copy">
                                            <i class="fas fa-copy"></i>
                                        </a>
                                    </div>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p>Kein Baustein für diese Kategorie vorhanden.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <!-- Modal to add a category -->

    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Neue Kategorie hinzufügen</h5>
                </div>

                <div class="modal-body">
                    <form method="POST">
                        <div class="form-group">
                            <label for="newCategoryName">Name</label>
                            <input type="text" class="form-control" id="newCategoryName" name="new_category" placeholder="Geben Sie ein Namen ein" required>
                        </div>
                        <div class="form-group">
                            <label for="newCategoryKuerzel">Kürzel </label>
                            <input type="text" class="form-control" id="newCategoryKuerzel" name="new_category_kuerzel" placeholder="Geben Sie ein Kürzel ein">
                        </div>
                        <button type="submit" class="btn btn-primary">Speichern</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Schließen</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Baustein  -->
    <div class="modal fade" id="addBausteinModal" tabindex="-1" aria-labelledby="addBausteinModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBausteinModalLabel">Neuen Baustein hinzufügen</h5>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="form-group">
                            <label for="newBausteinName">Name</label>
                            <input type="text" class="form-control" name="new_baustein" placeholder="Geben Sie ein Baustein ein" required>

                            <label for="description">Beschreibung</label>
                            <input type="text" class="form-control" name="description" placeholder="Geben Sie eine Beschreibung ein">

                        </div class="mt-4">
                        <button type="submit" class="btn btn-primary">Speichern</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Schließen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- Inclure jQuery  -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>

</html>