<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Journal</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/journal.css">
    <script>
        const API_BASE_URL = "<?php echo API_BASE_URL; ?>";
        const userId = <?php echo json_encode($_SESSION['user_id']); ?>;
    </script>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Food Journal</h2>
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addEntryModal">Add New Entry</button>
    
    <table id="journalTable" class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Aliment</th>
                <th>Quantity</th>
                <th>Calories</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="journalData"></tbody>
    </table>
</div>

<div id="addEntryModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addEntryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEntryModalLabel">Add Journal Entry</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addEntryForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="form-group">
                        <label for="aliment">Aliment</label>
                        <input type="text" class="form-control" id="aliment" name="aliment" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Entry</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Entry Modal -->
<div id="editEntryModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editEntryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEntryModalLabel">Edit Journal Entry</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editEntryForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editDate">Date</label>
                        <input type="date" class="form-control" id="editDate" name="date" required>
                    </div>
                    <div class="form-group">
                        <label for="editAliment">Aliment</label>
                        <input type="text" class="form-control" id="editAliment" name="aliment" required>
                    </div>
                    <div class="form-group">
                        <label for="editQuantity">Quantity</label>
                        <input type="number" class="form-control" id="editQuantity" name="quantity" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- jQuery and Bootstrap Bundle (with Popper) -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="js/journal.js"></script>
<style>
    .ui-autocomplete {
        z-index: 1051; 
    }
</style>
</body>
</html>
