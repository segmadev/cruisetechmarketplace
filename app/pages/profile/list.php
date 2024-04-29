<div class="container-fluid">
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Profile Setting</h4>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="../dist/images/breadcrumb/ChatBc.png" alt="" class="img-fluid mb-n4">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <ul class="nav nav-pills user-profile-tab" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link position-relative rounded-0 active d-flex align-items-center justify-content-center bg-transparent fs-3 py-4" id="pills-account-tab" data-bs-toggle="pill" data-bs-target="#pills-account" type="button" role="tab" aria-controls="pills-account" aria-selected="true">
                    <i class="ti ti-user-circle me-2 fs-6"></i>
                    <span class="d-none d-md-block">Account</span>
                </button>
            </li>
            <!-- <li class="nav-item" role="presentation">
                <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-4" id="pills-notifications-tab" data-bs-toggle="pill" data-bs-target="#pills-notifications" type="button" role="tab" aria-controls="pills-notifications" aria-selected="false">
                    <i class="ti ti-bell me-2 fs-6"></i>
                    <span class="d-none d-md-block">Notifications</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-4" id="pills-bills-tab" data-bs-toggle="pill" data-bs-target="#pills-bills" type="button" role="tab" aria-controls="pills-bills" aria-selected="false">
                    <i class="ti ti-article me-2 fs-6"></i>
                    <span class="d-none d-md-block">Bills</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-4" id="pills-security-tab" data-bs-toggle="pill" data-bs-target="#pills-security" type="button" role="tab" aria-controls="pills-security" aria-selected="false">
                    <i class="ti ti-lock me-2 fs-6"></i>
                    <span class="d-none d-md-block">Security</span>
                </button>
            </li> -->
        </ul>
        <div class="card-body">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-account" role="tabpanel" aria-labelledby="pills-account-tab" tabindex="0">
                    <div class="row">
                        <div class="col-lg-6 d-flex align-items-stretch">
                            <div class="card w-100 position-relative overflow-hidden">
                                <div class="card-body p-4">
                                    <h5 class="card-title fw-semibold">Change Profile</h5>
                                    <p class="card-subtitle mb-4">Change your profile picture from here</p>
                                    <div class="text-center">
                                        <div id="image-preview-profile-img">
                                            <img src="<?= $u->get_profile_icon_link($userID); ?>" alt="" class="img-fluid rounded-circle" width="120" height="120">
                                        </div>
                                        <div class="d-flex align-items-center justify-content-center my-4 gap-3">
                                            <form action="" id="upload-img">
                                                <input type="hidden" name="page" value="user">
                                                <input type="hidden" name="change_profile_pic">
                                                <input type="file" onchange="showPreview(event, 'image-preview-profile-img'); change_profile('upload-img')" style="display: none;" name="profile_image" id="profile_image">
                                                <label for="profile_image" class="btn btn-primary">Upload</label>
                                                <!-- <button class="btn btn-outline-danger">Reset</button> -->
                                        </div>
                                        </form>
                                        <p class="mb-0">Allowed JPGor PNG. Max size of 800K</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 d-flex align-items-stretch">
                            <div class="w-100 position-relative overflow-hidden">
                                <div class="card-body p-0 mt-2">
                                    <h5 class="card-title fw-semibold">Change Password</h5>
                                    <p class="card-subtitle mb-4">To change your password please confirm here</p>
                                    <form id="foo">
                                        <?= $c->create_form($change_password_from); ?>
                                        <input type="hidden" name="page" value="profile">
                                        <input type="hidden" name="change_password">
                                        <div id="custommessage"></div>
                                        <input type="submit" value="Change Password" class="btn btn-primary">
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="w-100 position-relative overflow-hidden mb-0">
                                <div class="card-body p-0 mt-2">
                                    <h5 class="card-title fw-semibold">Personal Details</h5>
                                    <p class="card-subtitle mb-4">To change your personal detail , edit and save from here</p>

                                    
                                        <form id="foo" class="col-lg-6">
                                            <?= $c->create_form($profile_form); ?>
                                            <input type="hidden" name="update_profile">
                                            <input type="hidden" name="page" value="profile">
                                            <div id="custommessage"></div>
                                            <div class="col-12">
                                                <div class="d-flex align-items-center justify-content-end mt-4 gap-3">
                                                    <button type="submit" class="btn btn-primary">Save Change</button>
                                                    <!-- <button class="btn btn-light-danger text-danger">Cancel</button> -->
                                                </div>
                                            </div>
                                        </form>
                                   

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>