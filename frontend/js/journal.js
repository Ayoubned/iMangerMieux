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
                                <button class="btn btn-sm btn-warning edit-btn" data-id="${entry.id}">Edit</button>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="${entry.id}">Delete</button>
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
                    response($.map(data, function(item) {
                        return {
                            label: item.NOM,     
                            value: item.ID_ALIMENT, 
                            nom: item.NOM         
                        };
                    }));
                },
                error: function() {
                    alert("Failed to fetch aliment suggestions");
                }
            });
        },
        minLength: 2, 
        select: function(event, ui) {
            $('#aliment').val(ui.item.value); 
            $('#editAliment').val(ui.item.nom); 
            return false; 
        }
    });

    // Add Entry
    $('#addEntryForm').submit(function(event) {
        event.preventDefault();
        let entryData = {
            DATE: $('#date').val(),
            ID_ALIMENT: $('#aliment').val(),
            QUANTITE: $('#quantity').val(),
            ID_UTILISATEUR: sessionStorage.getItem('user_id'),
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
            url: API_BASE_URL + 'journal.php?id=' + entryId,
            method: 'GET',
            success: function(entry) {
                $('#editDate').val(entry.date);
                $('#editAliment').val(entry.aliment);
                $('#editQuantity').val(entry.quantity);
                $('#editCalories').val(entry.calories);
                $('#editType').val(entry.type);
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
            date: $('#editDate').val(),
            aliment: $('#editAliment').val(),
            quantity: $('#editQuantity').val(),
            calories: $('#editCalories').val(),
            type: $('#editType').val()
        };

        $.ajax({
            url: API_BASE_URL + 'journal.php?id=' + entryId,
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
                url: API_BASE_URL + 'journal.php?id=' + entryId,
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
