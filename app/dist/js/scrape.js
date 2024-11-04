$(document).ready(function () {
    $('#lineForm').submit(function (e) {
      e.preventDefault(); // Prevent form refresh
      $('#displayresult').html("");
      const data = $('#textarea').val().split('\n').filter(line => line.trim() !== ''); // Split & filter empty lines
      $('#response').show().html('<div class="alert alert-info">Processing...</div>');
  
      let index = 0; // Start with the first line
  
      function sendNextLine() {
        if (index < data.length) {
          const line = data[index]; // Current line
          
          $.ajax({
            url: 'passer', // PHP script URL
            type: 'POST',
            data: {
              instalink: line, 
              page: 'instagram'
            },
            success: function (response) {
              // Update response areas with success feedback
              $('#response').html('Processing Line ' + (index + 1) + ' of ' + data.length);
              $('#displayresult').append('<p>' + response + '</p>'); // Append result
  
              // Proceed to the next line
              index++;
              sendNextLine();
            },
            error: function (error) {
              console.error('Error submitting line:', line, error);
              $('#response').html('<div class="alert alert-danger">Error on Line ' + (index + 1) + '.</div>');
            }
          });
        } else {
          // All lines processed
          $('#response').html('<div class="alert alert-success">All data submitted successfully!</div>');
        }
      }
  
      // Start processing the first line
      sendNextLine();
    });
  });
  