$(document).ready(function() {
    // Initialize DataTables
    $('#alimentsTable').DataTable();
  
    // Placeholder event handlers for adding, editing, and deleting
    $('#addAlimentForm').on('submit', function(e) {
      e.preventDefault();
      alert('Aliment added!'); // Replace with AJAX call to add aliment to database
      $('#addAlimentModal').modal('hide');
    });
  
    $('.edit-btn').on('click', function() {
      alert('Edit function not implemented'); // Placeholder
    });
  
    $('.delete-btn').on('click', function() {
      alert('Delete function not implemented'); // Placeholder
    });
  });