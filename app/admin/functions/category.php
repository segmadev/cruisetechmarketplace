<?php
class Category extends roles
{

    // Function to fetch data dynamically
    public function fetchAccountsAndLogins($filterDate)
    {
        $query = "
        WITH CategoryData AS (
            SELECT 
                ID AS categoryID
            FROM 
                category
            WHERE 
                cat_type = 0
        ),
        AccountData AS (
            SELECT 
                a.ID AS accountID,
                a.amount,
                a.real_amount,
                COALESCE(NULLIF(a.real_amount, 0), a.amount) AS effective_amount
            FROM 
                account a
            INNER JOIN 
                CategoryData c ON a.categoryID = c.categoryID
        ),
        LoginInfoCount AS (
            SELECT 
                l.accountID,
                COUNT(*) AS login_count
            FROM 
                logininfo l
            INNER JOIN 
                AccountData ad ON l.accountID = ad.accountID
            WHERE 
                DATE(l.sold_at) = :filterDate
            GROUP BY 
                l.accountID
        )
        SELECT 
            ad.accountID,
            ad.amount,
            ad.real_amount,
            ad.effective_amount,
            lc.login_count,
            (lc.login_count * ad.effective_amount) AS total
        FROM 
            AccountData ad
        INNER JOIN 
            LoginInfoCount lc ON ad.accountID = lc.accountID;
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':filterDate', $filterDate, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    
    function manage_category($category_form, $action = "insert")
    {
        if($action == "insert") {
            $actionName = "new";
            if(!$this->validate_action(["category"=>"new"], true)) return ;
        }else {
            $actionName = "edit";
            if(!$this->validate_action(["category"=>"edit"], true)) return ;
            
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
        if(!$this->validate_action(["category"=>"delete"], true)) return ;
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
        $deleteForm = "<td class='flex d-flex'>";
        
        $id = $category['ID'];
        // name of the category
        $name = $category['name'];
        // icon url
        // No of account inside category
        $no = number_format($this->get_no_of_account_in_category($id));
        // date added
        $date = $this->date_format($category['date']);
        if($this->validate_action(["category"=> "edit"])) {
            $deleteForm .= "<a href='index?p=category&action=edit&id=$id' class='btn btn-primary btn-sm'>Edit</a>";
        }
        if($this->validate_action(["category"=> "delete"])) {
            $deleteForm .="
                            <form action='' id='foo'>
                                <input type='hidden' name='ID' value='$id'>
                                <input type='hidden' name='delete_category' value='approved'>
                                <input type='hidden' name='page' value='category'>
                                <input type='hidden' name='confirm' value='You are about to delete this $name'>
                                <div id='custommessage'></div>
                                <button type='submit' class='ml-2 btn btn-light-danger d-flex align-items-center gap-3 text-danger' href='#'><i class='fs-4 ti ti-trash'></i>Delete</button>
                            </form>
                        ";
        }
        $deleteForm .= "</td>";

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
                        
                            <td>
                            $date
                            </td>
                            <td>
                            $deleteForm
                            </td>
                    </tr>";
    }
}