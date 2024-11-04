<?php
class platform extends roles
{
    function manage_platform($platform_form, $action = "insert")
    {
        if(!$this->validate_action(["platform"=>$action == "insert" ? "new" : "edit"])) return ;
        $platform_form['icon']['file_name'] = $_POST['name'];
        $info = $this->validate_form($platform_form, "platform", $action);
        if (is_array($info)) {
            $action = $action == "insert" ? "New" : "edit";
            $actInfo = ["userID" => adminID, "date_time" => date("Y-m-d H:i:s"), 
            "action_name" => "$action platform",
            "description" => "$action Platform", 
            "action_for"=>"platform", 
            "action_for_ID"=>$info['ID']];
            $this->new_activity($actInfo);
            return $this->message("Platform Added successfully", "success");
        }
    }

    function delete_platform($id) {
        // check if platform not in account
        if(!$this->validate_action(["platform"=>"delete"])) return ;
        $check = $this->getall("account", "platformID = ?", [$id], fetch: "");
        if($check > 0) return $this->message( "You can not delete a platform with account in it.", "error", "json");
        $platform = $this->getall("platform", "ID = ?", [$id]);
        if(!is_array($platform)) return ;
        $icon = $this->get_platform_base_url().$platform['icon'];
        if (file_exists($icon)) unlink($icon);
        $this->delete("platform", "ID = ?", [$id]);
        $return = [
            "message" => ["Success", "Platform Deleted successfully, Reload page to see effect", "success"],
            // "function" => ["removediv", "data" => ["platform$id", "id"]],
        ];
        $actInfo = ["userID" => adminID, "date_time" => date("Y-m-d H:i:s"), 
        "action_name" => "delete platform",
        "description" => "delete Platform", 
        "action_for"=>"platform", 
        "action_for_ID"=>$id];
        $this->new_activity($actInfo);
        
        return json_encode($return);

        // return $this->message("Platform Deleted successfully", "success", "json");  
    }
    function get_platforms($start, $limit)
    {
        $data = $this->getall("platform", "ID != ? order by date DESC LIMIT $start, $limit", data: [""], fetch: "moredetails");
        return $data;
    }

    function get_platform_base_url()
    {
        return PATH."assets/images/icons/";
    }

    function get_no_of_account_in_platform($id)
    {
        return $this->getall("account", "platformID = ?", [$id], fetch: "");
    }
    function display_platform($platform)
    {
        $id = $platform['ID'];
        // name of the platform
        $name = $platform['name'];
        // icon url
        $icon = $this->get_platform_base_url() . $platform['icon'];
        // No of account inside platform
        $no = number_format($this->get_no_of_account_in_platform($id));
        // date added
        $date = $this->date_format($platform['date']);
        return "<tr id='platform$id'>
                        <td class='ps-0'>
                            <div class='d-flex align-items-center'>
                                <div class='me-2 pe-1'>
                                    <img src='$icon' class='rounded-circle' width='40' height='40' alt='>
                                </div>
                                <div>
                                    <h6 class='fw-semibold mb-1'>$name</h6>
                                    <p class='fs-2 mb-0 text-muted'>Date added: $date</p>
                                </div>
                            </div>
                        </td>
                        
                        <td>
                            <a href='index?p=account&platform=".$id."'><span class='badge fw-semibold py-1 w-85 bg-light-dark'>View: $no</span></a>
                        </td>
                        <td class='flex d-flex'>
                            <a href='index?p=platform&action=edit&id=$id' class='btn btn-primary btn-sm'>Edit</a>
                            <form action='' id='foo'>
                                <input type='hidden' name='ID' value='$id'>
                                <input type='hidden' name='delete_platform' value='approved'>
                                <input type='hidden' name='page' value='platform'>
                                <input type='hidden' name='confirm' value='You are about to delete this $name'>
                                <div id='custommessage'></div>
                                <button type='submit' class='ml-2 btn btn-light-danger d-flex align-items-center gap-3 text-danger' href='#'><i class='fs-4 ti ti-trash'></i>Delete</button>
                            </form>
                        </td>
                    </tr>";
    }
}
