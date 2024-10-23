const cartContainer = $('#cart-container');
const loginsContainer = $('#logins-container');
let offset = 0;
const limit = 20;
let fetching = false;
let accountID = cartContainer.data('account-id');
let amount = document.getElementById('amountvalue').value;
let platfromImage = document.getElementById('platfromImage').src;
let copybtn = document.getElementById("copybtn");
let clearbtn = document.getElementById("clearbtn");

let discountAppliedOnce = false;

add = document.getElementById("addAmount");
minus = document.getElementById("minusAmount");
var qtynumber = document.getElementById("qtynumber");

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

// let cart = []; // Example cart: [{id: 1, name: 'item1'}, {id: 2, name: 'item2'}]
 // Example: [{id: 1, name: 'item1'}, {id: 2, name: 'item2'}]
// let discountAppliedOnce = false; // Track if the discount was applied after page load


function applyDiscount(totalAmount) {
    let discountData = document.getElementById("discountData").value;
    if (!discountData) return { amount: totalAmount, discountApplied: false, discountName: "" };

    discountData = JSON.parse(atob(discountData));
    const qtynumberValue = parseInt(qtynumber.value);

    if (qtynumberValue >= parseInt(discountData.no_order) && discountData.totalCredit > 0) {
        let discountedAmount = totalAmount;
        let discountValue = "";
        let discountName = discountData.name;

        if (discountData.discount_type === "percentage") {
            discountedAmount -= totalAmount * (parseFloat(discountData.discount) / 100);
            discountValue = `${discountData.discount}% off`;
        } else if (discountData.discount_type === "fixed") {
            discountedAmount -= parseFloat(discountData.discount);
            discountValue = `N${parseFloat(discountData.discount).toLocaleString("en-US")} off`;
        }

        if (!discountAppliedOnce) {
            showCelebration(); // Show celebration on first discount application
            discountAppliedOnce = true;
        }

        return { amount: discountedAmount, discountApplied: true, discountName, discountValue };
    }

    return { amount: totalAmount, discountApplied: false, discountName: "" };
}

function formatAmount(amount) {
    return `<?= htmlspecialchars(currency ?? "N") ?>${amount.toLocaleString('en-US')}`;
}

function displayAmounts(totalAmount, discountedAmount, discountApplied, discountValue, discountName) {
    let content = `<span style="color: ${discountApplied ? "#f4a1a1" : "#333"}; text-decoration: ${discountApplied ? "line-through" : "none"};">${formatAmount(totalAmount)}</span>`;
    
    if (discountApplied) {
        content += `
            <span style="color: #4b8f4b; font-size: 1rem; font-weight: bold; margin-left: 10px;">
                ${formatAmount(discountedAmount)}
            </span>
            <small style="font-size: 0.8rem; color: #4b8f4b;">
                (${discountValue} - ${discountName})
            </small>`;
    }

    return content;
}

function updateDisplayAmount(qtyValue = null, amountValue = null) {
    const qtynumberValue = qtyValue != null ? qtyValue : parseInt(qtynumber.value);
    const totalAmount = amountValue != null ? (amountValue * qtynumberValue) : (amount * qtynumberValue);
    const { amount: discountedAmount, discountApplied, discountName, discountValue } = applyDiscount(totalAmount);

    const displayContent = displayAmounts(totalAmount, discountedAmount, discountApplied, discountValue, discountName);
    const encouragementText = qtynumberValue < JSON.parse(atob(document.getElementById("discountData").value)).no_order
        ? `<p style="font-size: 0.85rem; color: #777; margin-top: 5px;">
             Add <b>${JSON.parse(atob(document.getElementById("discountData").value)).no_order - qtynumberValue}</b> more items to unlock an exclusive discount!
           </p>`
        : '';

    document.getElementById("DisplayAmount").innerHTML = displayContent + `<p style="font-size: 1rem;">Quantity: <b>${qtynumberValue}</b></p>` + encouragementText;
}

