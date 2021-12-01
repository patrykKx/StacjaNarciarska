<?php
require_once 'Database.php';
require_once 'ServicemenValidation.php';

class Servicemen{
    public function addService() {
        $_SESSION['goToServicemenPage'] = 'service';
        $login = filter_input(INPUT_POST, "userLogin");
        $type = filter_input(INPUT_POST, "serviceType");
        $description = filter_input(INPUT_POST, "serviceDescription");
        $cost = $this->getServiceCost($type, false);
        if(ServicemenValidation::userLoginValidation($login) && ServicemenValidation::serviceTypeValidation($type) && ServicemenValidation::serviceDescriptionValidation($description)) {
            $this->insertNewService($login, $type, $description, $cost);
        }
        else {
            self::putServiceValuesToSession($login, $type, $description, $cost);
        }
        
        header("Location: servicemenPage.php");
    }
    
    public function insertNewService($login, $type, $description, $cost) {
        $db = new Database();
        $now = date("Y-m-d H:i");
        try {
            $result = $db->mysqli->query(
                    sprintf("SELECT idAccount FROM account WHERE login='%s'",
                    mysqli_real_escape_string($db->mysqli, $login)));
            $row = $result->fetch_assoc();
            $idUser = $row['idAccount'];
            $db->mysqli->query(
                    sprintf("INSERT INTO service VALUES (NULL, $idUser, '%s', '%s', 'W realizacji', '$now', NULL, $cost)",
                    mysqli_real_escape_string($db->mysqli, $description),
                    mysqli_real_escape_string($db->mysqli, $type)));
            $_SESSION['addService'] = true;
        } catch (Exception $ex) {
            $_SESSION['addService'] = false;
        }
    }
    
    public static function putServiceValuesToSession($login, $type, $description, $cost) {
        $_SESSION['serviceLogin'] = $login;
        $_SESSION['serviceType'] = $type;
        $_SESSION['serviceDescription'] = $description;
        $_SESSION['serviceCost'] = $cost;
    }
    
    public function getUserLogins() {
        $db = new Database();
        $logins = [];
        try {
            $result = $db->mysqli->query("SELECT login FROM account WHERE deletedDate IS NULL AND role='Użytkownik'");
            while($row = $result->fetch_assoc()) {
                array_push($logins, $row['login']);
            }
        } catch (Exception $ex) {
        }
        echo json_encode($logins);
    }
    
    public function getServiceCost($type = '', $fromJS = true) {
        if($fromJS) {
           $type = filter_input(INPUT_POST, "type"); 
        }
        $db = new Database();
        $cost = '';
        if($type == "Podstawowy") {
            $result = $db->mysqli->query("SELECT smallService FROM tariff ORDER BY idTariff DESC LIMIT 1");
            $row = $result->fetch_assoc();
            $cost = $row["smallService"];
        }
        else if($type == "Kompleksowy") {
            $result = $db->mysqli->query("SELECT mediumService FROM tariff ORDER BY idTariff DESC LIMIT 1");
            $row = $result->fetch_assoc();
            $cost = $row["mediumService"];
        }
        else if($type == "Zaawansowany") {
            $result = $db->mysqli->query("SELECT bigService FROM tariff ORDER BY idTariff DESC LIMIT 1");
            $row = $result->fetch_assoc();
            $cost = $row["bigService"];
        }
        if($fromJS) {
            echo $cost;           
        }
        else {
            return $cost;
        }
    }
    
    public function getCurrentService() {
        $db = new Database();
        $status = filter_input(INPUT_POST, 'status');
        $all['id'] = [];
        $all['login'] = [];
        $all['name'] = [];
        $all['status'] = [];
        $all['startTime'] = [];
        $all['type'] = [];
        try {
            $result = $db->mysqli->query(
                    sprintf("SELECT idService, login, name, status, type, startTime FROM account a INNER JOIN service s ON a.idAccount=s.idUser WHERE status='%s'",
                    mysqli_real_escape_string($db->mysqli, $status)));
            while($row = $result->fetch_assoc()) {
                array_push($all['id'], $row['idService']);
                array_push($all['login'], $row['login']);
                array_push($all['name'], $row['name']);
                array_push($all['status'], $row['status']);
                array_push($all['startTime'], $row['startTime']);
                array_push($all['type'], $row['type']);
            }
        } catch (Exception $ex) {
        }
        echo json_encode($all);
    }
    
