<?php
require_once './classes/Person.php';

class Access {
    public static function isAccess($role) {
        if(isset($_SESSION["role"]) && $_SESSION["role"] == $role) {}
        else {
            header("Location: index.php");
        }
    }
    
    public static function isAccessForLoggedPerson() {
        if(!isset($_SESSION['role'])) {
            header("Location: index.php");
        }
    }
    
    public static function isAccessForUnloggedPerson() {
        if(isset($_SESSION['role'])) {
            Person::goToPage($_SESSION['role']);
        }
    }
    
    
}
