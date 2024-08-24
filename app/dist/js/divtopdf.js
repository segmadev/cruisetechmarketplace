
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

function printDiv(divId, title) {
            
        
            // Hide elements with the "print-ignore" class
            hidePrintIgnore();
            title = sanitizeFileName(title);
            let mywindow = window.open('', 'PRINT', 'height=650,width=900,top=100,left=150');
        
            mywindow.document.write(`<html><head><title>${title}</title>`);
            mywindow.document.write('</head><body >');
            mywindow.document.write(document.getElementById(divId).innerHTML);
            mywindow.document.write('</body></html>');
        
            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/
        
            // Restore visibility of the "print-ignore" elements
            showPrintIgnore();
        
            mywindow.print();
            // setTimeout(mywindow.close(), 3000);
            // mywindow.close();
        
            return true;
        }
        

        function downloadText(divId, fileName) {
            // Get the div content
            const div = document.getElementById(divId);
        
            // Clone the div to manipulate content without affecting the original
            const cloneDiv = div.cloneNode(true);
        
            // Remove all elements with the class 'print-ignore'
            const elementsToIgnore = cloneDiv.getElementsByClassName('print-ignore');
            while (elementsToIgnore.length > 0) {
                elementsToIgnore[0].parentNode.removeChild(elementsToIgnore[0]);
            }
        
            // Get the cleaned content and normalize the spaces
            let content = cloneDiv.innerText;
        
            // Remove unnecessary spaces and ensure proper alignment
            content = content
            .replace(/^\s+|\s+$/gm, '') // Trim spaces at the start and end of each line
            // .replace(/\s+/g, ' ')       // Replace multiple spaces with a single space
                // .replace(/\n\s*\n/g, '\n'); // Remove empty lines
        
            // Clean up the file name
            const cleanFileName = sanitizeFileName(fileName);
        
            // Create a blob with the content as plain text
            const blob = new Blob([content], { type: 'text/plain' });
        
            // Create a link element
            const link = document.createElement('a');
        
            // Create a URL for the blob and set it as the href attribute of the link
            link.href = URL.createObjectURL(blob);
        
            // Set the download attribute with the sanitized file name
            link.download = `${cleanFileName}.txt`;
        
            // Append the link to the document body
            document.body.appendChild(link);
        
            // Programmatically click the link to trigger the download
            link.click();
        
            // Remove the link from the document
            document.body.removeChild(link);
        }

        function sanitizeFileName(name) {
            return name
                .trim() // Remove leading and trailing spaces
                .replace(/[^\w\-]/g, '_') // Replace special characters with an underscore
                .replace(/[\s]+/g, '_') // Replace spaces with an underscore
                .replace(/_+/g, '_') // Replace multiple underscores with a single one
                .toLowerCase(); // Convert to lowercase (optional)
        }