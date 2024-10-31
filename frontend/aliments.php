<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aliments</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
</head>

<body>

    <div class="container">
        <?php include 'navbar.php'; ?>

        <h2 class="mt-4">Aliments List</h2>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addAlimentModal">Add New Aliment</button>

        <!-- Aliments Table -->
        <table id="alimentsTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Calories</th>
                    <th>Proteins</th>
                    <th>Carbs</th>
                    <th>Fats</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Sample data (replace with database query in production)
                $aliments = [
                    ['name' => 'Apple', 'type' => 'Fruit', 'calories' => 52, 'proteins' => 0.3, 'carbs' => 14, 'fats' => 0.2],
                    ['name' => 'Chicken Breast', 'type' => 'Meat', 'calories' => 165, 'proteins' => 31, 'carbs' => 0, 'fats' => 3.6],
                    // Add more sample aliments here
                ];

                foreach ($aliments as $aliment) {
                    echo "<tr>
                <td>{$aliment['name']}</td>
                <td>{$aliment['type']}</td>
                <td>{$aliment['calories']} kcal</td>
                <td>{$aliment['proteins']} g</td>
                <td>{$aliment['carbs']} g</td>
                <td>{$aliment['fats']} g</td>
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

    <!-- Add Aliment Modal -->
    <div class="modal fade" id="addAlimentModal" tabindex="-1" role="dialog" aria-labelledby="addAlimentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAlimentModalLabel">Add New Aliment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addAlimentForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="alimentName">Name</label>
                            <input type="text" class="form-control" id="alimentName" required>
                        </div>
                        <div class="form-group">
                            <label for="alimentType">Type</label>
                            <input type="text" class="form-control" id="alimentType" required>
                        </div>
                        <div class="form-group">
                            <label for="alimentCalories">Calories</label>
                            <input type="number" class="form-control" id="alimentCalories" required>
                        </div>
                        <div class="form-group">
                            <label for="alimentProteins">Proteins (g)</label>
                            <input type="number" class="form-control" id="alimentProteins" required>
                        </div>
                        <div class="form-group">
                            <label for="alimentCarbs">Carbs (g)</label>
                            <input type="number" class="form-control" id="alimentCarbs" required>
                        </div>
                        <div class="form-group">
                            <label for="alimentFats">Fats (g)</label>
                            <input type="number" class="form-control" id="alimentFats" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Aliment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="js/aliments.js"></script>

</body>

</html>