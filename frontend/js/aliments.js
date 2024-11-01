$(document).ready(function() {
  let ratioKeys = [];

  function fetchAliments() {
      $.ajax({
          url: API_BASE_URL + 'aliments.php',
          method: 'GET',
          success: function(data) {
              // Collect all unique ratio keys
              ratioKeys = Array.from(new Set(data.flatMap(aliment => Object.keys(JSON.parse(aliment.RATIOS) || {}))));

              // Dynamically add headers for each ratio key
              $('#tableHead').html(`
                  <tr>
                      <th>Name</th>
                      ${ratioKeys.map(ratio => `<th>${ratio}</th>`).join('')}
                      <th>Actions</th>
                  </tr>
              `);

              // Populate the table with aliments and their respective ratio values
              $('#alimentsData').html('');
              data.forEach(aliment => {
                  const parsedRatios = JSON.parse(aliment.RATIOS);
                  const ratioValues = ratioKeys.map(key => `<td>${parsedRatios[key] || 'N/A'}</td>`);
                  $('#alimentsData').append(`
                      <tr>
                          <td>${aliment.NOM}</td>
                          ${ratioValues.join('')}
                          <td>
                              <button class="btn btn-sm btn-warning edit-btn" data-id="${aliment.ID_ALIMENT}">Edit</button>
                              <button class="btn btn-sm btn-danger delete-btn" data-id="${aliment.ID_ALIMENT}">Delete</button>
                          </td>
                      </tr>
                  `);
              });

              // Initialize or refresh DataTable
              if (!$.fn.DataTable.isDataTable('#alimentsTable')) {
                  $('#alimentsTable').DataTable();
              } else {
                  $('#alimentsTable').DataTable().destroy();
                  $('#alimentsTable').DataTable();
              }
          },
          error: function() {
              alert("Failed to load aliments");
          }
      });
  }

  fetchAliments();
});
