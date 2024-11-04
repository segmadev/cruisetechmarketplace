<?php
class Category extends roles
{
    function manage_category($category_form, $action = "insert")
    {
        if($action == "insert") {
            $actionName = "new";
            if(!$this->validate_action(["categories"=>"new"], true)) return ;
        }else {
            $actionName = "edit";
            if(!$this->validate_action(["categories"=>"edit"], true)) return ;
            
        }
        $info = $this->validate_form($category_form, "category", $action);
        if (is_array($info)) {
            $actInfo = ["userID" => adminID, "date_time" => date("Y-m-d H:i:s"), 
            "action_name" => "$actionName category",
            "description" => "$actionName category", 
            "action_for"=>"categories", 
            "action_for_ID"=>$info['ID']];
            $this->new_activity($actInfo);
            return $this->message("category Added successfully", "success");
        }
    }

    function delete_category($id) {
        // check if category not in account
        if(!$this->validate_action(["categories"=>"delete"], true)) return ;
        $check = $this->getall("account", "categoryID = ?", [$id], fetch: "");
        if($check > 0) return $this->message( "You can not delete a category with account in it.", "error", "json");
        $category = $this->getall("category", "ID = ?", [$id]);
        if(!is_array($category)) return ;
        $this->delete("category", "ID = ?", [$id]);
        $return = [
            "message" => ["Success", "category Deleted successfully, Reload page to see effect", "success"],
            // "function" => ["removediv", "data" => ["category$id", "id"]],
        ];
        $actInfo = ["userID" => adminID, "date_time" => date("Y-m-d H:i:s"), 
        "action_name" => "delete category",
        "description" => "delete category", 
        "action_for"=>"categories", 
        "action_for_ID"=>$id];
        $this->new_activity($actInfo);
        return json_encode($return);

        // return $this->message("category Deleted successfully", "success", "json");  
    }
    function get_categories($start, $limit)
    {
        $data = $this->getall("category", "ID != ? order by date DESC LIMIT $start, $limit", data: [""], fetch: "moredetails");
        return $data;
    }

    function get_category_base_url()
    {
        return PATH."assets/images/icons/";
    }

    function get_no_of_account_in_category($id)
    {
        return $this->getall("account", "categoryID = ?", [$id], fetch: "");
    }
    function display_category($category)
    {
        $deleteForm = "";
        
        $id = $category['ID'];
        // name of the category
        $name = $category['name'];
        // icon url
        // No of account inside category
        $no = number_format($this->get_no_of_account_in_category($id));
        // date added
        $date = $this->date_format($category['date']);
        if($this->validate_action(["categories"=> "delete"])) {
            $deleteForm = "<td class='flex d-flex'>
                            <a href='index?p=category&action=edit&id=$id' class='btn btn-primary btn-sm'>Edit</a>
                            <form action='' id='foo'>
                                <input type='hidden' name='ID' value='$id'>
                                <input type='hidden' name='delete_category' value='approved'>
                                <input type='hidden' name='page' value='category'>
                                <input type='hidden' name='confirm' value='You are about to delete this $name'>
                                <div id='custommessage'></div>
                                <button type='submit' class='ml-2 btn btn-light-danger d-flex align-items-center gap-3 text-danger' href='#'><i class='fs-4 ti ti-trash'></i>Delete</button>
                            </form>
                        </td>";
        }
        return "<tr id='category$id'>
                        <td class='ps-0'>
                            <div class='d-flex align-items-center'>
                                
                                <div>
                                    <h6 class='fw-semibold mb-1'>$name</h6>
                                    <p class='fs-2 mb-0 text-muted'>Date added: $date</p>
                                </div>
                            </div>
                        </td>
                        
                        <td>
                            <a href='index?p=account&category=".$id."'><span class='badge fw-semibold py-1 w-85 bg-light-dark'>View: $no</span></a>
                        </td>
                        $deleteForm
                    </tr>";
    }
}
