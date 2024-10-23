<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Export</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .export-button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
        }
        .result {
            margin-top: 20px;
        }
        .loading {
            display: none;
            font-size: 16px;
            margin-top: 10px;
        }
        .progress {
            margin-top: 10px;
            width: 100%;
            background-color: #f3f3f3;
            border-radius: 5px;
        }
        .progress-bar {
            width: 0%;
            height: 25px;
            background-color: #4caf50;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <h1>Database Export Tool</h1>
    <button class="export-button" id="exportButton">
        <i class="fas fa-database"></i> Export Database
    </button>

    <div class="loading" id="loadingMessage">Exporting... Please wait.</div>
    <div class="progress" id="progressContainer" style="display: none;">
        <div class="progress-bar" id="progressBar"></div>
    </div>
    <div class="result" id="resultMessage"></div>

    <script>
        document.getElementById('exportButton').addEventListener('click', function() {
            // Show loading message and progress container
            document.getElementById('loadingMessage').style.display = 'block';
            document.getElementById('progressContainer').style.display = 'block';
            document.getElementById('resultMessage').innerHTML = '';
            document.getElementById('progressBar').style.width = '0%';

            // Start the export process
            fetch('backup', {
                method: 'POST',
                body: new URLSearchParams({ export: true })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Show completion message
                    document.getElementById('resultMessage').innerHTML = data.message;
                } else {
                    document.getElementById('resultMessage').innerHTML = "Error: " + data.message;
                }
            })
            .catch(error => {
                document.getElementById('resultMessage').innerHTML = "Error: " + error;
            })
            .finally(() => {
                // Hide loading message
                document.getElementById('loadingMessage').style.display = 'none';
                clearInterval(progressInterval); // Stop checking progress
            });

            // Function to check progress
            const progressInterval = setInterval(() => {
                fetch('progress.txt')
                    .then(response => response.json())
                    .then(progress => {
                        if (progress.total_tables > 0) {
                            const percent = (progress.completed_tables / progress.total_tables) * 100;
                            document.getElementById('progressBar').style.width = percent + '%';
                        }
                    });
            }, 1000); // Check progress every second
        });
    </script>

</body>
</html>
