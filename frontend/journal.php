<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Journal</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <?php include 'config.php'; ?>
    <script>
        const API_BASE_URL = "<?php echo API_BASE_URL; ?>";
    </script>
</head>
<body>
    <div class="container">

        <h2 class="mt-4">Food Journal</h2>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addEntryModal">Add New Entry</button>

        <table id="journalTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Aliment</th>
                    <th>Quantity (g)</th>
                    <th>Calories</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="journalData"></tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="js/journal.js"></script>
</body>
</html>