function updateCartCount() {
    const cartDetails = document.getElementById("cartDetails");
    const qtynumberDiv = document.getElementById("qtynumberDiv");
    const qtynumberInput = document.getElementById("qtynumber");

    qtynumberDiv.style.display = cart.length > 0 ? "none" : "flex";
    qtynumberInput.value = cart.length;
    cartDetails.value = JSON.stringify(cart, null, 2);
    
    if (cart.length === 0) {
        document.getElementById("DisplayAmount").innerHTML = `<p style="color: gray; font-size: 1rem;">No items in the cart.</p>`;
        return;
    }

    updateDisplayAmount(); // Sync display update with cart count update
}

function showCelebration() {
    const celebrationDiv = document.createElement("div");
    celebrationDiv.innerHTML = `🎉 Congratulations! You unlocked a discount! 🎉`;
    celebrationDiv.style.position = "fixed";
    celebrationDiv.style.top = "20%";
    celebrationDiv.style.left = "50%";
    celebrationDiv.style.transform = "translateX(-50%)";
    celebrationDiv.style.background = "#e0ffe0";
    celebrationDiv.style.color = "#4b8f4b";
    celebrationDiv.style.padding = "15px 30px";
    celebrationDiv.style.fontSize = "1.2rem";
    celebrationDiv.style.borderRadius = "10px";
    celebrationDiv.style.boxShadow = "0 0 10px rgba(0, 0, 0, 0.1)";
    celebrationDiv.style.zIndex = "1000";
    document.body.appendChild(celebrationDiv);

    setTimeout(() => {
        celebrationDiv.style.opacity = "1";
        celebrationDiv.style.transition = "opacity 0.5s";
        setTimeout(() => {
            celebrationDiv.style.opacity = "0";
            setTimeout(() => celebrationDiv.remove(), 500);
        }, 2000);
    }, 100);
}



// function updateCartCount() {
//     if(cart.length > 0)  document.getElementById("qtynumberDiv").style.display = "none";
//     if(cart.length == 0)  document.getElementById("qtynumberDiv").style.display = "flex";
//     document.getElementById("qtynumber").value = cart.length;
//     document.getElementById("cartDetails").innerHTML = JSON.stringify(cart);
//     document.getElementById("DisplayAmount").innerHTML = "N" + (parseInt(amount) * cart.length).toLocaleString('en-US') + "</b><br> Quantity: <b>"+cart.length+"</b>";
//     // (parseInt(amount) * currentValue).toLocaleString('en-US')

//     // $('#qtynumberere').value(cart.length);
// }

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
    
    // console.log(cart.length);
    if(cart.length == 0) {
        cartContainer.html('<small><b>Any Account Added will be displayed here.</b></small>');
        copybtn.style.display = "none";
        clearbtn.style.display = "none";
    }


    if(cart.length > 0){ 
        cartContainer.html(cart.map(login => renderLogin(login, true)).join(''));
        copybtn.style.display = "block";
        clearbtn.style.display = "block";
    }
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


function extractPreviewLinks() {
    // Select the cart container
    const cartContainer = document.getElementById('cart-container');
    
    // Select all the preview links within the cart
    const previewLinks = cartContainer.querySelectorAll('a[target="_BLANK"]');
    console.log(previewLinks[0].href);
    // Initialize an empty string to store the links
    let links = '';
    
    // Loop through the preview links and add each link to the string
    previewLinks.forEach(link => {
        links += link.href + '\n'; // Add each link followed by a new line
    });

    navigator.clipboard.writeText(links).then(() => {
        // Optionally alert the user
        alert('Preview links copied to clipboard!');
    }).catch(err => {
        console.error('Failed to copy links: ', err);
    });
    
    // Output the links (for demonstration, you can log it or show in a textarea or alert)
    // alert("All account(s) preview link in cart copied.")
    // Alternatively, display it in a div or alert
    // document.getElementById('output').innerText = links; // Example if using a div with id 'output'
    // alert(links); // If you want to use an alert to display the links
}



