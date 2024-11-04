<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aliments</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <?php include 'config.php'; ?>
    <script>
        const API_BASE_URL = "<?php echo API_BASE_URL; ?>";
    </script>

    <div class="container">

        <h2 class="mt-4">Aliments List</h2>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addAlimentModal">Add New Aliment</button>

        <table id="alimentsTable" class="table table-striped table-bordered">
            <thead id="tableHead">
                <tr>
                    <th>Name</th>
                    <!-- Additional columns for each ratio will be added here dynamically -->
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="alimentsData"></tbody>
        </table>
    </div>

    <div class="modal fade" id="addAlimentModal" tabindex="-1" role="dialog" aria-labelledby="addAlimentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAlimentModalLabel">Add New Aliment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addAlimentForm">
                        <div class="form-group">
                            <label for="alimentName">Aliment Name</label>
                            <input type="text" class="form-control" id="alimentName" name="alimentName" required>
                        </div>
                        <!-- Additional fields can be added here for each ratio if needed -->
                        <button type="submit" class="btn btn-primary">Save Aliment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="js/aliments.js"></script>
</body>
</html>
