<?php
require_once 'Database.php';
require_once 'UserValidation.php';

class User{
    public function buyTicket() {
        $_SESSION['goToUserPage'] = 'ticket';
        $day = filter_input(INPUT_POST, "ticketDay");
        $startHour = filter_input(INPUT_POST, "startTicketHour");
        $numberOfHours = filter_input(INPUT_POST, "numberOfHours");
        $OK = false;

        if(UserValidation::numberOfHoursValidation($numberOfHours) && UserValidation::startHourValidation($day, $startHour)) {
            $startDateUnix = strtotime($day." ".$startHour);
            $startDate = date("Y-m-d H:i:s", $startDateUnix);
            $finishDateUnix = $startDateUnix + $numberOfHours*3600;
            $finishDate = date("Y-m-d H:i:s", $finishDateUnix);
            $cost = $this->getTicketCost(false);
            if(UserValidation::ticketDateValidation($day, $startDate, $finishDate) && UserValidation::ticketCostValidation($cost)) {
                $OK = true;
            } 
        }

        if($OK) {
            self::insertNewTicket($startDate, $finishDate, $cost);
        }
        else {
            self::putTicketValuesToSession($day, $startHour, $numberOfHours, $cost);
        }
        header("Location: userPage.php");
    }
    
    public function getTicketCost($fromJS = true) {
       $numberOfHours = filter_input(INPUT_POST, "numberOfHours");
       $db = new Database();
       try {
           $result = $db->mysqli->query("SELECT skipass_1h, skipass_2h, skipass_3h, skipass_allDay FROM tariff ORDER BY idTariff DESC LIMIT 1");
           $row = $result->fetch_assoc();
           if($numberOfHours > 3) {
               $cost = $row['skipass_allDay'];
           }
           else if($numberOfHours > 2) {
               $cost = $row['skipass_3h'];
           }
           else if($numberOfHours > 1) {
               $cost = $row['skipass_2h'];
           }
           else if($numberOfHours > 0){
               $cost = $row['skipass_1h'];
           }
           else {
               $cost = '';
           }
       } catch (Exception $ex) {
           $cost = '';
       }
       if($fromJS === true) {
           echo $cost;
       }
       else {
           return $cost;
       }
    }
    
    public static function insertNewTicket($startTime, $finishTime, $cost) {
        $db = new Database();
        $userId = $_SESSION['id'];
        try {
            $db->mysqli->query(
                sprintf("INSERT INTO skipass VALUES(NULL,$userId,'%s','%s',%s,0)",
                mysqli_real_escape_string($db->mysqli, $startTime),
                mysqli_real_escape_string($db->mysqli, $finishTime),
                mysqli_real_escape_string($db->mysqli, $cost)));
            $_SESSION['buyTicket'] = true;
        } catch (Exception $ex) {
            $_SESSION['buyTicket'] = false;
        }
    }
    
    public static function putTicketValuesToSession($day, $startHour, $numberOfHours, $cost) {
        $_SESSION['ticketDay'] = $day;
        $_SESSION['ticketStartHour'] = $startHour;
        $_SESSION['ticketNumberOfHours'] = $numberOfHours;
        $_SESSION['ticketCost'] = $cost;
    }
    
    public function getTicketList() {
        $db = new Database();
        $year = filter_input(INPUT_POST, "year");
        $userId = $_SESSION['id'];
      
        $all['id'] = [];
        $all['startTime'] = [];
        $all['finishTime'] = [];
        $all['cost'] = [];
        $all['isCancelled'] = [];
        $all['status'] = [];
        try {
            $result = $db->mysqli->query(
                    sprintf("SELECT idSkipass, startTime, finishTime, cost, isCancelled FROM skipass WHERE idUser = $userId AND YEAR(startTime)=%s ORDER BY startTime DESC",
                    mysqli_real_escape_string($db->mysqli, $year)));      
            while($row = $result->fetch_assoc()) {
                array_push($all['id'], $row['idSkipass']);
                array_push($all['startTime'], $row['startTime']);
                array_push($all['finishTime'], $row['finishTime']);
                array_push($all['cost'], $row['cost']);
                array_push($all['isCancelled'], $row['isCancelled']);
                array_push($all['status'], self::getTicketStatus($row['startTime'], $row['finishTime'], $row['isCancelled']));
            }
        }
        catch(Exception $ex) {
            
        }
        echo json_encode($all);
    }
    
    public static function getTicketStatus($startTime, $finishTime, $isCancelled) {
        $now = strtotime("now");
        $startTimeUNIX = strtotime($startTime);
        $finishTimeUnix = strtotime($finishTime);
        if($isCancelled == true) {
            return 'Anulowany';
        }
        else if($now < $startTimeUNIX) {
            return "Nierozpoczęty";
        }
        else if($now >= $startTimeUNIX && $now <= $finishTimeUnix) {
            return "Aktywny";
        }
        else {
            return "Zakończony";
        }
    }
    
