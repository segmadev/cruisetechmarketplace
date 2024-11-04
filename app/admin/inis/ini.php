<?php
// define("ROOT", $_SERVER['DOCUMENT_ROOT']."/invest2/");
require_once "include/session.php";
require_once "include/side.php";
require_once "../consts/main.php";
require_once "../include/phpmailer/PHPMailerAutoload.php";
require_once '../vendor/autoload.php';
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(rootFile);
$dotenv->load();
require_once "include/database.php";
require_once "functions/roles.php";
$r = new roles;
$d = new database;
$admin = $d->getall("admins", "token = ?", [$adminToken]);
if(!is_array($admin)) {
    $d->message("Unable to identify admin", "error");
    exit();
}
$adminID = $admin['ID'];
define("adminID", $adminID);
require_once "../consts/general.php";
require_once "../consts/Regex.php";
require_once "../content/content.php";
require_once "../functions/notifications.php";
require_once "../functions/users.php";
require_once "functions/users.php";
$u = new users;
$c = new content;
$route = "";
$page = "dashboard";
$script = [];
$userID = "admin";
define("ADMINROLE", $r->get_role($admin['roleID']));
if (isset($_GET['p'])) {
    $page = htmlspecialchars($_GET['p']);
}

if (isset($_GET['action'])) {
    $route = htmlspecialchars($_GET['action']);
}

$navs = [
    "home" => [
        "title" => "Home",
        "links" => [
            "dashboard" => [
                "list" => [
                    "a" => "index", 
                    "title" => "Dashboard", 
                ],
                    "icon" => "ti ti-home" // Main icon for Dashboard
            ],
        ]
    ],
    "accountManagement" => [
        "title" => "Account Management",
        "links" => [
            "account" => [
                "new" => [
                    "a" => "index?p=account&action=new", 
                    "title" => "Publish Account"
                ],
                "list" => [
                    "a" => "index?p=account&action=list", 
                    "title" => "Manage Accounts"
                ],
                "icon" => "ti ti-layout-sidebar" // Main icon for Accounts
            ],
            "platform" => [
                "new" => [
                    "a" => "index?p=platform&action=new", 
                    "title" => "Create Platform"
                ],
                "list" => [
                    "a" => "index?p=platform&action=list", 
                    "title" => "List of Platforms"
                ],
                "icon" => "ti ti-layout-grid" // Main icon for Platforms
            ],

            "category" => [
                "new" => [
                    "a" => "index?p=category&action=new", 
                    "title" => "Create category"
                ],
                "list" => [
                    "a" => "index?p=category&action=list", 
                    "title" => "List of categories"
                ],
                "icon" => "ti ti-layout-grid" // Main icon for Platforms
            ],

            "orders" => [
                "list" => [
                    "a" => "index?p=orders", 
                    "title" => "Orders", 
                ],
                    "icon" => "ti ti-list" // Main icon for Dashboard
            ],

        ]
    ],

    "userManagement" => [
        "title" => "Users",
        "links" => [
            "users" => [
                "new" => [
                    "a" => "index?p=users&action=new", 
                    "title" => "Create", 
                    "icon" => "ti ti-user-plus" 
                ],
                "list" => [
                    "a" => "index?p=users", 
                    "title" => "Manage Users", 
                ],
                "icon" => "ti ti-users" 
            ],

            "deposits" => [
                "list" => [
                    "a" => "index?p=deposit", 
                    "title" => "Payments", 
                ],
                "icon" => "ti ti-users" 
            ]

        ]
    ],

    "Management" => [
        "title" => "Management",
        "links" => [
            "admins" => [
                "new" => [
                    "a" => "index?p=admins&action=new", 
                    "title" => "Add New Admin", 
                ],
                "list" => [
                    "a" => "index?p=admins", 
                    "title" => "Manage Admins", 
                ],
                "icon" => "ti ti-users" 
            ],
            "settings" => [
                "list" => [
                    "a" => "index?p=settings", 
                    "title" => "Manage Settings", 
                ],
                "icon" => "ti ti-settings" 
            ],

            "profile" => [
                "edit" => [
                    "a" => "index?p=profile&action=edit", 
                    "title" => "Edit profile", 
                ],
                "icon" => "ti ti-fingerprint" 
            ],
            "roles" => [
                "new" => [
                    "a" => "index?p=roles&action=new", 
                    "title" => "Create", 
                ],
                "list" => [
                    "a" => "index?p=roles", 
                    "title" => "Manage", 
                ],
                "icon" => "ti ti-key" 
            ]

        ]
    ],

    "content" => [
        "title" => "Website Content",
        "links" => [
            "content" => [
                "home" => [
                    "a" => "index?p=content&action=home", 
                    "title" => "Home Page", 
                ],
                
                "icon" => "ti ti-home" 
            ],
            "features" => [
                "new" => [
                    "a" => "index?p=fetures&action=new", 
                    "title" => "Add new feature", 
                ],
                "list" => [
                    "a" => "index?p=fetures", 
                    "title" => "Manage fetures", 
                ],
                "icon" => "ti ti-components" 
            ],

            "how_it_works" => [
                "new" => [
                    "a" => "index?p=how_it_works&action=new", 
                    "title" => "Add stage", 
                ],
                "list" => [
                    "a" => "index?p=how_it_works", 
                    "title" => "Manage stages", 
                ],
                "icon" => "ti ti-table-filled" 
            ],

            "testimonies" => [
                "new" => [
                    "a" => "index?p=testimonies&action=new", 
                    "title" => "Add new testimony", 
                ],
                "list" => [
                    "a" => "index?p=testimonies", 
                    "title" => "Manage testimonies", 
                ],
                "icon" => "ti ti-stars" 
            ],   
        ]
    ],

    "templates" => [
        "title" => "Templates",
        "links" => [
            "email_template" => [
                "list" => [
                    "a" => "index?p=email_template", 
                    "title" => "Email Templates", 
                ],
                
                "icon" => "ti ti-inbox" 
            ],
           
        ]
    ],


];