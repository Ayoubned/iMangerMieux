<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Journal</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
</head>

<body>

    <div class="container">
        <?php include 'navbar.php'; ?>

        <h2 class="mt-4">Food Journal</h2>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addEntryModal">Add New Entry</button>

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-md-4">
                <label for="filterDate">Filter by Date</label>
                <input type="date" id="filterDate" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="filterType">Filter by Type</label>
                <select id="filterType" class="form-control">
                    <option value="">All Types</option>
                    <option value="Fruit">Fruit</option>
                    <option value="Vegetable">Vegetable</option>
                    <option value="Meat">Meat</option>
                    <!-- Add more options as needed -->
                </select>
            </div>
            <div class="col-md-4">
                <button class="btn btn-secondary mt-4" onclick="filterJournal()">Apply Filters</button>
            </div>
        </div>

        <!-- Journal Table -->
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
            <tbody>
                <?php
                // Sample data (replace with database query in production)
                $entries = [
                    ['date' => '2024-10-28', 'aliment' => 'Apple', 'quantity' => 150, 'calories' => 78, 'type' => 'Fruit'],
                    ['date' => '2024-10-28', 'aliment' => 'Chicken Breast', 'quantity' => 200, 'calories' => 330, 'type' => 'Meat'],
                    // Add more sample entries here
                ];

                foreach ($entries as $entry) {
                    echo "<tr>
                <td>{$entry['date']}</td>
                <td>{$entry['aliment']}</td>
                <td>{$entry['quantity']} g</td>
                <td>{$entry['calories']} kcal</td>
                <td>{$entry['type']}</td>
                <td>
                  <button class='btn btn-sm btn-warning edit-btn'>Edit</button>
                  <button class='btn btn-sm btn-danger delete-btn'>Delete</button>
                </td>
              </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Add Entry Modal -->
    <div class="modal fade" id="addEntryModal" tabindex="-1" role="dialog" aria-labelledby="addEntryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEntryModalLabel">Add New Journal Entry</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addEntryForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="entryDate">Date</label>
                            <input type="date" class="form-control" id="entryDate" required>
                        </div>
                        <div class="form-group">
                            <label for="entryAliment">Aliment</label>
                            <input type="text" class="form-control" id="entryAliment" required>
                        </div>
                        <div class="form-group">
                            <label for="entryQuantity">Quantity (g)</label>
                            <input type="number" class="form-control" id="entryQuantity" required>
                        </div>
                        <div class="form-group">
                            <label for="entryCalories">Calories</label>
                            <input type="number" class="form-control" id="entryCalories" required>
                        </div>
                        <div class="form-group">
                            <label for="entryType">Type</label>
                            <input type="text" class="form-control" id="entryType" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Entry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="js/journal.js"></script>

</body>

</html>