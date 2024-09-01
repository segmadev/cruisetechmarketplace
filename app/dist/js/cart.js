const cartContainer = $('#cart-container');
const loginsContainer = $('#logins-container');
let offset = 0;
const limit = 20;
let fetching = false;
let accountID = cartContainer.data('account-id');
let amount = document.getElementById('amountvalue').value;
let platfromImage = document.getElementById('platfromImage').src;

// Initialize cart
let cart = JSON.parse(getCookie('cart' + accountID) || '[]');

// Function to update the cart cookie
function updateCartCookie(cart) {
    document.cookie = `cart${accountID}=${JSON.stringify(cart)}; path=/`;
}

// Function to get a cookie value
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}

// Function to shorten preview link
function shortenLink(link, maxLength = 15) {
    return link.length > maxLength ? link.substring(0, maxLength) + '...' : link;
}

// Function to render a login item
function renderLogin(login, isInCart = false) {
    return `
        <div class="col single-note-item all-category p-0 ms-2 col-lg-4 col-sm-4" style='min-width: 250px!important' data-ID="${login.ID}">
            <div class="card card-body bg-light p-0 p-2 border-1 mb-1 w-100">
                <div class="d-flex m-0 justify-content-between">
                    <div class="d-flex m-0">
                        <div>
                            <img  id="platfromImage" class="img-fluid rounded-circle" width="20"
                                src="${platfromImage}" alt="">
                        </div>
                        <div class="ms-1">
                            <h6 class="note-title w-100 mb-0">
                                <p class="m-0">${shortenLink(login.username)}</p>
                            </h6>
                            <p class="note-date fs-2 m-0">${"N" + parseInt(amount).toLocaleString('en-US')}</p>
                        </div>
                    </div>
                    <div class="ms-2">
                        <a target="_BLANK" href="${addHttpsToLink(login.preview_link)}" class="link me-1 btn btn-sm bg-blue">
                            <i class="ti ti-eye fs-4 favourite-note"></i>
                        </a>
                        <a href="#" onclick="toggleCart(${login.ID}, '${login.username}', '${login.preview_link}'); return false;" class="link me-1 btn btn-sm bg-primary text-white">
                            <i class="ti ${isInCart ? 'ti-minus' : 'ti-plus'} fs-4 favourite-note"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function addHttpsToLink(link) {
    // Check if the link doesn't already start with 'http://' or 'https://'
    if (!link.startsWith('http://') && !link.startsWith('https://')) {
        // Prepend 'https://' to the link
        link = 'https://' + link;
    }
    return link;
}

// Function to update the cart count in the HTML
function updateCartCount() {
    if(cart.length > 0)  document.getElementById("qtynumberDiv").style.display = "none";
    if(cart.length == 0)  document.getElementById("qtynumberDiv").style.display = "flex";
    document.getElementById("qtynumber").value = cart.length;
    document.getElementById("cartDetails").innerHTML = JSON.stringify(cart);
    document.getElementById("DisplayAmount").innerHTML = "N" + (parseInt(amount) * cart.length).toLocaleString('en-US');
    // (parseInt(amount) * currentValue).toLocaleString('en-US')

    // $('#qtynumberere').value(cart.length);
}

// Global function to add or remove login from cart
window.toggleCart = function(ID, username, previewLink) {
    const itemIndex = cart.findIndex(item => item.ID === ID);
    if (itemIndex > -1) {
        cart.splice(itemIndex, 1); // Remove from cart
        updateCartCookie(cart);
        updateCartCount(); // Update cart count
        $(`[data-ID=${ID}]`).remove(); // Remove from cart container

        // Add back to logins container
        loginsContainer.append(renderLogin({ ID, username, preview_link: previewLink }));
    } else {
        cart.push({ ID, username, preview_link: previewLink }); // Add to cart
        updateCartCookie(cart);
        updateCartCount(); // Update cart count
        $(`[data-ID=${ID}]`).remove(); // Remove from logins container
    }

    // Re-render the cart
    updateCartDisplay();
};

// Function to update cart display
function updateCartDisplay() {
    console.log(cart.length);
    if(cart.length == 0) {
        cartContainer.html('<small><b>Any Account Added will be displayed here.</b></small>');
    }
    if(cart.length > 0) cartContainer.html(cart.map(login => renderLogin(login, true)).join(''));
    updateCartCount(); // Ensure the count is updated whenever the cart is updated
}

// Function to fetch logins from server
function fetchLogins(accountID) {
    if (fetching) return;
    fetching = true;

    const excludedIDs = cart.map(item => item.ID);

    $.ajax({
        url: 'passer',
        method: 'POST',
        data: { 
            page: 'account',
            accountID: accountID, 
            limit: limit, 
            offset: offset, 
            exclude: excludedIDs 
        },
        success: function(response) {
            if (response.logins && response.logins.length > 0) {
                const logins = response.logins;
                const filteredLogins = logins.filter(login => !excludedIDs.includes(login.ID));
                loginsContainer.append(filteredLogins.map(login => renderLogin(login)).join(''));

                offset += limit;
                fetching = false;

                // Fetch the next batch immediately
                fetchLogins(accountID);
            } else {
                // No more data to fetch
                fetching = false;
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
            fetching = false;
        }
    });
}

// Function to handle the "Buy Now" button click
$('#buy-now').on('click', function() {
    if (cart.length === 0) {
        alert('Your cart is empty!');
        return;
    }

    // Serialize the cart data
    const cartData = JSON.stringify(cart);

    // Send the cart data to the server
    $.ajax({
        url: 'passer',
        method: 'POST',
        data: { 
            page: 'account',
            accountID: accountID,
            cart: cartData,
            buynow: "true"
        },
        success: function(response) {
            if (response.success) {
                alert('Purchase successful!');
                // Clear the cart after successful purchase
                cart = [];
                updateCartCookie(cart);
                updateCartDisplay();
            } else {
                alert('Purchase failed. Please try again.');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
            alert('There was an error processing your request.');
        }
    });
});

function emptyCartAndRedirect(redirectUrl = null, holder = 9000) {
    console.log("redirectUrl");
    cart.length = 0;
    // Update the cart cookie
    updateCartCookie(cart);
    // Update the cart display (to clear the cart on the UI)
    updateCartDisplay();
    // Update the cart count (if you have a cart count display)
    updateCartCount();
    if(redirectUrl != null) {
        setTimeout(() => {
            window.location.replace(redirectUrl);
        }, parseInt(holder)); 
    }
}

// Initial setup
updateCartDisplay();
fetchLogins(accountID);