    public function cancelTicket() {
        $_SESSION['goToUserPage'] = 'ticket';
        $idTicket = filter_input(INPUT_POST, "ticketId");
        $idUser = $_SESSION['id'];
        $now = date("Y-m-d H:i:s");
        $db = new Database();
        try {
            $result = $db->mysqli->query(
                    sprintf("SELECT idUser, startTime, finishTime FROM skipass WHERE idSkipass=%s AND idUser=%s AND startTime>'$now'",
                    mysqli_real_escape_string($db->mysqli, $idTicket),
                    mysqli_real_escape_string($db->mysqli, $idUser)));
            $row = $result->fetch_assoc();
            if($row == null) {
                $_SESSION['cancelTicket'] = false;
            }
            else {
                $db->mysqli->query(
                    sprintf("UPDATE skipass SET isCancelled=1, cost=0 WHERE idSkipass=%s AND idUser=%s",
                    mysqli_real_escape_string($db->mysqli, $idTicket),
                    mysqli_real_escape_string($db->mysqli, $idUser)));
                $_SESSION['cancelTicket'] = true;
            }
        } catch (Exception $ex) {
            $_SESSION['cancelTicket'] = false;
        }
        header("Location: userPage.php");
    }
    
    public function getUserServiceList() {
        $db = new Database();
        $idUser = $_SESSION['id'];
        $all['name'] = [];
        $all['type'] = [];
        $all['status'] = [];
        $all['startTime'] = [];
        $all['finishTime'] = [];
        $all['cost'] = [];
        try {
            $result = $db->mysqli->query("SELECT name, type, status, startTime, finishTime, cost FROM service WHERE idUser=$idUser");
            while($row = $result->fetch_assoc()) {
                array_push($all['name'], $row['name']);
                array_push($all['type'], $row['type']);
                array_push($all['status'], $row['status']);
                array_push($all['startTime'], $row['startTime']);
                array_push($all['finishTime'], $row['finishTime']);
                array_push($all['cost'], $row['cost']);
            }
        } catch (Exception $ex) {
        }
        echo json_encode($all);
    }
    
