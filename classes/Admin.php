<?php
require_once 'Database.php';
require_once 'Validation.php';

class Admin{
    public function addWorker() {
        $_SESSION['goToAdminPage'] = 'workers';
        $firstname = filter_input(INPUT_POST, 'firstname');
        $surname = filter_input(INPUT_POST, 'surname');
        $birthdate = filter_input(INPUT_POST, 'birthdate');
        $role = filter_input(INPUT_POST, 'role');
        $email = filter_input(INPUT_POST, 'email');
        $login = filter_input(INPUT_POST, 'login');
        $pass = filter_input(INPUT_POST, 'pass');
        $pass2 = filter_input(INPUT_POST, 'pass2');
        $checkboxSkiing = filter_input(INPUT_POST, 'checkboxSkiingInstructor');
        $checkboxSnowboard = filter_input(INPUT_POST, 'checkboxSnowboardInstructor');

        if(Validation::firstnameValidation($firstname) && Validation::surnameValidation($surname) && Validation::birthdateValidation($birthdate) && Validation::emailValidation($email) && Validation::roleValidation($role) && Validation::instructorValidation($role, $checkboxSkiing, $checkboxSnowboard) && Validation::loginValidation($login) && Validation::passwordValidation($pass, $pass2)) {
            self::insertNewUser($firstname, $surname, $birthdate, $email, $login, $pass, $role);
            if($role == "Instruktor") {
                self::insertInstructorType($login, $checkboxSkiing, $checkboxSnowboard);
            }
            $_SESSION['addWorkerSuccess'] = true;  
        }
        else {
            self::putRegisterValuesToSession($firstname, $surname, $birthdate, $email, $login, $pass, $pass2, $role);
        }  
        header('Location: adminPage.php');
    }

    
    public static function insertNewUser($firstname, $surname, $birthdate, $email, $login, $pass, $role) {
        $passHash = password_hash($pass, PASSWORD_DEFAULT);
        $creationDate = date("Y-m-d");
        $db = new Database();
        //Start transaction
        $db->mysqli->begin_transaction();
        try {
            $db->mysqli->query(
                sprintf("INSERT INTO account VALUES(NULL, '%s', '%s', '%s', '$creationDate', NULL)",
                mysqli_real_escape_string($db->mysqli, $login),
                mysqli_real_escape_string($db->mysqli, $passHash),
                mysqli_real_escape_string($db->mysqli, $role))); 
            $idAccount = $db->mysqli->query(
                sprintf("SELECT idAccount FROM account WHERE login='%s'",
                mysqli_real_escape_string($db->mysqli, $login)));
            $row = $idAccount->fetch_assoc(); //Tworzę tab.asocjacyjną o kluczu z nagłówków
            $id = $row['idAccount'];
            $db->mysqli->query(
                sprintf("INSERT INTO personaldata VALUES('%s', '%s', '%s', '%s', '$birthdate')",
                mysqli_real_escape_string($db->mysqli, $id),
                mysqli_real_escape_string($db->mysqli, $firstname),
                mysqli_real_escape_string($db->mysqli, $surname),
                mysqli_real_escape_string($db->mysqli, $email))); 
            /* If code reaches this point without errors then commit the data in the database */
            $db->mysqli->commit();    
        } catch (mysqli_sql_exception $exception) {
            $db->mysqli->rollback();
            $_SESSION['addWorkerSuccess'] = false;  
        }
    }
    
    public static function insertInstructorType($login, $checkboxSkiing, $checkboxSnowboard) {
        $db = new Database();
        try {
            $result = $db->mysqli->query(
                sprintf("SELECT idAccount FROM account WHERE login='%s'",
                mysqli_real_escape_string($db->mysqli, $login)));
            $row = $result->fetch_assoc();
            $idInstructor = $row['idAccount'];
            if($checkboxSkiing == 'ski') {
                $ski = 1;
            } else {
                $ski = 0;
            }
            if($checkboxSnowboard == 'snowboard') {
                $snowboard = 1;
            } else {
                $snowboard = 0;
            }
            $db->mysqli->query(
                sprintf("INSERT INTO instructortype VALUES(%s, $ski, $snowboard)",
                mysqli_real_escape_string($db->mysqli, $idInstructor)));
        } catch (Exception $ex) {
            $_SESSION['addWorkerSuccess'] = false;  
        }
    }
    
