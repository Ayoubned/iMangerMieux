$(document).ready(function() {
    // Initialize DataTable
    $('#journalTable').DataTable();

    // Fetch and display journal entries
    function fetchJournal() {
        $.ajax({
            url: API_BASE_URL + 'journal.php',
            method: 'GET',
            success: function(data) {
                $('#journalData').html('');
                data.forEach(entry => {
                    $('#journalData').append(`
                        <tr>
                            <td>${entry.DATE}</td>
                            <td>${entry.NOM}</td>
                            <td>${entry.QUANTITE}</td>
                            <td>${entry.VALEUR_RATIO}</td>
                            <td>${entry.LAB}</td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-btn" data-id="${entry.ID_JOURNAL}">Edit</button>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="${entry.ID_JOURNAL}">Delete</button>
                            </td>
                        </tr>
                    `);
                });
            },
            error: function() {
                alert("Failed to load journal entries");
            }
        });
    }

    fetchJournal();

    // Autocomplete for aliment field in Add Entry Modal
    $('#aliment').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: API_BASE_URL + 'aliments.php',
                method: 'GET',
                data: { aliment_name: request.term },
                success: function(data) {
                    response(data);
                },
                error: function() {
                    alert("Failed to fetch aliment suggestions");
                }
            });
        },
        minLength: 2  // Start searching after 2 characters
    });

    // Autocomplete for aliment field in Edit Entry Modal
    $('#editAliment').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: API_BASE_URL + 'aliments.php',
                method: 'GET',
                data: { aliment_name: request.term },
                success: function(data) {
                    response(data);
                },
                error: function() {
                    alert("Failed to fetch aliment suggestions");
                }
            });
        },
        minLength: 2  // Start searching after 2 characters
    });

    // Add Entry
    $('#addEntryForm').submit(function(event) {
        event.preventDefault();
        let entryData = {
            DATE: $('#date').val(),
            NOM: $('#aliment').val(),
            QUANTITE: $('#quantity').val(),
            ID_UTILISATEUR: userId
        };

        $.ajax({
            url: API_BASE_URL + 'journal.php',
            method: 'POST',
            data: JSON.stringify(entryData),
            contentType: "application/json",
            success: function(response) {
                alert("Entry added successfully");
                $('#addEntryModal').modal('hide');
                fetchJournal();
            },
            error: function() {
                alert("Failed to add entry");
            }
        });
    });

    // Edit Entry
    $(document).on('click', '.edit-btn', function() {
        let entryId = $(this).data('id');
        
        $.ajax({
            url: API_BASE_URL + 'journal.php?ID=' + entryId,
            method: 'GET',
            success: function(entry) {
                $('#editDate').val(entry.DATE);
                $('#editAliment').val(entry.NOM);
                $('#editQuantity').val(entry.QUANTITE);
                $('#editEntryModal').modal('show');
                $('#editEntryForm').data('id', entryId);
            },
            error: function() {
                alert("Failed to load entry details");
            }
        });
    });

    $('#editEntryForm').submit(function(event) {
        event.preventDefault();
        let entryId = $(this).data('id');
        let updatedData = {
            DATE: $('#editDate').val(),
            NOM: $('#editAliment').val(),
            QUANTITE: $('#editQuantity').val()
        };

        $.ajax({
            url: API_BASE_URL + 'journal.php?ID=' + entryId,
            method: 'PUT',
            data: JSON.stringify(updatedData),
            contentType: "application/json",
            success: function(response) {
                alert("Entry updated successfully");
                $('#editEntryModal').modal('hide');
                fetchJournal();
            },
            error: function() {
                alert("Failed to update entry");
            }
        });
    });

    // Delete Entry
    $(document).on('click', '.delete-btn', function() {
        let entryId = $(this).data('id');
        if (confirm("Are you sure you want to delete this entry?")) {
            $.ajax({
                url: API_BASE_URL + 'journal.php?ID=' + entryId,
                method: 'DELETE',
                success: function(response) {
                    alert("Entry deleted successfully");
                    fetchJournal();
                },
                error: function() {
                    alert("Failed to delete entry");
                }
            });
        }
    });
});
