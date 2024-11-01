$(document).ready(function() {
  $('#journalTable').DataTable();

  function fetchJournal() {
      $.ajax({
          url: API_BASE_URL + 'journal.php',
          method: 'GET',
          success: function(data) {
              $('#journalData').html('');
              data.forEach(entry => {
                  $('#journalData').append(`
                      <tr>
                          <td>${entry.date}</td>
                          <td>${entry.aliment}</td>
                          <td>${entry.quantity}</td>
                          <td>${entry.calories}</td>
                          <td>${entry.type}</td>
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
});