    public static function putRegisterValuesToSession($firstname, $surname, $birthdate, $email, $login, $pass, $pass2, $role) {
        $_SESSION['rememberFirstnameAddWorker'] = $firstname;
        $_SESSION['rememberSurnameAddWorker'] = $surname;
        $_SESSION['rememberBirthdateAddWorker'] = $birthdate;
        $_SESSION['rememberEmailAddWorker'] = $email;
        $_SESSION['rememberLoginAddWorker'] = $login;
        $_SESSION['rememberPassAddWorker'] = $pass;
        $_SESSION['rememberPass2AddWorker'] = $pass2;
        $_SESSION['rememberRoleAddWorker'] = $role;
    }
    
    public function getPerson() {
        $type = filter_input(INPUT_POST, 'personType');
        $db = new Database();
        $all['ids'] = []; 
        $all['firstnames'] = []; 
        $all['surnames'] = []; 
        $all['birthdates'] = []; 
        $all['logins'] = []; 
        $all['creationDates'] = []; 
        $all['roles'] = [];
        $all['instructorType'] = [];
        try {
            $result = $db->mysqli->query("SELECT a.idAccount,login,role,creationDate,pd.idAccount,firstname,surname,birthdate FROM account a INNER JOIN personaldata pd ON a.idAccount=pd.idAccount WHERE a.deletedDate IS NULL AND a.role = '$type'");
            while($row = $result->fetch_assoc()) {
               array_push($all['ids'], $row['idAccount']);
               array_push($all['logins'], $row['login']);
               if(isset($_SESSION['role']) && $_SESSION['role'] == $row['role']) {
                   array_push($all['roles'], 'me');
               } 
               else {
                   array_push($all['roles'], $row['role']);
               }
               array_push($all['instructorType'], $this->getInstructorType($db, $row));
               array_push($all['creationDates'], $row['creationDate']);
               array_push($all['firstnames'], $row['firstname']);
               array_push($all['surnames'], $row['surname']);
               array_push($all['birthdates'], $row['birthdate']);
            }

        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function getInstructorType($db, $row) {
        $type = '';
        if($row['role'] == "Instruktor") {
            $result2 = $db->mysqli->query("SELECT isSkiing, isSnowboard FROM instructortype WHERE idInstructor=".$row['idAccount']);
            $row2 = $result2->fetch_assoc();
            if($row2['isSkiing'] == 1 && $row2['isSnowboard'] == 1) {
                $type = 'both';
            }
            else if($row2['isSkiing'] == 1) {
                $type = 'ski';
            }
            else if($row2['isSnowboard'] == 1) {
                $type = 'snowboard';
            }
        }
        else {
            $type = null;
        }
        return $type;
    }
    
    public function deletePerson() {
        $workerId = filter_input(INPUT_POST, 'workerId');
        $deletedDate = date("Y-m-d H:i");
        $db = new Database();
        try {
            $db->mysqli->query(
                sprintf("UPDATE account SET deletedDate = '%s' WHERE idAccount = '%s'",
                mysqli_real_escape_string($db->mysqli, $deletedDate),
                mysqli_real_escape_string($db->mysqli, $workerId)));

        } catch (Exception $ex) {        
        }
    }
    
    public function submitTariff() { 
        $_SESSION['goToAdminPage'] = 'station';
        $tariffValues = $_POST['tariff'];
        $OK = true;
        foreach($tariffValues as $value) {
            if(Validation::tariffValidation($value) == false) {
                $OK = false;
                break;
            }
        }
        if($OK) {
            self::insertNewTariff($tariffValues);
        }
        header('Location: adminPage.php');
    }
    
    public static function insertNewTariff($tariffValues) {
        $db = new Database();
        $date = date("Y-m-d");
        $sql = 'INSERT INTO tariff VALUES(NULL,';
        foreach($tariffValues as $value) {
            $sql .= mysqli_real_escape_string($db->mysqli,$value).',';
        }
        $sql .= "'$date'".')';

        try {
            $db->mysqli->query($sql);
            $_SESSION['addTariff'] = true; 
        } catch (Exception $ex) {
            $_SESSION['addTariff'] = false;
        }
    }
    
    public function submitConditions() {
        $_SESSION['goToAdminPage'] = 'station';
        $minSnowCover = filter_input(INPUT_POST, 'minSnowCover');
        $maxSnowCover = filter_input(INPUT_POST, 'maxSnowCover');
        $conditions = filter_input(INPUT_POST, 'conditions');
        $snowType = filter_input(INPUT_POST, 'snowType');
        $description = filter_input(INPUT_POST, 'description');
        
        if(Validation::conditionsValidation($minSnowCover, $maxSnowCover, $conditions, $snowType)) {
            self::insertNewConditions($minSnowCover, $maxSnowCover, $conditions, $snowType, $description);
        }
        header('Location: adminPage.php');
    }
    
    public static function insertNewConditions($min, $max, $con, $snowType, $descr) {
        $db = new Database();
        $date = date('Y-m-d H:i');
        try {
            $db->mysqli->query(
                sprintf("INSERT INTO conditions VALUES(NULL, %s, %s, '%s', '%s', '%s', '$date')",
                mysqli_real_escape_string($db->mysqli, $min),
                mysqli_real_escape_string($db->mysqli, $max),
                mysqli_real_escape_string($db->mysqli, $con),
                mysqli_real_escape_string($db->mysqli, $snowType),
                mysqli_real_escape_string($db->mysqli, $descr)));
            $_SESSION['addConditions'] = true; 
        } catch (Exception $ex) {
            $_SESSION['addConditions'] = false;
        }
    }
    
    public function addEquipment() {
        $_SESSION['goToAdminPage'] = 'equipment';
        $type = filter_input(INPUT_POST, 'type');
        $prefixId = filter_input(INPUT_POST, 'prefixId');
        $id = filter_input(INPUT_POST, 'id');
        $name = filter_input(INPUT_POST, 'name');
        $size = filter_input(INPUT_POST, 'size');
        $url = filter_input(INPUT_POST, 'photoURL');
        $ID = $prefixId.$id;

        if(Validation::equipmentTypeValidation($type) && Validation::equipmentIdValidation($ID, $type) && Validation::equipmentNameValidation($name) && Validation::equipmentSizeValidation($size) && Validation::equipmentURLValidation($url)) {
            self::insertNewEquipment($ID, $type, $name, $size, $url);
        }
        else {
            self::putEquipmentValuesToSession($prefixId, $id, $type, $name, $size, $url);
        }  
        header('Location: adminPage.php');
    }
    
    public static function insertNewEquipment($ID, $type, $name, $size, $url) {
        $db = new Database();
        if($url == '') {
            $saveSQL = sprintf("INSERT INTO equipment VALUES('%s', '%s', '%s', %s, NULL, 0)",
                       mysqli_real_escape_string($db->mysqli, $ID),
                       mysqli_real_escape_string($db->mysqli, $type),
                       mysqli_real_escape_string($db->mysqli, $name),
                       mysqli_real_escape_string($db->mysqli, $size));
        }
        else {
            $saveSQL = sprintf("INSERT INTO equipment VALUES('%s', '%s', '%s', %s, '%s', 0)",
                       mysqli_real_escape_string($db->mysqli, $ID),
                       mysqli_real_escape_string($db->mysqli, $type),
                       mysqli_real_escape_string($db->mysqli, $name),
                       mysqli_real_escape_string($db->mysqli, $size),
                       mysqli_real_escape_string($db->mysqli, $url));
        }
        try {
            $db->mysqli->query($saveSQL);
            $_SESSION['addEquipment'] = true; 
        } catch (Exception $ex) {
            $_SESSION['addEquipment'] = false;
        }
    }
    
    public static function putEquipmentValuesToSession($prefixId, $id, $type, $name, $size, $url) {
        $_SESSION['rememberEquipmentPrefixId'] = $prefixId;
        $_SESSION['rememberEquipmentId'] = $id;
        $_SESSION['rememberEquipmentType'] = $type;
        $_SESSION['rememberEquipmentName'] = $name;
        $_SESSION['rememberEquipmentSize'] = $size;
        $_SESSION['rememberEquipmentURL'] = $url;
    }
    
    public function getEquipment() {
        $type = filter_input(INPUT_POST, 'equipmentType');
        $ids = [];
        $names = [];
        $sizes = [];
        $photoURLs = [];
        $types = [];
        $all = [];
        $db = new Database();
        try {
            $result = $db->mysqli->query("SELECT * FROM equipment WHERE type = '$type' AND isDeleted=0");
        } catch (Exception $ex) {
        }
        while($row = $result->fetch_assoc()) {
            array_push($ids, $row['idEquipment']);
            array_push($names, $row['name']);
            array_push($sizes, $row['size']);
            array_push($photoURLs, $row['photoURL']);
            array_push($types, $row['type']);
        }
        $all['ids'] = $ids; 
        $all['names'] = $names;
        $all['sizes'] = $sizes; 
        $all['photoURLs'] = $photoURLs; 
        $all['types'] = $types; 

        echo json_encode($all);
    }
    
    public function deleteEquipment() {
        $equipmentId = filter_input(INPUT_POST, 'equipmentId');
        $db = new Database();
        try {
            $db->mysqli->query(
                sprintf("UPDATE equipment SET isDeleted=1 WHERE idEquipment='%s'",
                mysqli_real_escape_string($db->mysqli, $equipmentId)));
        } catch (Exception $ex) {
        }
    }
    
    public function getNextAvailableEquipmentId() {
        $db = new Database();
        $type = filter_input(INPUT_POST, 'type');
        try {
            $result = $db->mysqli->query(
                sprintf("SELECT idEquipment FROM equipment WHERE type='%s' ORDER BY idEquipment DESC ",
                mysqli_real_escape_string($db->mysqli, $type)));
        } catch (Exception $ex) {

        }
    }
    
    public function submitCalendar() {
        $_SESSION['goToAdminPage'] = 'station';
        $day = filter_input(INPUT_POST, "date");
        $isOpen = filter_input(INPUT_POST, "isOpen");
        $startTime = filter_input(INPUT_POST, "openingHour");
        $finishTime = filter_input(INPUT_POST, "closingHour");
        
        $startTimeUnix = strtotime($startTime); //sekundy od stycznia 1970
        $finishTimeUnix = strtotime($finishTime);
        
        if(Validation::dayValidation($day, $isOpen, $startTimeUnix, $finishTimeUnix)) {
            if(Validation::isDayInDatabase($day)) { //Wtedy UPDATE
                if($isOpen == 1) {
                    self::updateDay($day, $isOpen, $startTime, $finishTime);
                }
                else if($isOpen == 0){
                    self::deleteDay($day);
                }
            }
            else { 
                if($isOpen == 1) { //Wtedy INSERT
                    self::insertNewDay($day, $isOpen, $startTime, $finishTime);
                } 
            }
        }
        header('Location: adminPage.php');
    }
    public static function deleteDay($day) {
        $db = new Database();
        $db->mysqli->begin_transaction();
        try {
            $db->mysqli->query(
                sprintf("DELETE FROM openingschedule WHERE date='%s'",
                mysqli_real_escape_string($db->mysqli, $day))); 
            $db->mysqli->query(
                sprintf("UPDATE lesson SET cost=0, isCancelled=1 WHERE DATE(startDate)='%s'",
                mysqli_real_escape_string($db->mysqli, $day))); 
            $db->mysqli->query(
                sprintf("UPDATE skipass SET cost=0, isCancelled=1 WHERE DATE(startTime)='%s'",
                mysqli_real_escape_string($db->mysqli, $day))); 
            $db->mysqli->commit();
            $_SESSION['addCalendar'] = true;
        } catch (Exception $ex) {
            $db->mysqli->rollback();
            $_SESSION['addCalendar'] = false; 
        }
    }
     
    public static function insertNewDay($day, $isOpen, $startTime, $finishTime) {
        $db = new Database();
        try {
            $db->mysqli->query(
                sprintf("INSERT INTO openingschedule VALUES('%s', %s, '%s', '%s')",
                mysqli_real_escape_string($db->mysqli, $day),
                mysqli_real_escape_string($db->mysqli, $isOpen),
                mysqli_real_escape_string($db->mysqli, $startTime),
                mysqli_real_escape_string($db->mysqli, $finishTime))); 
            $_SESSION['addCalendar'] = true; 
        } catch (Exception $ex) {
            $_SESSION['addCalendar'] = false; 
        }
    }
    
    public static function updateDay($day, $isOpen, $startTime, $finishTime) {
        $db = new Database();
        $startDatatime = $day." ".$startTime;
        $finishDatatime = $day." ".$finishTime;
        $db->mysqli->begin_transaction();
        try {
            $db->mysqli->query(
                sprintf("UPDATE openingschedule SET date='%s', isOpen=%s, startTime='%s', finishTime='%s' WHERE date='%s'",
                mysqli_real_escape_string($db->mysqli, $day),
                mysqli_real_escape_string($db->mysqli, $isOpen),
                mysqli_real_escape_string($db->mysqli, $startTime),
                mysqli_real_escape_string($db->mysqli, $finishTime),
                mysqli_real_escape_string($db->mysqli, $day)));
            $db->mysqli->query(
                sprintf("UPDATE lesson SET cost=0, isCancelled=1 WHERE DATE(startDate)='%s' AND (startDate < '".$startDatatime."' OR finishDate > '".$finishDatatime."')",
                mysqli_real_escape_string($db->mysqli, $day))); 
            $db->mysqli->query(
                sprintf("UPDATE skipass SET cost=0, isCancelled=1 WHERE DATE(startTime)='%s' AND (startTime < '".$startDatatime."' OR finishTime > '".$finishDatatime."')",
                mysqli_real_escape_string($db->mysqli, $day))); 
            $db->mysqli->commit();
            $_SESSION['addCalendar'] = true; 
        } catch (Exception $ex) {
            $db->mysqli->rollback();
            $_SESSION['addCalendar'] = false; 
        }
    }
    
    public function getWorkersNamesAndLogins(){
        $role = filter_input(INPUT_POST, "role");
        $db = new Database();
        $all['logins'] = [];
        $all['firstnames'] = [];
        $all['surnames'] = [];
        $all['instructors'] = [];
        try {
            $result = $db->mysqli->query("SELECT a.idAccount, login, firstname, surname, role FROM account a INNER JOIN personaldata pd ON a.idAccount=pd.idAccount WHERE a.deletedDate IS NULL AND a.role = '$role'");
            while($row = $result->fetch_assoc()) {
                array_push($all['logins'], $row['login']);
                array_push($all['firstnames'], $row['firstname']);
                array_push($all['surnames'], $row['surname']);
                array_push($all['instructors'], $this->getInstructorType($db, $row));
            }
        } catch (Exception $ex) {
        }

        echo json_encode($all);
    }
    
    public function addTimetable() {
        $_SESSION['goToAdminPage'] = 'timetable';
        $day = filter_input(INPUT_POST, "day");
        $_SESSION['calendarDay'] = "'".$day."'";
        $workerStartHour = filter_input(INPUT_POST, "workerStartHour");
        $workerFinishHour = filter_input(INPUT_POST, "workerFinishHour");
        $workerStartHourUnix = strtotime($workerStartHour); //sekundy od stycznia 1970
        $workerFinishHourUnix = strtotime($workerFinishHour);
        $workerLogin = filter_input(INPUT_POST, "workerLogin");
        
        if(Validation::timetableValidation($day, $workerStartHourUnix, $workerFinishHourUnix, $workerLogin)) {
            self::insertNewTimetable($workerLogin, $day." ".$workerStartHour, $day." ".$workerFinishHour);
        }
        header("Location: adminPage.php");
    }
    
    public static function insertNewTimetable($login, $startTime, $finishTime) {
        $db = new Database();
        try {
            $result = $db->mysqli->query(
                        sprintf("SELECT idAccount FROM account WHERE login = '%s'",
                        mysqli_real_escape_string($db->mysqli, $login)));
            if($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $userId = $row['idAccount'];
                $db->mysqli->query(
                    sprintf("INSERT INTO workschedule VALUES(NULL, %s, '%s', '%s')",
                    mysqli_real_escape_string($db->mysqli, $userId),
                    mysqli_real_escape_string($db->mysqli, $startTime),
                    mysqli_real_escape_string($db->mysqli, $finishTime)));
                $_SESSION['addTimetable'] = true;
            }
        } catch (Exception $ex) {
            $_SESSION['addTimetable'] = false;
        }
    }
    
    public function getTimetable() {
        $day = filter_input(INPUT_POST, "date");
        $db = new Database();
        $all['ids'] = [];
        $all['surnames'] = [];
        $all['firstnames'] = [];
        $all['logins'] = [];
        $all['roles'] = [];
        $all['startTimes'] = [];
        $all['finishTimes'] = [];
        $all['instructorType'] = [];
        try {
            $result = $db->mysqli->query(
                      "SELECT a.idAccount, idworkschedule, surname, firstname, login, startTime, finishTime, role
                       FROM workschedule ws INNER JOIN account a ON ws.idUser=a.idAccount 
                       INNER JOIN personaldata pd ON pd.idAccount=a.idAccount
                       WHERE DATE(ws.startTime) = '$day'
                       ORDER BY ws.startTime");
            while($row = $result->fetch_assoc()) {
                array_push($all['ids'], $row['idworkschedule']);
                array_push($all['surnames'], $row['surname']);
                array_push($all['firstnames'], $row['firstname']);
                array_push($all['logins'], $row['login']);
                array_push($all['roles'], $row['role']);
                array_push($all['startTimes'], substr($row['startTime'], -9, -3));
                array_push($all['finishTimes'], substr($row['finishTime'], -9, -3));
                array_push($all['instructorType'], $this->getInstructorType($db, $row));
            }
        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function deletePersonFromTimetable() {
        $id = filter_input(INPUT_POST, "id");
        $db = new Database();
        try {
            $db->mysqli->query(
                    sprintf("DELETE FROM workschedule WHERE idworkschedule=%s",
                    mysqli_real_escape_string($db->mysqli, $id)));
        } catch (Exception $ex) {

        }
    }
    
    public function showWorkerDays() {
        $year = filter_input(INPUT_POST, "year");
        $month = filter_input(INPUT_POST, "month");
        $all = [];
        $db = new Database();
        try {
            $result = $db->mysqli->query(
                sprintf("SELECT DISTINCT DATE(startTime) FROM workschedule WHERE YEAR(startTime)=%s AND MONTH(startTime)=%s",
                mysqli_real_escape_string($db->mysqli, $year),
                mysqli_real_escape_string($db->mysqli, $month)));
            while($row = $result->fetch_assoc()) {
                array_push($all, $row['DATE(startTime)']);
            }
        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function getEquipmentData() {
        $id = filter_input(INPUT_POST, "id");
        $db = new Database();
        $all = [];
        try {
            $result = $db->mysqli->query(
                sprintf("SELECT idEquipment, type, name, size, photoURL FROM equipment WHERE idEquipment='%s' AND isDeleted=0",
                mysqli_real_escape_string($db->mysqli, $id)));
            if($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                array_push($all, $row["idEquipment"]);
                $_SESSION['oldEquipmentId'] = $id;
                array_push($all, $row["type"]);
                $_SESSION['oldEquipmentType'] = $row["type"];
                array_push($all, $row["name"]);
                array_push($all, $row["size"]);
                array_push($all, $row["photoURL"]);
            }
        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function editEquipment() {
        $_SESSION['goToAdminPage'] = 'equipment';
        $name = filter_input(INPUT_POST, 'name');
        $size = filter_input(INPUT_POST, 'size');
        $url = filter_input(INPUT_POST, 'photoURL');
        $prefixId = filter_input(INPUT_POST, 'prefixId');
        $id = filter_input(INPUT_POST, 'id');
        $ID = $prefixId.$id;
        if(!isset($_SESSION['oldEquipmentId']) || !isset($_SESSION['oldEquipmentType'])) {
            $_SESSION['editEquipment'] = false;
        }
        $type = $_SESSION['oldEquipmentType'];
        
        if(Validation::equipmentTypeValidation($type) && Validation::equipmentIdValidation($ID, $type) && Validation::equipmentNameValidation($name) && Validation::equipmentSizeValidation($size) && Validation::equipmentURLValidation($url)) {
            self::updateEquipment($ID, $name, $size, $url);
        }
        unset($_SESSION["oldEquipmentId"]);
        header('Location: adminPage.php');
    }
    
    public static function updateEquipment($ID, $name, $size, $url) {
        $db = new Database();
        $saveSQL = sprintf("UPDATE equipment SET idEquipment='%s', name='%s', size=%s, photoURL='%s' WHERE idEquipment='%s'",
                   mysqli_real_escape_string($db->mysqli, $ID),
                   mysqli_real_escape_string($db->mysqli, $name),
                   mysqli_real_escape_string($db->mysqli, $size),
                   mysqli_real_escape_string($db->mysqli, $url),
                   mysqli_real_escape_string($db->mysqli, $_SESSION['oldEquipmentId']));
        try {
            $db->mysqli->query($saveSQL);
            $_SESSION['editEquipment'] = true; 
        } catch (Exception $ex) {
            $_SESSION['editEquipment'] = false;
        }
    }
    
    public function getCorrectEquipmentId() {
        $type = filter_input(INPUT_POST, 'type');
        $prefixId = filter_input(INPUT_POST, 'prefix');
        $id = filter_input(INPUT_POST, 'id');
        $ID = $prefixId.$id;
        if(Validation::equipmentTypeValidation($type) && Validation::equipmentIdValidation($ID, $type)) {
            unset($_SESSION['equipmentValidation']);
            $db = new Database();
            try {
                $result = $db->mysqli->query(
                    sprintf("SELECT idEquipment FROM equipment WHERE idEquipment='%s'",
                        mysqli_real_escape_string($db->mysqli, $ID)));
                if($result->num_rows == 0) {
                    echo 1;
                }
                else {
                    echo 0;
                }
            } catch (Exception $ex) {
                echo 0;
            }
         }
        else {
            unset($_SESSION['equipmentValidation']);
            echo 0;
        }
    }
    
    public function getMatchingLogins() {
        $login = filter_input(INPUT_POST, 'login');
        $db = new Database();
        $all = [];
        $filteredLogin = sprintf("%s", mysqli_real_escape_string($db->mysqli, $login));
        if($filteredLogin == "") {
            echo json_encode($all);
            exit();
        }
        try {
            $result = $db->mysqli->query("SELECT login FROM account WHERE role='Użytkownik' AND deletedDate IS NULL AND lower(login) LIKE lower('$filteredLogin%')");
            while($row = $result->fetch_assoc()) {
                array_push($all, $row["login"]);
            }
        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function getInfoAboutUser() {
        $login = filter_input(INPUT_POST, 'login');
        $db = new Database();
        //$all = [];
        try {
            $result = $db->mysqli->query(
                sprintf("SELECT a.idAccount, a.login, a.creationDate, pd.firstname, pd.surname, pd.email, pd.birthdate FROM account a INNER JOIN personaldata pd ON a.idAccount=pd.idAccount WHERE a.login='%s' AND a.role='Użytkownik'",
                mysqli_real_escape_string($db->mysqli, $login)));
            if($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $all['id'] = $row['idAccount'];
                $all['login'] = $row['login'];
                $all['creationDate'] =  $row['creationDate'];
                $all['firstname'] =  $row['firstname'];
                $all['surname'] = $row['surname'];
                $all['email'] =  $row['email'];
                $all['birthdate'] =  $row['birthdate'];
                $all['ticket']['cancelled'] = $this->getNumberUserTicket($all['id'], 1);
                $all['ticket']['uncancelled'] = $this->getNumberUserTicket($all['id'], 0);
                $all['lesson']['cancelled'] = $this->getNumberUserLesson($all['id'], 1);
                $all['lesson']['uncancelled'] = $this->getNumberUserLesson($all['id'], 0);
                $all['hire'] = $this->getNumberUserHire($all['id']);
                $all['service'] = $this->getNumberUserService($all['id']);
                $all['totalCost'] = $this->getTotalUserCost($all['id']);
            }
            else {
                echo json_encode([]);
            }
        } catch (Exception $ex) {
            echo json_encode([]);
        }
        echo json_encode($all);
    }
    
    public function getNumberUserTicket($userID, $isCancelled) {
        $db = new Database();
        $number = 0;
        try {
            $result = $db->mysqli->query("SELECT COUNT(*) AS countValue FROM skipass WHERE idUser=$userID AND isCancelled=$isCancelled");
            if($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $number = $row['countValue'];
            }
        } catch (Exception $ex) {
        }
        return $number;
    }
    
    public function getNumberUserHire($userID) {
        $db = new Database();
        $number = 0;
        try {
            $result = $db->mysqli->query("SELECT COUNT(*) AS countValue FROM hire WHERE idUser=$userID");
            if($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $number = $row['countValue'];
            }
        } catch (Exception $ex) {
        }
        return $number;
    }
    
    public function getNumberUserService($userID) {
        $db = new Database();
        $number = 0;
        try {
            $result = $db->mysqli->query("SELECT COUNT(*) AS countValue FROM service WHERE idUser=$userID");
            if($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $number = $row['countValue'];
            }
        } catch (Exception $ex) {
        }
        return $number;
    }
    
    public function getNumberUserLesson($userID, $isCancelled) {
        $db = new Database();
        $number = 0;
        try {
            $result = $db->mysqli->query("SELECT COUNT(*) AS countValue FROM lesson WHERE idUser=$userID AND isCancelled=$isCancelled");
            if($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $number = $row['countValue'];
            }
        } catch (Exception $ex) {
        }
        return $number;
    }
    
    public function getTotalUserCost($userID) {
        $db = new Database();
        $cost = 0;
        try {
            $result = $db->mysqli->query("SELECT SUM(s.cost) AS sumValue FROM account a INNER JOIN skipass s ON a.idAccount=s.idUser WHERE a.idAccount=$userID");
            if($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                if(!is_null($row['sumValue'])) {
                    $cost += (float)$row['sumValue'];
                } 
            }
            $result2 = $db->mysqli->query("SELECT SUM(les.cost) AS sumValue FROM account a INNER JOIN lesson les ON a.idAccount=les.idUser WHERE a.idAccount=$userID");
            if($result2->num_rows == 1) {
                $row2 = $result2->fetch_assoc();
                if(!is_null($row2['sumValue'])) {
                    $cost += (float)$row2['sumValue'];
                } 
            }
            $result3 = $db->mysqli->query("SELECT SUM(ser.cost) AS sumValue FROM account a INNER JOIN service ser ON a.idAccount=ser.idUser WHERE a.idAccount=$userID");
            if($result3->num_rows == 1) {
                $row3 = $result3->fetch_assoc();
                if(!is_null($row3['sumValue'])) {
                    $cost += (float)$row3['sumValue'];
                } 
            }
            $result4 = $db->mysqli->query("SELECT SUM(h.cost) AS sumValue FROM account a INNER JOIN hire h ON a.idAccount=h.idUser WHERE a.idAccount=$userID");
            if($result4->num_rows == 1) {
                $row4 = $result4->fetch_assoc();
                if(!is_null($row4['sumValue'])) {
                    $cost += (float)$row2['sumValue'];
                } 
            }
        } catch (Exception $ex) {

        }
        return $cost;
    }
}