// Inject the notification HTML into the page under the search input
function injectNotificationHTML() {
    const container = document.createElement('div');
    container.id = 'notification-container';
    container.style.display = 'none';
    container.style.marginTop = '10px';
    container.style.padding = '10px';
    container.style.borderRadius = '5px';
    container.style.border = '1px solid #fa5a15'; // Border color
    container.style.backgroundColor = 'transparent'; // Fully transparent background
    container.style.color = '#721c24'; // Dark red text
    container.style.fontFamily = 'Arial, sans-serif';
    container.style.boxShadow = '0 2px 4px rgba(0, 0, 0, 0.1)';
    container.style.position = 'relative'; // Set position to relative for positioning the cancel button
    container.style.width = '100%'; // Set notification to 100% width

    const notFoundMessage = document.createElement('p');
    notFoundMessage.id = 'not-found-message';
    notFoundMessage.style.margin = '0'; // Remove default margin for the message
    container.appendChild(notFoundMessage);

    const buttonContainer = document.createElement('div');
    buttonContainer.style.display = 'flex'; // Use flexbox for layout
    buttonContainer.style.justifyContent = 'flex-start'; // Align items to start
    buttonContainer.style.marginTop = '10px'; // Space above buttons
    buttonContainer.style.gap = '5px'; // Small gap between buttons

    const viewUnfoundBtn = document.createElement('button');
    viewUnfoundBtn.id = 'view-unfound-btn';
    viewUnfoundBtn.onclick = showUnfoundLinks;
    viewUnfoundBtn.style.display = 'none';
    viewUnfoundBtn.textContent = 'View Unfound Links';
    viewUnfoundBtn.classList.add('btn', 'btn-sm', 'btn-primary'); // Add primary classes
    buttonContainer.appendChild(viewUnfoundBtn);

    const copyUnfoundBtn = document.createElement('button');
    copyUnfoundBtn.id = 'copy-unfound-btn';
    copyUnfoundBtn.onclick = copyUnfoundLinks;
    copyUnfoundBtn.textContent = 'Copy Links';
    copyUnfoundBtn.classList.add('btn', 'btn-sm'); // Add specified classes
    copyUnfoundBtn.innerHTML += ' <i class="ti ti-copy"></i>'; // Add copy icon
    buttonContainer.appendChild(copyUnfoundBtn);

    const cancelButton = document.createElement('button');
    cancelButton.textContent = 'Close'; // Change close icon to text 'X'
    cancelButton.style.background = 'none'; // Remove background
    cancelButton.style.border = 'none'; // Remove border
    cancelButton.style.cursor = 'pointer'; // Change cursor to pointer
    cancelButton.style.fontSize = '16px'; // Adjust the font size for better visibility
    cancelButton.onclick = () => {
        container.style.display = 'none'; // Hide the notification on click
    };
    buttonContainer.appendChild(cancelButton); // Add the cancel button to the container

    container.appendChild(buttonContainer); // Add button container to the notification

    const unfoundLinksContainer = document.createElement('div');
    unfoundLinksContainer.id = 'unfound-links-container';
    unfoundLinksContainer.style.display = 'none';

    const unfoundLinks = document.createElement('pre');
    unfoundLinks.id = 'unfound-links';
    unfoundLinksContainer.appendChild(unfoundLinks);

    container.appendChild(unfoundLinksContainer);

    // Append the notification container after the search input
    const searchInput = document.getElementById('search-input');
    searchInput.parentNode.insertBefore(container, searchInput.nextSibling); // Insert after the search input
}

// Call this function once on page load to inject the HTML
injectNotificationHTML();

let notFoundTerms = []; // To store the unfound terms globally