    public function changeServiceStatusToReadyToPickUp() {
        $_SESSION['goToServicemenPage'] = 'service';
        $idService = filter_input(INPUT_POST, 'idService');
        if(ServicemenValidation::idServiceValidation($idService, 'W realizacji')) {
            $db = new Database();
            try {
                $db->mysqli->query(
                        sprintf("UPDATE service SET status='Gotowe do odebrania' WHERE idService=%s",
                        mysqli_real_escape_string($db->mysqli, $idService)));
                $_SESSION['changeServiceStatus'] = true;
            } catch (Exception $ex) {
                $_SESSION['changeServiceStatus'] = false;
            }
        }
        header("Location: servicemenPage.php");
    }
    
    public function changeServiceStatusToPickedUp() {
        $_SESSION['goToServicemenPage'] = 'service';
        $idService = filter_input(INPUT_POST, 'idService');
        $now = date("Y-m-d H:i");
        if(ServicemenValidation::idServiceValidation($idService, 'Gotowe do odebrania')) {
            $db = new Database();
            try {
                $db->mysqli->query(
                        sprintf("UPDATE service SET status='Zakończone', finishTime='$now' WHERE idService=%s",
                        mysqli_real_escape_string($db->mysqli, $idService)));
                $_SESSION['changeServiceStatus'] = true;
            } catch (Exception $ex) {
                $_SESSION['changeServiceStatus'] = false;
            }
        }
        header("Location: servicemenPage.php");
    }
    
    public function getServiceHistory() {
        $db = new Database();
        $year = filter_input(INPUT_POST, 'year');
        $month = filter_input(INPUT_POST, 'month');
        $all['login'] = [];
        $all['name'] = [];
        $all['type'] = [];
        $all['startTime'] = [];
        $all['finishTime'] = [];
        $all['cost'] = [];
        try {
            $result = $db->mysqli->query(
                    sprintf("SELECT login, name, type, startTime, finishTime, cost FROM account a INNER JOIN service s ON a.idAccount=s.idUser WHERE status='Zakończone' AND YEAR(finishTime)='%s' AND MONTH(finishTime)='%s' ORDER BY finishTime DESC",
                            mysqli_real_escape_string($db->mysqli, $year),
                            mysqli_real_escape_string($db->mysqli, $month)));
            while($row = $result->fetch_assoc()) {
                array_push($all['login'], $row['login']);
                array_push($all['name'], $row['name']);
                array_push($all['type'], $row['type']);
                array_push($all['startTime'], $row['startTime']);
                array_push($all['finishTime'], $row['finishTime']);
                array_push($all['cost'], $row['cost']);
            }
        } catch (Exception $ex) {
            
        }
        echo json_encode($all);
    }
    
    public function getEquipmentByType() {
        $type = filter_input(INPUT_POST, 'type');
        $all = [];
        $db = new Database();
        try {
            $result = $db->mysqli->query("SELECT idEquipment FROM equipment WHERE type='$type' AND isDeleted=0");
            while($row = $result->fetch_assoc()) {
                array_push($all, $row['idEquipment']);
            }
        } catch (Exception $ex) {
            
        }
        echo json_encode($all);
    }
    
    public function getEquipmentNameAndSize() {
        $id = filter_input(INPUT_POST, 'id');
        $all['name'] = [];
        $all['size'] = [];
        $all['type'] = [];
        $db = new Database();
        try {
            $result = $db->mysqli->query(
                    sprintf("SELECT name, size, type FROM equipment WHERE idEquipment='%s' AND isDeleted=0",
                    mysqli_real_escape_string($db->mysqli, $id)));
            while($row = $result->fetch_assoc()) {
                array_push($all['name'], $row['name']);
                array_push($all['size'], $row['size']);
                array_push($all['type'], $row['type']);
            }
        } catch (Exception $ex) {
            
        }
        echo json_encode($all);
    }
    
