<hr>
<div class="row">
    <?php
    $loginClass = 'row border border-1 p-3';
    ?>
    <h3 class="h5">Add Account Logins</h3>
    <div id="loginInfos" class="row"></div>
</div>
<div class="d-flex mt-2">
<button type="button" onclick="add_login()" class="btn text-primary"><b><i class="ti ti-plus"></i> Add more
Login</b></button> <button type='button' id="copyButton" class="btn btn-outline-black btn-sm btn-black">Copy Preview Links</button>
</div>
<hr>
<?php require_once 'pages/account/autofill.php'; ?>
<script>
    let i = 1;
    function add_login() {
        var template = `<div class="d-flex justify-content-between align-items-center w-100">
            <h6 class="login-number"><b>Login ${i++}</b></h6>
            <button type='button' class='btn btn-danger btn-sm remove-login'>Remove</button>
        </div> <?= $c->create_form($logininfo); ?>`;
        var new_row = document.createElement('div');
        new_row.className = "add-new w-100 <?= $loginClass ?>";
        new_row.innerHTML = template;
        document.getElementById("loginInfos").appendChild(new_row);

        // Add event listener to the remove button
    new_row.querySelector('.remove-login').addEventListener('click', function() {
        remove_login(new_row);
    });
    }

    add_login();
    function auto_fill(data, urlPattern, dataPattern, spliter) {
    // Remove any login cards that are not filled (empty fields)
    remove_empty_logins();
    if(spliter == 0) spliter = " ";
    let patterns = dataPattern.split(spliter);
    let mismatchedRows = [];  // Array to collect mismatched rows
    var usernamePosition = findPosition(patterns);
    if(usernamePosition < 0) {
        alert("Username and ID not found in the data pattern.");
        return
    }
    data.forEach((item, index) => {
        
        // Split the data by the delimiter
        let details = item.split(spliter);
        
        // Check if the number of fields matches the pattern (allow optional fields)
        if (details.length < patterns.length - 1) {
            mismatchedRows.push(index + 1); // Collect the row number (1-based index)
            return;
        }

        
        
        let username = details[usernamePosition];  // Assuming the first field is 'id', treat it as username
        let preview_link = urlPattern.replace(/username|ID|id/, username);

        let filteredDetails = [];
        patterns.forEach((pattern, i) => {
            if (!pattern.includes('null')) {
                filteredDetails.push(details[i]); // Keep only needed values
            }
        });

        // Construct login details excluding optional fields like 'dob'
        let login_details = filteredDetails.join(`|`);
        // Call add_login to create new inputs
        add_login();

        // Fill in the fields with the extracted data
        let last_added = document.querySelectorAll('.add-new').length - 1;
        let container = document.querySelectorAll('.add-new')[last_added];

        container.querySelector('textarea[name="login_details[]"]').value = login_details;
        container.querySelector('input[name="preview_link[]"]').value = preview_link;
        console.log(document.querySelector("#ignoreusername").checked);
        if(!document.querySelector("#ignoreusername").checked) container.querySelector('input[name="username[]"]').value = username;
    });
    
    // Show a single alert if there are mismatched rows
        if (mismatchedRows.length > 0) {
            alert(`Data doesn't match the specified pattern in the following rows: ${mismatchedRows.join(', ')}`);
        }
    }


        function processFile() {
            let fileInput = document.getElementById("data-file");
            let urlPattern = document.getElementById("url-pattern").value.trim();
            let dataPattern = document.getElementById("data-pattern").value.trim();
            let spliter = document.getElementById("data-spliter").value.trim();

            if (fileInput.files.length === 0 || !dataPattern) {
                alert("Please upload a file and fill in the URL pattern and data pattern.");
                return;
            }

            

            let file = fileInput.files[0];
            let reader = new FileReader();

            reader.onload = function(event) {
                let text = event.target.result.trim();
                let data = text.split("\n").map(line => line.trim()).filter(line => line);

                // Call auto_fill to automatically populate the form fields
                auto_fill(data, urlPattern, dataPattern, spliter);

                // Clear the file input
                fileInput.value = '';
            };

            reader.readAsText(file);
        }

        function remove_login(row) {
            row.remove();  // Remove the selected row
            update_login_numbers();  // Update the login numbers
        }

    function update_login_numbers() {
        const loginRows = document.querySelectorAll('.add-new');
        loginRows.forEach((row, index) => {
            const numberLabel = row.querySelector('.login-number b');
            numberLabel.textContent = `Login ${index + 1}`;  // Update the login number
        });
        i = loginRows.length + 1;  // Update the counter for future logins
    }


    // Function to remove any login cards that are empty
    function remove_empty_logins() {
        const loginRows = document.querySelectorAll('.add-new');
        loginRows.forEach(row => {
            const loginDetails = row.querySelector('textarea[name="login_details[]"]').value.trim();
            const previewLink = row.querySelector('input[name="preview_link[]"]').value.trim();
            const username = row.querySelector('input[name="username[]"]').value.trim();
            
            // If all fields are empty, remove the row
            if (!loginDetails && !previewLink && !username) {
                row.remove();
            }
        });

        // Update login numbers after removing empty logins
        update_login_numbers();
    }

    function findPosition(arr) {
        // Try to find 'username'
        let position = arr.indexOf('username');
        
        // If 'username' is not found, try 'ID'
        if (position === -1) {
            position = arr.indexOf('ID');
        }

        // If 'ID' is not found, try 'id'
        if (position === -1) {
            position = arr.indexOf('id');
        }

        // If neither 'username', 'ID', nor 'id' is found, return false
        return position;
    }

    // copy preview links to clipboard
    function copyPreviewLinks() {
    const previewLinks = document.querySelectorAll('#loginInfos input[name="preview_link[]"]');
    let links = '';

    previewLinks.forEach(input => {
        const value = input.value.trim();
        if (value) {
            links += value + '\n';  // Add each non-empty link with a newline
        }
    });

    if (links) {
        navigator.clipboard.writeText(links).then(() => {
            alert('Preview links copied to clipboard!');
        }).catch(err => {
            console.error('Failed to copy links: ', err);
        });
    } else {
        alert('No preview links to copy.');
    }
}

// Example: Call the function on button click
document.getElementById('copyButton').addEventListener('click', copyPreviewLinks);

</script>