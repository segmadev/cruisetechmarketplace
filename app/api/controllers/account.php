<?php
require_once ROOT . "functions/accounts.php";
require_once "controllers/user.php";
class ApiAccount extends account
{
    public $user;
    public function __construct()
    {
        // Call parent constructor to set the name
        parent::__construct();
        $this->user = new ApiUser;
    }

    function getCategories()
    {
        $this->apiMessage("all Cat", 200);
    }
}