function isValidUrl(string) {
    const regex = /^(ftp|http|https):\/\/[^ "]+$/; // Basic URL validation
    return regex.test(string);
}

function searchLogins() {
    const searchQuery = document.getElementById('search-input').value.toLowerCase().trim();
    const searchTerms = searchQuery.split(/\s*,\s*|\s+/); // Split by commas or spaces
    const loginsContainer = document.getElementById('logins-container');
    const logins = loginsContainer.getElementsByClassName('single-note-item');
    let searchResultsFound = false;

    const foundTerms = new Set(); // Track terms that are found
    notFoundTerms = []; // Reset the unfound terms

    // Clear the notification and unfound links on each new search
    const notificationContainer = document.getElementById('notification-container');
    notificationContainer.style.display = 'none';
    const unfoundLinksElement = document.getElementById('unfound-links');
    unfoundLinksElement.textContent = ''; // Clear the unfound links

    // Loop through each login and check if it matches any search term
    Array.from(logins).forEach(login => {
        const username = login.querySelector('h6 p').textContent.toLowerCase();
        const previewLinks = login.querySelectorAll('a[target="_BLANK"]'); // Select multiple links if present
        let matchFound = false;

        // Check if the username or any preview link matches any search term
        searchTerms.forEach(term => {
            if (username.includes(term)) {
                matchFound = true;
                foundTerms.add(term);
            } else {
                previewLinks.forEach(link => {
                    if (link.href.toLowerCase().includes(term)) {
                        matchFound = true;
                        foundTerms.add(term);
                    }
                });
            }
        });

        // Display the item if a match is found, otherwise hide it
        if (matchFound) {
            login.style.display = '';
            searchResultsFound = true;
        } else {
            login.style.display = 'none';
        }
    });

    // Show or hide "Add All to Cart" button based on search results
    let addAllBtn = document.getElementById('add-all-to-cart-btn');
    if (searchResultsFound && searchQuery !== "") {
        addAllBtn.style.display = 'block';
    } else {
        addAllBtn.style.display = 'none';
    }

    // Determine which search terms were not found
    notFoundTerms = searchTerms.filter(term => !foundTerms.has(term) && isValidUrl(term));

    // Display notification if there are valid unfound terms
    if (notFoundTerms.length > 0) {
        notificationContainer.style.display = 'block';
        const notFoundMessage = document.getElementById('not-found-message');
        notFoundMessage.textContent = `${notFoundTerms.length} link(s) not found.`;
        const viewUnfoundBtn = document.getElementById('view-unfound-btn');
        viewUnfoundBtn.style.display = 'inline-block';
    } else {
        notificationContainer.style.display = 'none';
        const viewUnfoundBtn = document.getElementById('view-unfound-btn');
        viewUnfoundBtn.style.display = 'none';
    }
}

function showUnfoundLinks() {
    const unfoundLinksContainer = document.getElementById('unfound-links-container');
    const unfoundLinksElement = document.getElementById('unfound-links');

    // Display the list of unfound links, each on a new line
    unfoundLinksElement.textContent = notFoundTerms.join('\n');
    unfoundLinksContainer.style.display = unfoundLinksContainer.style.display === 'none' ? 'block' : 'none'; // Toggle display
}

function copyUnfoundLinks() {
    const unfoundLinksElement = document.getElementById('unfound-links');
    navigator.clipboard.writeText(unfoundLinksElement.textContent)
        .then(() => alert('Unfound links copied to clipboard!'))
        .catch(err => alert('Failed to copy links: ', err));
}

// Initial call to set the notification container
injectNotificationHTML();



function addAllToCart() {
    const loginsContainer = document.getElementById('logins-container');
    const logins = loginsContainer.getElementsByClassName('single-note-item');

    // Loop through each visible login and click its "Add to Cart" button
    Array.from(logins).forEach(login => {
        if (login.style.display !== 'none') { // Check if login is visible (part of search result)
            const toggleButton = login.querySelector('a[onclick*="toggleCart"]');
            if (toggleButton) {
                toggleButton.click(); // Simulate a click on the "toggleCart" button
            }
        }
    });
    document.getElementById('search-input').value = "";
    document.getElementById('add-all-to-cart-btn').style.display = "none";
    searchLogins();
}
    


copybtn.addEventListener('click', function (event) {
    extractPreviewLinks();
});
clearbtn.addEventListener('click', function (event) {
    clearCart();
});



function clearCart() {
    // Prompt the user for confirmation before clearing the cart
    const confirmation = confirm("Are you sure you want to clear the cart? This action cannot be undone.");

    if (confirmation) {
        // Select all minus buttons within the cart container
        const minusButtons = document.querySelectorAll('#cart-container .btn.btn-sm.bg-primary.text-white');

        // Loop through each button and simulate a click to remove items from the cart
        minusButtons.forEach(button => {
            button.click();
        });

        // Optional: You can log a message to the console after clearing the cart
        console.log('Cart cleared: clicked all minus buttons.');
    } else {
        console.log('Cart clearing cancelled.');
    }
}