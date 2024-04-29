<?php 
    class wallets extends content {
        function new_wallet(array $data) {
            $_POST['ID'] = uniqid();
            $wallet = $this->validate_form($data, "wallets");
            if(!is_array($wallet)) { return false; }
            $insert = $this->quick_insert("wallets", $wallet, message: "Wallet created");
            if($insert) {
              $actInfo = ["userID" => $wallet['userID'],  "date_time" => date("Y-m-d H:i:s"), "action_name" => "Create wallet", "description" => "A wallet was created", "action_for"=>"wallets", "action_for_ID"=>$wallet['ID']];
                $this->new_activity($actInfo);
            }
            return true;
        }
        function edit_wallet(array $data) {
            $wallet = $this->validate_form($data, "wallets");
            if(!is_array($wallet)) { return false; }
            $id = $wallet['ID'];
            $update = $this->update("wallets", $wallet, "ID = '$id'", message: "Wallet Updated");
            $actInfo = ["userID" => $wallet['userID'],  "date_time" => date("Y-m-d H:i:s"), "action_name" => "Update wallet", "description" => "Wallet   edited to a new detail.", "action_for"=>"wallets", "action_for_ID"=>$wallet['ID']];
            $this->new_activity($actInfo);
            return true;
        }

        function delete_wallet($userID) {
            $data = $this->checkmessage(["ID"]);
            
            if(!is_array($data)) {
                $return = [
                    "message" => ["Error", "Unable to delete wallet. Reload page and try again", "error"],
                ];
                return json_encode($return);
            }
            $delete = $this->delete("wallets", "ID = ? and userID = ?", [$data['ID'], $userID]);
            if($delete) {
              $actInfo = ["userID" => $userID,  "date_time" => date("Y-m-d H:i:s"), "action_name" => "Create removed", "description" => "A wallet was deleted from your account."];
                $this->new_activity($actInfo);
                $return = [
                    "message" => ["Sucess", "Wallet deleted", "success"],
                    "function" => ["removediv", "data"=>[".walle_con_".$data['ID'], "success"]],
                ];
            }else {

                $return = [
                    "message" => ["Error", "Unable to delete wallet. Reload page and try again", "error"],
                ];

                
            }
            return json_encode($return);
        }
        function get_coin_details($coinID, $what = ["name", "symbol", "image"]) {
          $coin = $this->getall("coins", "coinID = ?", [$coinID], "name, short_name");
          $symbol = strtolower($coin['short_name']);
          $coin_image = "https://assets.coincap.io/assets/icons/$symbol@2x.png";
          $data['name']= $coin['name'];
          $data['image'] = $coin_image;
          $data['symbol'] = $coin['short_name'];
          return $data;
        }

        function short_wallet_address($address) {
          return substr($address, 0, 6) . "..." . substr($address, -3);
        }
        function wallet_detail_widget($data) {
          $coin = $this->getall("coins", "coinID = ?", [$data['coin_name']], "name, short_name");
          $symbol = strtolower($coin['short_name']);
          $status = $this->badge($data['status']);
          $coin_image = "https://assets.coincap.io/assets/icons/$symbol@2x.png";
          return "<div id='genqr' data-id='".$data['ID']."wallet' data-info='".$data['wallet_address']."' class='col-12 col-md-6 walle_con_".$data['ID']."'>
          <div class='card rounded-2 overflow-hidden'>
            <div class='position-relative'>
              <a href='javascript:void(0)' id='".$data['ID']."wallet' class='qr-image'></a>
              <a href='javascript:void(0)' class='btn rounded-circle d-flex align-items-center justify-content-center position-absolute bottom-0 end-0 me-3 mb-n3 mt-5 '>
              <img style='width: 40px' src='$coin_image' class='card-img-top rounded-0' alt='$symbol'>
              </a>
            </div>
            <div class='card-body p-4'>
              <h5 class='fw-semibold fs-4 mb-2'>".$coin['name']." - ".$symbol."</h5>
              <div class='d-flex align-items-center justify-content-between'>
                <div class='d-flex align-items-center gap-2'>
                  <b class='mb-0 '>".$data['wallet_address']."</b>
                  <p class='mb-0 '><a href='javascript:void(0)' onclick='copytext(\"".$data['wallet_address']."\")' class='text-primary' data-copy='".$data['wallet_address']."'><i class='ti ti-copy'></i></a></p>
                </div>
                
              </div>
            </div>
          </div>
        </div>";
        }
        function wallet_widget($data) {
            $coin = $this->getall("coins", "coinID = ?", [$data['coin_name']], "name, short_name");
            $symbol = strtolower($coin['short_name']);
            $status = $this->badge($data['status']);
            $coin_image = "https://assets.coincap.io/assets/icons/$symbol@2x.png";
            return "<div id='genqr' data-id='".$data['ID']."wallet' data-info='".$data['wallet_address']."' class='col-6 col-lg-3 walle_con_".$data['ID']."'>
            <div class='card rounded-2 overflow-hidden'>
              <div class='position-relative'>
                <a href='javascript:void(0)' id='".$data['ID']."wallet' class='qr-image'></a>
                <a href='javascript:void(0)' class='btn rounded-circle d-flex align-items-center justify-content-center position-absolute bottom-0 end-0 me-3 mb-n3 mt-5 '>
                <img style='width: 40px' src='$coin_image' class='card-img-top rounded-0' alt='$symbol'>
                </a>
              </div>
              <div class='card-body p-4'>
                <h5 class='fw-semibold fs-4 mb-2'>".$coin['name']." - ".$symbol."</h5>
                <div class='d-flex align-items-center justify-content-between'>
                  <div class='d-flex align-items-center gap-2'>
                    <b class='mb-0 '>".substr($data['wallet_address'], 0, 6) . "..." . substr($data['wallet_address'], -3)."</b>
                    <p class='mb-0 '><a href='javascript:void(0)' class='text-primary' data-copy='".$data['wallet_address']."'><i class='ti ti-copy'></i></a></p>
                  </div>
                  
                </div>
                $status
              </div>
              <div class='card-footer text-center d-flex'>
                    <a href='index?p=wallets&action=edit&id=".$data['ID']."' class='btn'><i class='ti ti-edit'></i></a>
                    <form id='foo'>
                        <input name='ID' value='".$data['ID']."' type='hidden'/>
                        <input name='delete_wallet' value='holder' type='hidden'/>
                        <input name='page' value='wallets' type='hidden'/>
                        <input name='confirm' value='Are you sure you want to delete this wallet?' type='hidden'/>
                        <div id='custommessage'></div>
                        <button type='submit' class='text-danger btn'><i class='ti ti-trash'></i></button>
                    </form>
              </div>
            </div>
          </div>";
        }
    }