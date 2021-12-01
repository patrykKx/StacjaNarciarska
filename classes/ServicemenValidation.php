<?php
require_once 'Database.php';

class ServicemenValidation{
    public static function userLoginValidation($login) {
        $db = new Database();
        try {
            $result = $db->mysqli->query(
                    sprintf("SELECT login FROM account WHERE login='%s' AND deletedDate IS NULL",
                    mysqli_real_escape_string($db->mysqli, $login)));
            if($result->num_rows == 1) {
                return true;
            }
            else {
                $_SESSION['loginValidation'] = "Proszę wybrać poprawny login użytkownika!";
                return false;
            }
        } catch (Exception $ex) {
            $_SESSION['loginValidation'] = "Proszę wybrać poprawny login użytkownika!";
            return false;
        } 
    }
    
    public static function serviceTypeValidation($type) {
        if($type != 'Podstawowy' && $type != 'Kompleksowy' && $type != 'Zaawansowany') {
            $_SESSION['serviceTypeValidation'] = "Proszę wybrać poprawny rodzaj serwisu!";
            return false;
        }
        return true;
    }
    
    public static function serviceDescriptionValidation($description) {
        if(strlen($description) < 3) {
            $_SESSION['serviceDescriptionValidation'] = "Proszę podać poprawny opis serwisu!";
            return false;
        }
        return true;
    }
    
    public static function idServiceValidation($id, $status) {
        $db = new Database();
        try {
            $result = $db->mysqli->query(
                    sprintf("SELECT idService FROM service WHERE idService=%s AND status='$status'",
                    mysqli_real_escape_string($db->mysqli, $id)));
            if($result->num_rows == 1) {
                return true;
            }
            else {
                $_SESSION['changeServiceStatus'] = false;
                return false;
            }
        } catch (Exception $ex) {
            return false;
        }
    }
    
    public static function equipmentHireValidation($checkbox, $id, $type) {
        if(!is_null($checkbox) && self::isEquipmentInDatabase($id, $type)) {
            return true;
        }
        else if(is_null($checkbox) && is_null($id)) {
            return true;
        }
        return false;
    }

    public static function isEquipmentInDatabase($id, $type) {
        $db = new Database();
        try {
            $result = $db->mysqli->query(
                    sprintf("SELECT idEquipment FROM equipment WHERE idEquipment='%s' AND type='$type'",
                    mysqli_real_escape_string($db->mysqli, $id)));
            if($result->num_rows == 1) {
                return true;
            }
            else {
                return false;
            }
        } catch (Exception $ex) {
            return false;
        }
    }
    
    public static function isChangeActiveHireAvailable($idHire) {
        $db = new Database();
        try {
            $result = $db->mysqli->query(
                    sprintf("SELECT idHire FROM hire WHERE idHire=%s AND status='Trwa'",
                    mysqli_real_escape_string($db->mysqli, $idHire)));
            if($result->num_rows == 1) {
                return true;
            }
            else {
                return false;
            }
        } catch (Exception $ex) {
            return false;
        }
    }
}