    public function addHire() {
        $_SESSION['goToServicemenPage'] = 'hire';
        $userLogin = filter_input(INPUT_POST, 'userLogin');
        $checkboxSki = filter_input(INPUT_POST, 'checkboxSki');
        $checkboxSkiBoots = filter_input(INPUT_POST, 'checkboxSkiBoots');
        $checkboxSnowboard = filter_input(INPUT_POST, 'checkboxSnowboard');
        $checkboxSnowboardBoots = filter_input(INPUT_POST, 'checkboxSnowboardBoots');
        $checkboxHelmet = filter_input(INPUT_POST, 'checkboxHelmet');
        $skiId = filter_input(INPUT_POST, 'skiId');
        $skiBootsId = filter_input(INPUT_POST, 'skiBootsId');
        $snowboardId = filter_input(INPUT_POST, 'snowboardId');
        $snowboardBootsId = filter_input(INPUT_POST, 'snowboardBootsId');
        $helmetId = filter_input(INPUT_POST, 'helmetId');
        echo $snowboardId."<br/>";
        //Niezaznaczony checkbox to null, puste pole Id to null
        if(ServicemenValidation::userLoginValidation($userLogin) && ServicemenValidation::equipmentHireValidation($checkboxSki, $skiId, "Narty") && ServicemenValidation::equipmentHireValidation($checkboxSkiBoots, $skiBootsId, "Buty narciarskie") && ServicemenValidation::equipmentHireValidation($checkboxSnowboard, $snowboardId, "Snowboard") && ServicemenValidation::equipmentHireValidation($checkboxSnowboardBoots, $snowboardBootsId, "Buty snowboardowe") && ServicemenValidation::equipmentHireValidation($checkboxHelmet, $helmetId, "Kask")) {
            $IDs = $this->putIDsToArray($skiId, $skiBootsId, $snowboardId, $snowboardBootsId, $helmetId);
            if(count($IDs) > 0) {
                $this->insertNewHire($IDs, $userLogin);
            }
            else {
                $_SESSION['addHire'] = false;
            }
        }
        else {
            $_SESSION['addHire'] = false;
        }
        header("Location: servicemenPage.php");
    }
    public function putIDsToArray($skiId, $skiBootsId, $snowboardId, $snowboardBootsId, $helmetId) {
        $IDs = [];
        if(!is_null($skiId)) {
            array_push($IDs, $skiId);
        }
        if(!is_null($skiBootsId)) {
            array_push($IDs, $skiBootsId);
        }
        if(!is_null($snowboardId)) {
            array_push($IDs, $snowboardId);
        }
        if(!is_null($snowboardBootsId)) {
            array_push($IDs, $snowboardBootsId);
        }
        if(!is_null($helmetId)) {
            array_push($IDs, $helmetId);
        }
        return $IDs;
    }
    public function insertNewHire($IDs, $userLogin) {
        $db = new Database();
        $now = date("Y-m-d H:i");
        $myArr = [];
        //Start transaction
        $db->mysqli->begin_transaction();
        try {
            $result = $db->mysqli->query(
                sprintf("SELECT idAccount FROM account WHERE login='%s'",
                mysqli_real_escape_string($db->mysqli, $userLogin)));
            $row = $result->fetch_assoc();
            $userId = $row['idAccount'];
            $db->mysqli->query(
                sprintf("INSERT INTO hire VALUES(NULL, %s, '%s', NULL, 'Trwa', NULL)", 
                mysqli_real_escape_string($db->mysqli, $userId),
                mysqli_real_escape_string($db->mysqli, $now)));  
            $result2 = $db->mysqli->query("SELECT idHire FROM hire ORDER BY idHire DESC LIMIT 1");
            $row2 = $result2->fetch_assoc();
            $hireId = $row2['idHire'];
            for($i=0; $i<count($IDs); $i++) {
                $db->mysqli->query("INSERT INTO hire_has_equipment VALUES($hireId, '$IDs[$i]')");   
            }  
            $db->mysqli->commit();
            $_SESSION['addHire'] = true;      
        } catch (mysqli_sql_exception $exception) {
            $db->mysqli->rollback();
            $_SESSION['addHire'] = false;
        }
    }
    
    public function getActiveHire() {
        $db = new Database();
        $all['idHire'] = [];
        $all['login'] = [];
        $all['startTime'] = [];
        $all['idEquipment'] = [];
        $all['equipmentName'] = [];
        try {
            $result = $db->mysqli->query("SELECT h.idHire, e.idEquipment, name, startTime, login FROM hire h INNER JOIN hire_has_equipment hhe ON h.idHire=hhe.idHire INNER JOIN equipment e ON e.idEquipment=hhe.idEquipment INNER JOIN account a ON h.idUser=a.idAccount WHERE status='Trwa' ORDER BY h.startTime");
            while($row = $result->fetch_assoc()) {
                array_push($all['idHire'], $row['idHire']);
                array_push($all['idEquipment'], $row['idEquipment']);
                array_push($all['login'], $row['login']);
                array_push($all['startTime'], $row['startTime']);
                array_push($all['equipmentName'], $row['name']);
            }
        } catch (Exception $ex) {
        }
        echo json_encode($all);
    }
    
