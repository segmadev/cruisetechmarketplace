<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download PDF with White Background</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <style>
        /* Ensure the content has a white background */
        #content {
            padding: 20px;
            border: 1px solid #000;
            background-color: white; /* Set white background */
        }
    </style>
</head>
<body>
    <div id="content">
        <h1>My Content</h1>
        <p>This is the content of the div that will be downloaded as a PDF.</p>
        <p class="print-ignore">This content will be ignored and not included in the PDF.</p>
        <ul>
            <li>Point 1</li>
            <li>Point 2</li>
            <li class="print-ignore">This point will be ignored in the PDF.</li>
            <li>Point 3</li>
        </ul>
    </div>

    <button onclick="downloadPDF()">Download as PDF</button>

    <script>
        // Function to temporarily hide elements with the class "print-ignore"
        function hidePrintIgnore() {
            const elements = document.querySelectorAll('.print-ignore');
            elements.forEach(element => {
                element.style.display = 'none';
            });
        }

        // Function to restore visibility of elements with the class "print-ignore"
        function showPrintIgnore() {
            const elements = document.querySelectorAll('.print-ignore');
            elements.forEach(element => {
                element.style.display = '';
            });
        }

        // Function to download the content as a PDF with a white background and selectable text
        function downloadPDF() {
            // First, hide all elements with the "print-ignore" class
            hidePrintIgnore();

            // Generate and download the PDF
            const element = document.getElementById('content');
            
            const options = {
                margin: 1,
                filename: 'download.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, backgroundColor: 'white' },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };
            
            html2pdf().from(element).set(options).save().then(() => {
                // After the PDF is generated, restore the visibility of the "print-ignore" elements
                showPrintIgnore();
            });
        }
    </script>
</body>
</html>
