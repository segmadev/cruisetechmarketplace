        function printDiv(divId, title) {
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
        
            // Hide elements with the "print-ignore" class
            hidePrintIgnore();
        
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
        