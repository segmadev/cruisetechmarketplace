const qrcodelist = document.querySelectorAll('#genqr');
    
qrcodelist.forEach(element => {
    get_qr_code(element);
   
});

function get_qr_code(element) {
    data = element.getAttribute("data-info");
    showhere = element.getAttribute("data-id");
    // Get the container element
    var container = document.getElementById(showhere);
    // Text or data you want to encode in the QR code
    // QR code options (optional)
    var options = {
        // width: 200, // Width of the QR code (pixels)
        // height: 200, // Height of the QR code (pixels)
    };

    // Generate the QR code
    var qrcode = new QRCode(container, options);
    
    qrcode.makeCode(data);
}