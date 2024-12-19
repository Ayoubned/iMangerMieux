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
                    const isCustom = entry.VALEUR_RATIO == null;
                    const valeurRatio = entry.VALEUR_RATIO !== null ? entry.VALEUR_RATIO : entry.CUSTOM_CALORIES;
                    $('#journalData').append(`
                        <tr>
                            <td>${entry.DATE}</td>
                            <td>${isCustom ? "-" : entry.NOM}</td>
                            <td>${isCustom ? "-" : entry.QUANTITE}</td>
                            <td>${valeurRatio}</td>
                            <td>${isCustom ? "Custom" : entry.LAB}</td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-btn" data-id="${entry.ID_JOURNAL}" ${isCustom ? "disabled" : ""}>Edit</button>
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
        minLength: 2
    });

    // Add Regular Entry
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
            success: function() {
                alert("Entry added successfully");
                $('#addEntryModal').modal('hide');
                fetchJournal();
            },
            error: function() {
                alert("Failed to add entry");
            }
        });
    });

    // Add Custom Calorie Entry
    $('#addCustomCalorieForm').submit(function(event) {
        event.preventDefault();
        let customData = {
            DATE: $('#customDate').val(),
            NOM: "custom calories",
            QUANTITE: 1,
            CUSTOM_CALORIES: $('#customCalories').val(),
            ID_UTILISATEUR: userId
        };

        $.ajax({
            url: API_BASE_URL + 'journal.php',
            method: 'POST',
            data: JSON.stringify(customData),
            contentType: "application/json",
            success: function() {
                alert("Custom calorie entry added successfully");
                $('#addCustomCalorieModal').modal('hide');
                fetchJournal();
            },
            error: function() {
                alert("Failed to add custom calorie entry");
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
                success: function() {
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
