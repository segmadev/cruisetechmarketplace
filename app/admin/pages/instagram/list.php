<?php 
$script[] = "scrape"; 
// $script[] = "wizard"; 

?>
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <h3 class="text-center mb-4">Paste your instagram links below</h3>
      <form id="lineForm">
        <div class="mb-3">
          <label for="textarea" class="form-label">Enter Data (one line per entry):</label>
          <textarea id="textarea" class="form-control" rows="6" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">Submit</button>
      </form>

      <div class="mt-3" id="response" style="display: none;">
        <div class="alert alert-info">Processing...</div>
      </div>

      <div class="mt-3" id="displayresult" style="">
      </div>
    </div>
  </div>
</div>