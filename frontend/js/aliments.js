$(document).ready(function() {
    const nutrientOptions = [
        'Eau (g/100g)', 'Fer (mg/100g)', 'Zinc (mg/100g)', 'Sucres (g/100g)', 
        'Lipides (g/100g)', 'Calcium (mg/100g)', 'Energie (kj/100g)', 
        'Glucides (g/100g)', 'Potassium (mg/100g)', 'Protéines (g/100g)', 
        'Magnésium (mg/100g)', 'Vitamine C (mg/100g)', 'Vitamine E (mg/100g)', 
        'Vitamine D (µg/100g)', 'Cholestérol (mg/100g)', 'Fibres alimentaires (g/100g)'
    ];

    let alimentData = [];

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

    // Populate, Edit, Save, and nutrient selector handling code remains unchanged
    fetchAliments();
});
