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

    // Edit and Delete functionality remains the same, but no session storage
});
