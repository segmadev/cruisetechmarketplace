<?php 
    $script[] = "modal";
    $script[] = "sweetalert";
?>
<div class="card">
    <div class="card-body" data-load="get" data-path="passer?s=<?= htmlspecialchars($_GET['s'] ?? "") ?>&platform=<?= htmlspecialchars($_GET['platform'] ?? "") ?>&userID=<?= htmlspecialchars($_GET['userID'] ?? "") ?>&category=<?= htmlspecialchars($_GET['category'] ?? "all") ?>" data-page="account" data-displayId="displayAccounts">
        <div class="card-title">
            <h1>Accounts Added</h1>
        </div>
        <div class="card-header">
            <form action="" method="get" class="row flex d-flex">
                <input type="hidden" name="p" value="account">
                <div class="col-12 col-md-5">
                    <input type="search" value="<?php if(isset($_GET['s'])) { echo $_GET['s']; } ?>" name="s" class="form-control" placeholder="Search Account" data-id="#accountList" id="searchMarket">
                </div>
                <div class="col-12 col-md-7">
                    <div class="dropdown flex d-flex g-2 w-100">
                        <select name="platform" class="form-control" style="width: 30%;" id="">
                            <option value="">All</option>
                            <option value="loginsearch" <?php if(isset($_GET['platform']) && $_GET['platform'] == "loginsearch") { echo "selected"; } ?>>Search Logins</option>
                            <?php if ($platforms->rowCount() > 0) {
                                 foreach ($platforms as $row) {?>
                                    <option value="<?php echo $row['ID'];?>" <?php if(isset($_GET['platform']) && $_GET['platform'] == $row['ID']) { echo "selected"; } ?>><?php echo $row['name'];?></option>
                                <?php }
                            }?>
                        </select>
                        <select name="category" class="form-control" style="width: 30%;" id="">
                            <option value="all">All Categories</option>
                            <?php if ($categories->rowCount() > 0) {
                                foreach ($categories as $row) {?>
                                    <option value="<?php echo $row['ID'];?>" <?php if(isset($_GET['category']) && $_GET['category'] == $row['ID']) { echo "selected"; } ?>><?php echo $row['name'];?></option>
                                    <?php }
                            }?>
                            
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Fliter</button>
                        
                    </div>
                </div>
            </form>
        </div>

        <?php 
            if(isset($_GET['platform']) && $_GET['platform'] == "loginsearch" && isset($_GET['s'])) {
                $keyword = htmlspecialchars($_GET['s']);
                $data = $a->getall("logininfo", "ID  LIKE CONCAT( '%',?,'%') or username  LIKE CONCAT( '%',?,'%')", [$keyword, $keyword], fetch: "all");
                $i = 1;
                echo '<div class="border-top w-100 row row-cols-1 row-cols-lg-3 row-cols-md-2 g-1 g-lg-3 m-0 p-0">';
                foreach ($data as $value) {
                    echo  $a->display_login_details($value, $i, "d-block");
                    $i++;
                }
                // echo $body;
                echo "</div>";
                
            }else { ?>
                <div class="border-top w-100 row row-cols-1 row-cols-lg-3 row-cols-md-2 g-1 g-lg-3 m-0 p-0" id="displayAccounts"></div>
          <?php  } ?>

    </div>
</div>