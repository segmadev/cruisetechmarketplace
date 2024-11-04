<?php 
    $defaultRoles = $r->defaultRoles();
    if(isset($_GET['id']))  {
        $role = $r->getall("roles", "ID = ?",[htmlspecialchars($_GET['id'])]);
        $rolePermissions = $r->get_role(htmlspecialchars($_GET['id']));
    }
    if($action == "list") {
        $roles = $r->getall("roles", "date != ? order by date DESC", [""], fetch: "all");
    }
    