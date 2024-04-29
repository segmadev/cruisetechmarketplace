<div class="card">
    <div class="card-header">
        <h1>Bot User.</h1>
        <p>Genarate bot users</p>
        <small class="bg-light-danger p-2 text-danger mt-0">It might generate less than the amount of users you specify because it will ignore any user with  exiting name in the system.</small>
    </div>
        <div class="card-body">
            <form action="index" method="get">
                <input type="number" placeholder="No of users to generate." name="no" id="" max="50" class="form-control" required>
                <input type="hidden" name="p" value="users">
                <input type="hidden" name="action" value="generate">
                <input type="hidden"  name="create_bot_users" value="">
                <hr>
                <input type="submit" value="Genarate User" class="btn btn-primary">
            </form>
        </div>
</div>