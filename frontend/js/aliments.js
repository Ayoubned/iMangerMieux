$(document).ready(function() {
    const nutrientOptions = [
        'Eau (g/100g)', 'Fer (mg/100g)', 'Zinc (mg/100g)', 'Sucres (g/100g)', 
        'Lipides (g/100g)', 'Calcium (mg/100g)', 'Energie (kj/100g)', 
        'Glucides (g/100g)', 'Potassium (mg/100g)', 'Protéines (g/100g)', 
        'Magnésium (mg/100g)', 'Vitamine C (mg/100g)', 'Vitamine E (mg/100g)', 
        'Vitamine D (µg/100g)', 'Cholestérol (mg/100g)', 'Fibres alimentaires (g/100g)'
    ];

    let alimentData = []; // Variable to store data fetched from the API
    // Function to populate the table with data based on the selected nutrient
    function populateTable(nutrient) {
        $('#alimentsData').empty(); // Clear current rows
        alimentData.forEach(aliment => {
            const parsedRatios = JSON.parse(aliment.RATIOS);
            const nutrientValue = parsedRatios[nutrient] ? parseFloat(parsedRatios[nutrient]).toFixed(2) : 'N/A';
            $('#alimentsData').append(`
                <tr data-id="${aliment.ID_ALIMENT}">
                    <td class="editable-name">${aliment.NOM}</td>
                    <td>${parsedRatios["Energie (kcal/100g)"].toFixed(2)}</td>
                    <td>${nutrient}</td>
                    <td class="nutrient-value">${nutrientValue}</td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-btn" data-id="${aliment.ID_ALIMENT}">Edit</button>
                    </td>
                </tr>
            `);
        });
        if ($.fn.DataTable.isDataTable('#alimentsTable')) {
            $('#alimentsTable').DataTable().destroy();
        }
        $('#alimentsTable').DataTable();
    }

    function fetchAliments() {
        $.ajax({
            url: API_BASE_URL + 'aliments.php',
            method: 'GET',
            success: function(data) {
                alimentData = data;
                $('#tableHead').html(`
                    <tr>
                        <th>Name</th>
                        <th>Calories (kcal/100g)</th>
                        <th>
                            Choose Nutrient
                            <select id="nutrientSelector" class="form-control">
                                ${nutrientOptions.map(nutrient => `<option value="${nutrient}">${nutrient}</option>`).join('')}
                            </select>
                        </th>
                        <th>Nutrient Value</th>
                        <th>Actions</th>
                    </tr>
                `);

                populateTable(nutrientOptions[0]);
            },
            error: function() {
                alert("Failed to load aliments");
            }
        });
    }
 // Handle edit button click
 $('#alimentsData').on('click', '.edit-btn', function() {
    const row = $(this).closest('tr');
    const id = $(this).data('id');
    const currentName = row.find('.editable-name').text();
    // Make name cell editable
    row.find('.editable-name').html(`<input type="text" class="form-control edit-name" value="${currentName}" />`);
    $(this).text('Save').removeClass('edit-btn btn-warning').addClass('save-btn btn-success');
});
// Handle save button click after editing
$('#alimentsData').on('click', '.save-btn', function() {
    const row = $(this).closest('tr');
    const id = $(this).data('id');
    const newName = row.find('.edit-name').val();
    $.ajax({
        url: `${API_BASE_URL}aliments.php?ID_ALIMENT=${id}`,
        method: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify({ NOM: newName }),
        success: function(response) {
            alert(`Aliment updated: ${response.NOM}`);
            row.find('.editable-name').text(newName); // Update displayed name
            $(this).text('Edit').removeClass('save-btn btn-success').addClass('edit-btn btn-warning');
        },
        error: function() {
            alert("Failed to update aliment");
        }
    });
});
$('#tableHead').on('change', '#nutrientSelector', function() {
    const selectedNutrient = $(this).val();
    populateTable(selectedNutrient);
});
    // Populate, Edit, Save, and nutrient selector handling code remains unchanged
    fetchAliments();
});