    public function changeActiveHireStatus() {
        $_SESSION['goToServicemenPage'] = 'hire';
        $idHire = filter_input(INPUT_POST, "idHire");
        if(ServicemenValidation::isChangeActiveHireAvailable($idHire)) {
            $now = date("Y-m-d H:i");
            $cost = $this->getHireCost($idHire, $now);
            $db = new Database();
            try {
                $db->mysqli->query(
                    sprintf("UPDATE hire SET finishTime='$now',status='Zakończono',cost=$cost WHERE idHire=%s",
                    mysqli_real_escape_string($db->mysqli, $idHire)));
                $_SESSION['changeHireStatus'] = true;
            } catch (Exception $ex) {
                $_SESSION['changeHireStatus'] = false;
            }
        }
        else {
            $_SESSION['changeHireStatus'] = false;
        }
        header("Location: servicemenPage.php");
    }
    
    public function getHireCost($idHire, $now) {
        $db = new Database();
        $hireTimeUnix = $this->getHireHours($idHire, $now);
        $numberOfEquipments = $this->getHireEquipmentsNumber($idHire);
        $costType = $this->getHireCostType($hireTimeUnix, $numberOfEquipments);
        try {
            $result = $db->mysqli->query("SELECT $costType FROM tariff ORDER BY idTariff DESC LIMIT 1");
            $row = $result->fetch_assoc();
            $cost = $row["$costType"];
            return $cost;
        } catch (Exception $ex) {
        } 
    }
    
    public function getHireHours($idHire, $now) {
        $db = new Database();
        try {
            $result = $db->mysqli->query(
                    sprintf("SELECT startTime FROM hire WHERE idHire=%s AND status='Trwa'",
                    mysqli_real_escape_string($db->mysqli, $idHire)));
            $row = $result->fetch_assoc();
            $startTime = $row['startTime'];
            $startTimeUnix = strtotime($startTime);
            $nowUnix = strtotime($now);
            $hireTimeUnix = $nowUnix-$startTimeUnix;
            return $hireTimeUnix;
        } catch (Exception $ex) {
        }
    }
    
    public function getHireEquipmentsNumber($idHire) {
        $db = new Database();
        try {
            $result = $db->mysqli->query(
                sprintf("SELECT startTime FROM hire h INNER JOIN hire_has_equipment hhe ON h.idHire=hhe.idHire WHERE h.idHire=%s AND status='Trwa'",
                mysqli_real_escape_string($db->mysqli, $idHire)));
            return $result->num_rows;
        } catch (Exception $ex) {
            return 0;
        }
    }
    
    public function getHireCostType($hireTimeUnix, $numberOfEquipments) {
        $costSQL = '';
        if($numberOfEquipments > 1) {
            if($hireTimeUnix > 10800) { //10800s = 1h
                $costSQL = "setRental_allDay";
            }
            else if($hireTimeUnix > 7200) {
                $costSQL = "setRental_3h";
            }
            else if($hireTimeUnix > 3600) {
                $costSQL = "setRental_2h";
            }
            else if($hireTimeUnix >= 0) {
                $costSQL = "setRental_1h";
            }
        }
        else if($numberOfEquipments > 0) {
            if($hireTimeUnix > 10800) { //10800s = 1h
                $costSQL = "oneItemRental_allDay";
            }
            else if($hireTimeUnix > 7200) {
                $costSQL = "oneItemRental_3h";
            }
            else if($hireTimeUnix > 3600) {
                $costSQL = "oneItemRental_2h";
            }
            else if($hireTimeUnix >= 0) {
                $costSQL = "oneItemRental_1h";
            }
        }
        return $costSQL;
    }
    
    public function getHireHistory() {
        $db = new Database();
        $date = filter_input(INPUT_POST, "date");
        $all['login'] = [];
        $all['startTime'] = [];
        $all['finishTime'] = [];
        $all['idEquipment'] = [];
        $all['idHire'] = [];
        $all['equipmentName'] = [];
        $all['cost'] = [];
        try {
            $result = $db->mysqli->query(
                sprintf("SELECT h.idHire, e.idEquipment, name, startTime, finishTime, cost, login FROM hire h INNER JOIN hire_has_equipment hhe ON h.idHire=hhe.idHire INNER JOIN equipment e ON e.idEquipment=hhe.idEquipment INNER JOIN account a ON h.idUser=a.idAccount WHERE status='Zakończono' AND DATE(startTime)='%s' ORDER BY h.startTime",
                mysqli_real_escape_string($db->mysqli, $date)));
            while($row = $result->fetch_assoc()) {
                array_push($all['idHire'], $row['idHire']);
                array_push($all['idEquipment'], $row['idEquipment']);
                array_push($all['login'], $row['login']);
                array_push($all['startTime'], $row['startTime']);
                array_push($all['finishTime'], $row['finishTime']);
                array_push($all['cost'], $row['cost']);
                array_push($all['equipmentName'], $row['name']);
            }
        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
}