    public function getUserHireList() {
        $db = new Database();
        $idUser = $_SESSION['id'];
        $year = filter_input(INPUT_POST, "year");
        $all['idHire'] = [];
        $all['startTime'] = [];
        $all['finishTime'] = [];
        $all['status'] = [];
        $all['cost'] = [];
        $all['equipmentName'] = [];
        $all['idEquipment'] = [];
        $all['photoURL'] = [];
        try {
            $result = $db->mysqli->query(
                    sprintf("SELECT h.idHire, e.idEquipment, name, startTime, finishTime, cost, status, photoURL FROM hire h INNER JOIN hire_has_equipment hhe ON h.idHire=hhe.idHire INNER JOIN equipment e ON e.idEquipment=hhe.idEquipment WHERE h.idUser=$idUser AND YEAR(h.startTime)=%s",
                    mysqli_real_escape_string($db->mysqli, $year)));
            while($row = $result->fetch_assoc()) {
                array_push($all['idHire'], $row['idHire']);
                array_push($all['startTime'], $row['startTime']);
                array_push($all['finishTime'], $row['finishTime']);
                array_push($all['status'], $row['status']);
                array_push($all['cost'], $row['cost']);
                array_push($all['equipmentName'], $row['name']);
                array_push($all['idEquipment'], $row['idEquipment']);
                array_push($all['photoURL'], $row['photoURL']);
            }
        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function getInstructorTimetableByDay() {
        $db = new Database();
        $day = filter_input(INPUT_POST, "day");
        $all['startTimeWorking'] = [];
        $all['finishTimeWorking'] = [];
        $all['login'] = [];
        $all['firstname'] = [];
        $all['surname'] = [];
        $all['skiingInstructor'] = [];
        $all['snowboardInstructor'] = [];
        try {
            $result = $db->mysqli->query(
                sprintf("SELECT startTime, finishTime, login, firstname, surname, isSkiing, isSnowboard FROM workschedule ws INNER JOIN account a ON ws.idUser=a.idAccount INNER JOIN personaldata pd ON a.idAccount=pd.idAccount INNER JOIN instructortype it ON a.idAccount=it.idInstructor WHERE DATE(startTime)='%s'",
                mysqli_real_escape_string($db->mysqli, $day)));
            while($row = $result->fetch_assoc()) {
                array_push($all['startTimeWorking'], $row['startTime']);
                array_push($all['finishTimeWorking'], $row['finishTime']);
                array_push($all['login'], $row['login']);
                array_push($all['firstname'], $row['firstname']);
                array_push($all['surname'], $row['surname']);
                array_push($all['skiingInstructor'], $row['isSkiing']);
                array_push($all['snowboardInstructor'], $row['isSnowboard']);
            }
        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function getInstructorLessonsByDay() {
        $db = new Database();
        $day = filter_input(INPUT_POST, "day");
        $login = filter_input(INPUT_POST, "login");
        $all['lessonStartTime'] = [];
        $all['lessonFinishTime'] = [];
        try {
            $result = $db->mysqli->query(
                sprintf("SELECT startDate, finishDate FROM lesson les INNER JOIN account a ON les.idInstructor=a.idAccount WHERE a.login='%s' AND DATE(les.startDate)='%s' AND les.isCancelled=0",
                mysqli_real_escape_string($db->mysqli, $login),
                mysqli_real_escape_string($db->mysqli, $day)));
            while($row = $result->fetch_assoc()) {
                array_push($all['lessonStartTime'], $row['startDate']);
                array_push($all['lessonFinishTime'], $row['finishDate']);
            }
        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function showInstructorDays() {
        $db = new Database();
        $year = filter_input(INPUT_POST, "year");
        $month = filter_input(INPUT_POST, "month");
        $all = [];
        try {
            $result = $db->mysqli->query(
                sprintf("SELECT DISTINCT DATE(startTime) FROM workschedule ws INNER JOIN account a ON ws.idUser=a.idAccount WHERE YEAR(startTime)=%s AND MONTH(startTime)=%s AND a.role='Instruktor'",
                mysqli_real_escape_string($db->mysqli, $year),
                mysqli_real_escape_string($db->mysqli, $month)));
            while($row = $result->fetch_assoc()) {
                array_push($all, $row['DATE(startTime)']);
            }
        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function getLessonCost($hours = null, $participants = null, $fromJS = true) {
        $db = new Database();
        if($fromJS) { //Zapytanie przychodzi z JS
            $hours = filter_input(INPUT_POST, "hours");
            $participants = filter_input(INPUT_POST, "participants");
            if(!UserValidation::lessonHoursValidation($hours) || !UserValidation::lessonParticipantsValidation($participants)) {
                unset($_SESSION["bookLessonValidation"]);
                echo "";
                exit();
            }
        }  
        $hoursSQL = "lesson_".$hours."h";     
        try {
            $result = $db->mysqli->query("SELECT ".$hoursSQL." FROM tariff ORDER BY idTariff DESC LIMIT 1");
            $row = $result->fetch_assoc();
            $costOnePerson = $row["$hoursSQL"];
            $cost = 0;
            $diff = 0;
            for($a = 0; $a < $participants; $a++) {
                $cost += $costOnePerson - $diff;
                $diff += 5;
            }
        } catch (Exception $ex) {
            return null;
        }
        if($fromJS) {
            echo $cost;
        }
        else {
            return $cost;
        }
        
    }
    
    public function getInstructorList() {
        $db = new Database();
        $type = filter_input(INPUT_POST, "type");
        $day = filter_input(INPUT_POST, "day");
        $all['firstname'] = [];
        $all['surname'] = [];
        $all['login'] = [];
        $typeSQL = "";
        if($type == 'Narciarstwo') {
            $typeSQL = "isSkiing";
        }
        else if($type == 'Snowboard') {
            $typeSQL = "isSnowboard";
        }
        else {
            echo null;
            exit();
        }
        try {
            $result = $db->mysqli->query(
                    sprintf("SELECT firstname, surname, login FROM account a INNER JOIN personaldata pd ON a.idAccount=pd.idAccount INNER JOIN instructortype it ON it.idInstructor=a.idAccount INNER JOIN workschedule ws ON ws.idUser=a.idAccount WHERE a.deletedDate IS NULL AND a.role='Instruktor' AND it.$typeSQL=1 AND DATE(ws.startTime)='%s'",
                    mysqli_real_escape_string($db->mysqli, $day)));
            while($row = $result->fetch_assoc()) {
                array_push($all['firstname'], $row['firstname']);
                array_push($all['surname'], $row['surname']);
                array_push($all['login'], $row['login']);
            }
        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function bookLesson() {
        $_SESSION['goToUserPage'] = 'lesson';
        $day = filter_input(INPUT_POST, "inputDay");
        $startHour = filter_input(INPUT_POST, "startLessonHour");
        $numberOfHours = filter_input(INPUT_POST, "numberOfHours");
        $numberOfParticipants = filter_input(INPUT_POST, "numberOfParticipants");
        $type = filter_input(INPUT_POST, "lessonSport");
        $instructor = filter_input(INPUT_POST, "instructorSelect");
        
        $loginFirstChar = strpos($instructor, "(");
        $instructorLogin = substr($instructor, $loginFirstChar+1, -1);

        if(UserValidation::onlyDayValidation($day) && UserValidation::onlyHourValidation($startHour) && UserValidation::lessonHoursValidation($numberOfHours) && UserValidation::lessonTypeValidation($type) && UserValidation::instructorValidation($instructorLogin, $type, $day) && UserValidation::lessonParticipantsValidation($numberOfParticipants)) {
            $startTime = date($day." ".$startHour);
            $startTimeUnix = strtotime($startTime);
            $finishTimeUnix = $startTimeUnix + $numberOfHours*3600;
            $startDate = date("Y-m-d H:i", $startTimeUnix);
            $finishDate = date("Y-m-d H:i", $finishTimeUnix);
            
            if(UserValidation::isLessonBeetwenInstructorTimetable($startDate, $finishDate, $instructorLogin) && UserValidation::isLessonDatetimeAvailable($startDate, $finishDate, $instructorLogin) && UserValidation::isLessonDatetimeFuture($startDate)) {
                $this->insertNewLesson($instructorLogin, $startDate, $finishDate, $numberOfHours, $numberOfParticipants, $type);
            }
        }
        header("Location: userPage.php");
    }
    
    public function insertNewLesson($instructorLogin, $startDate, $finishDate, $numberOfHours, $numberOfParticipants, $type) {
        $lessonCost = $this->getLessonCost($numberOfHours, $numberOfParticipants, false);
        $instructorID = $this->getInstructorID($instructorLogin);
        $userID = $_SESSION['id'];
        $db = new Database();
        try {
            $db->mysqli->query(
                    sprintf("INSERT INTO lesson VALUES(NULL, $userID, $instructorID, '%s', '%s', %s, 0, '%s', %s)",
                    mysqli_real_escape_string($db->mysqli, $startDate),
                    mysqli_real_escape_string($db->mysqli, $finishDate),
                    mysqli_real_escape_string($db->mysqli, $numberOfParticipants),
                    mysqli_real_escape_string($db->mysqli, $type),
                    mysqli_real_escape_string($db->mysqli, $lessonCost)));
            $_SESSION['bookLesson'] = true;
        } catch (Exception $ex) {
            $_SESSION['bookLesson'] = false;
        }
    }
    
    public function getInstructorID($login) {
        $db = new Database();
        try {
            $result = $db->mysqli->query(
                    sprintf("SELECT idAccount FROM account WHERE login='%s'",
                    mysqli_real_escape_string($db->mysqli, $login)));
            $row = $result->fetch_assoc();
            return $row['idAccount'];
        } catch (Exception $ex) {
            return null;
        }
    }
    
    public function getUserLessons() {
        $userID = $_SESSION['id'];
        $db = new Database();
        $all['idLesson'] = [];
        $all['instructor'] = [];
        $all['startDate'] = [];
        $all['finishDate'] = [];
        $all['numberOfParticipants'] = [];
        $all['type'] = [];
        $all['cost'] = [];
        $all['isCancelled'] = [];
        try {
            $result = $db->mysqli->query(
                    sprintf("SELECT idLesson, login, firstname, surname, startDate, finishDate, numberOfParticipants, type, cost, isCancelled FROM lesson les INNER JOIN account a ON les.idInstructor=a.idAccount INNER JOIN personaldata pd ON pd.idAccount=a.idAccount WHERE les.idUser=%s ORDER BY startDate DESC",
                    mysqli_real_escape_string($db->mysqli, $userID)));
            while($row = $result->fetch_assoc()) {
                array_push($all['idLesson'], $row['idLesson']);
                array_push($all['instructor'], $row['firstname']." ".$row['surname']." (".$row['login'].")");
                array_push($all['startDate'], $row['startDate']);
                array_push($all['finishDate'], $row['finishDate']);
                array_push($all['numberOfParticipants'], $row['numberOfParticipants']);
                array_push($all['cost'], $row['cost']);
                array_push($all['type'], $row['type']);
                array_push($all['isCancelled'], $row['isCancelled']);
            }

        } catch (Exception $ex) { 
        }
        echo json_encode($all);
    }
    
    public function cancelLesson() {
        $_SESSION['goToUserPage'] = 'lesson';
        $lessonID = filter_input(INPUT_POST, "idLesson");
        $userID = $_SESSION['id'];
        if(UserValidation::isLessonToCancel($lessonID, $userID)) {
            $db = new Database();
            try {
                $db->mysqli->query(
                        sprintf("UPDATE lesson SET isCancelled=1, cost=0 WHERE idLesson=%s",
                        mysqli_real_escape_string($db->mysqli, $lessonID)));
                $_SESSION['cancelLesson'] = true;
            } catch (Exception $ex) {
            }
        }
        else {
            $_SESSION['cancelLesson'] = false;
        }
        header("Location: userPage.php");
    }
    

}