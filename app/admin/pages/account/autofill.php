   <!-- Collapsible section -->
<div class="accordion" id="autoFillAccordion">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button bg-light-danger collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                Configure Auto Fill
            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#autoFillAccordion">
            <div class="accordion-body">
                <!-- Fields for user to specify URL and data patterns -->
                <div class='mb-3'>
                    <label for='url-pattern'>Preview Link URL Pattern:</label>
                    <input id='url-pattern' class='form-control' type='text' placeholder='Enter URL pattern (e.g., https://fb.com/username)' value='https://fb.com/username' />
                </div>
                <div class='mb-3'>
                    <label for='data-pattern'>Data Pattern:</label>
                    <input id='data-pattern' class='form-control' type='text' placeholder='Enter data pattern (e.g., username|password|2fa key|Mail|mail pass|Recovery mail|additional info)' value='username|password|2fa key|Mail|mail pass|Recovery mail|additional info' />
                </div>
                <div class='mb-3'>
                    <label for='data-spliter'>Pattern spliter:</label>
                    <input id='data-spliter' class='form-control' type='text' placeholder='(e.g.(, | , :)' value='|' />
                </div>
                <input type="checkbox" name="ignoreusername" id="ignoreusername">Ignore Username
                <hr>
                <!-- File input for user to upload data -->
                <div class='mb-3'>
                    <label for='data-file'>Upload Login Data File:</label>
                    <input id='data-file' type='file' class='form-control' accept='.txt' />
                    <button type="button" onclick="processFile()" class='btn btn-primary mt-2'>Auto Fill Logins</button>
                </div>
            </div>
        </div>
    </div>
</div>
