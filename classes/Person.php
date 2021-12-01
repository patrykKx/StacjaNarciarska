<?php
require_once 'Database.php';
require_once 'Validation.php';

class Person {  
    public function checkSessionStatus() {
        if(isset($_SESSION['id'])) {
            echo true;
        }
        else {
            echo false;
        }
    }
    
    public static function goToPage($role) {
        switch($role) {
            case 'Admin': header('Location: adminPage.php'); break;
            case 'Użytkownik': header('Location: userPage.php'); break;
            case "Instruktor" : header('Location: instructorPage.php'); break;
            case "Serwisant" : header('Location: servicemenPage.php'); break;
            case "Techniczny" : header('Location: technicianPage.php'); break;
            default : header('Location: index.php'); break;
        }
    }
     
    public function getTariff() {
        $page = filter_input(INPUT_POST, 'page');
        $db = new Database();
        try {
            $result = $db->mysqli->query('SELECT * FROM tariff ORDER BY idTariff DESC LIMIT 1');
            $row = $result->fetch_assoc();       
            if($page == "mainPage") {
                echo json_encode($row);
            }
            else {
                $_SESSION['tariffValues'] = $row; 
            }
        } catch (Exception $ex) {

        }
    }
    
    public function getConditions() {
        $db = new Database();
        try {
            $result = $db->mysqli->query('SELECT * FROM conditions ORDER BY idConditions DESC LIMIT 1');
            $row = $result->fetch_assoc();
            echo json_encode($row);
        } catch (Exception $ex) {

        }
    }
    
    public static function putAccountDataToSession() {
        $db = new Database();
        try {
            $result = $db->mysqli->query('SELECT login, role, creationDate FROM account WHERE idAccount = '.$_SESSION["id"]);
            $result2 = $db->mysqli->query('SELECT firstname, surname, email FROM personaldata WHERE idAccount = '.$_SESSION["id"]);
            $row = $result->fetch_assoc();
            $row2 = $result2->fetch_assoc();
            if($row != NULL && $row2 != NULL) {
                $_SESSION['login'] = $row['login'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['creationDate'] = $row['creationDate'];
                $_SESSION['firstname'] = $row2['firstname'];
                $_SESSION['surname'] = $row2['surname'];
                $_SESSION['email'] = $row2['email'];
            }
            else {
                $_SESSION['getAccountDataError'] = true;
            }
        } catch (Exception $ex) {
            $_SESSION['getAccountDataError'] = false;
        }
    }
    
    public function editPassword() {
        $oldPass = filter_input(INPUT_POST, 'oldPass');
        $newPass = filter_input(INPUT_POST, 'newPass');
        $newPass2 = filter_input(INPUT_POST, 'newPass2');
        $all = [];
        $OK = true;
        if(!Validation::oldPasswordValidation($oldPass)) {
            $all['oldPassValidation'] = 'Podałeś złe stare hasło!';
            $OK = false;
        }
        if(!Validation::passwordValidation($newPass, $newPass2) && isset($_SESSION['passwordValidation'])) {
            $all['newPassValidation'] = $_SESSION['passwordValidation'];
            $OK = false;
        }
        if($OK) {
            $newPassHash = password_hash($newPass, PASSWORD_DEFAULT);
            $db = new Database();
            try {
                $db->mysqli->query(
                sprintf('UPDATE account SET password="%s" WHERE idAccount = '.$_SESSION["id"], 
                mysqli_real_escape_string($db->mysqli, $newPassHash)));  
                $_SESSION['editPassword'] = true;
            } catch (Exception $ex) {
                $_SESSION['editPassword'] = false;
            }
        }
        echo json_encode($all);
    }
    
    public function editEmail() {
        $newEmail = filter_input(INPUT_POST, 'newEmail');
        $OK = true;
        $emailError = '';
        if(!Validation::emailValidation($newEmail) && isset($_SESSION['emailValidation'])) {
            $emailError = $_SESSION['emailValidation'];
            $OK = false;
        }
        
        if($OK) {
            $db = new Database();
            $db->mysqli->query(
            sprintf('UPDATE personaldata SET email="%s" WHERE idAccount = '.$_SESSION["id"], 
            mysqli_real_escape_string($db->mysqli, $newEmail)));  
            $_SESSION['editEmail'] = true;
            $_SESSION['email'] = $newEmail;
        }
        else {
            $_SESSION['editEmail'] = false;
        }
        
        echo $emailError;
    }
    
    public function getCalendar() {
        $db = new Database();
        $year = filter_input(INPUT_POST, 'year');
        $month = filter_input(INPUT_POST, 'month');
        $all = [];
        try {
            $result = $db->mysqli->query(
                sprintf('SELECT date FROM openingschedule WHERE EXTRACT(Year FROM date)=%s AND Month(date)=%s AND isOpen=1',
                mysqli_real_escape_string($db->mysqli, $year),
                mysqli_real_escape_string($db->mysqli, $month)));  
            if($result == NULL) {
                json_encode($all);
                exit();
            }
            while($row = $result->fetch_assoc()) {
                array_push($all, $row['date']);  
            }
        } 
        catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function getAdvancedCalendar() {
        $db = new Database();
        $date = filter_input(INPUT_POST, 'day');
        $all = [];
        try {
            $result = $db->mysqli->query(
                sprintf("SELECT * FROM openingschedule WHERE date='%s'", 
                mysqli_real_escape_string($db->mysqli, $date)));
            if($result != NULL) {
                $row = $result->fetch_assoc();
                $all['date'] = $row['date']; 
                $all['isOpen'] = $row['isOpen'];
                $all['startTime'] = $row['startTime'];
                $all['finishTime'] = $row['finishTime'];
            }
        } 
        catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function getWorkerDays() {
        $db = new Database();
        $year = filter_input(INPUT_POST, "year");
        $month = filter_input(INPUT_POST, "month");
        $idWorker = $_SESSION['id'];
        $all['startTime'] = [];
        try {
            $result = $db->mysqli->query(
                sprintf("SELECT startTime FROM workschedule WHERE idUser=%s AND YEAR(startTime)=%s AND MONTH(startTime)=%s", 
                mysqli_real_escape_string($db->mysqli, $idWorker),
                mysqli_real_escape_string($db->mysqli, $year),
                mysqli_real_escape_string($db->mysqli, $month)));
            while($row = $result->fetch_assoc()) {
                array_push($all['startTime'], $row['startTime']);
            }
        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function getWorkerDayHighlights() {
        $db = new Database();
        $date = filter_input(INPUT_POST, "date");
        $idWorker = $_SESSION['id'];
        $all['startTime'] = [];
        $all['finishTime'] = [];
        try {
            $result = $db->mysqli->query(
                sprintf("SELECT startTime, finishTime FROM workschedule WHERE idUser=%s AND DATE(startTime)='%s'", 
                mysqli_real_escape_string($db->mysqli, $idWorker),
                mysqli_real_escape_string($db->mysqli, $date)));
            while($row = $result->fetch_assoc()) {
                array_push($all['startTime'], $row['startTime']);
                array_push($all['finishTime'], $row['finishTime']);
            }
        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function getPersonListToChat() {
        $role = filter_input(INPUT_POST, "personType");
        $idMyself = $_SESSION['id'];
        $db = new Database();
        $all['firstname'] = [];
        $all['surname'] = [];
        $all['login'] = [];
        $sql = "";
        if($role == "Użytkownik") {
            $sql = sprintf("SELECT login FROM account WHERE deletedDate IS NULL AND role='%s' AND idAccount<>$idMyself",
               mysqli_real_escape_string($db->mysqli, $role));     
        }
        else {
            $sql = sprintf("SELECT login, firstname, surname FROM account a INNER JOIN personaldata pd ON a.idAccount=pd.idAccount WHERE deletedDate IS NULL AND role='%s' AND a.idAccount<>$idMyself",
               mysqli_real_escape_string($db->mysqli, $role));     
        }
        try {
            $result = $db->mysqli->query($sql);
            while($row = $result->fetch_assoc()) {
                array_push($all['login'], $row['login']);
                if($role != "Użytkownik") {
                    array_push($all['firstname'], $row['firstname']);
                    array_push($all['surname'], $row['surname']);
                }
            }
        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function getMessages() {
        $myselfID = $_SESSION['id'];
        $who = filter_input(INPUT_POST, "who");
        $all['amIsender'] = [];
        $all['date'] = [];
        $all['content'] = [];
        $db = new Database();
        //Start transaction
        $db->mysqli->begin_transaction();
        try {
            $result = $result = $this->getLoginFromID($who);
            if($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $whoID = $row['idAccount'];
                $this->updateMessageReadStatus($myselfID, $whoID);
                $result3 = $db->mysqli->query(
                    sprintf("SELECT date, content, idUserSender FROM message WHERE idUserSender IN ($myselfID, %s) AND idUserReceiver IN ($myselfID, %s) ORDER BY date", 
                        mysqli_real_escape_string($db->mysqli, $whoID),
                        mysqli_real_escape_string($db->mysqli, $whoID)));
                while($row3 = $result3->fetch_assoc()) {
                    if($row3['idUserSender'] == $myselfID) {
                        array_push($all['amIsender'], true);
                    }
                    else {
                        array_push($all['amIsender'], false);
                    }
                    array_push($all['date'], $row3['date']);
                    array_push($all['content'], $row3['content']);
                }
            }
            $db->mysqli->commit();
        } catch (Exception $ex) {
            $db->mysqli->rollback();
        }
        echo json_encode($all);
    }
    
    public function saveMessage() {
        $myself = $_SESSION['id'];
        $receiver = filter_input(INPUT_POST, "receiver");
        $content = filter_input(INPUT_POST, "content");
        $now = date("Y-m-d H:i:s");
        $db = new Database();
        $db->mysqli->begin_transaction();
        try {
            $result = $result = $this->getLoginFromID($receiver);
            if($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $receiverID = $row['idAccount'];
                $db->mysqli->query(
                    sprintf("INSERT INTO message VALUES(NULL, $myself, $receiverID, '$now', '%s', 0)", 
                        mysqli_real_escape_string($db->mysqli, $content)));
                $db->mysqli->commit();
                echo true;
            }
        } catch (Exception $ex) {
            $db->mysqli->rollback();
            echo false;
        }
        echo false;
    }
    
    public function getRecentlyMessagePeople() {
        $db = new Database();
        $myselfID = $_SESSION['id'];
        $myselfLogin = $_SESSION['login'];
        $all['login'] = [];
        $all['unreadMessagesFromPerson'] = [];
        try {
            $result = $db->mysqli->query("SELECT a.login AS sender, a2.login AS receiver, m.isRead FROM message m INNER JOIN account a ON m.idUserSender=a.idAccount INNER JOIN account a2 ON m.idUserReceiver=a2.idAccount WHERE m.idUserSender=$myselfID OR m.idUserReceiver=$myselfID ORDER BY m.date DESC");
            while($row = $result->fetch_assoc()) {
                if($row["sender"] != $myselfLogin && !in_array($row["sender"], $all['login'])) {
                    array_push($all['login'], $row["sender"]);
                }
                else if($row["receiver"] != $myselfLogin && !in_array($row["receiver"], $all['login'])) {
                    array_push($all['login'], $row["receiver"]);
                }
            }
            $all['unreadMessagesFromPerson'] = $this->checkUnreadPeople($all['login'], $myselfID);
        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function refreshRecentlyMessagePeople() {
        $db = new Database();
        $myselfID = $_SESSION['id'];
        $all['login'] = [];
        try {
            $result = $db->mysqli->query("SELECT DISTINCT a2.login FROM message m INNER JOIN account a ON m.idUserReceiver=a.idAccount INNER JOIN account a2 ON m.idUserSender=a2.idAccount WHERE a.idAccount=$myselfID AND m.isRead=0");
            while($row = $result->fetch_assoc()) {
                array_push($all['login'], $row["login"]);
            }
        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function getUnreadMessages() {
        $sender = filter_input(INPUT_POST, "sender");
        $myselfID = $_SESSION['id'];
        $all['content'] = [];
        $all['date'] = [];
        $db = new Database();
        $db->mysqli->begin_transaction();
        try {
            $result = $this->getLoginFromID($sender);
            if($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $senderID = $row['idAccount'];
                $result2 = $db->mysqli->query("SELECT date, content FROM message WHERE isRead=false AND idUserSender=$senderID AND idUserReceiver=$myselfID");
                while($row2 = $result2->fetch_assoc()) {
                    array_push($all['date'], $row2["date"]);
                    array_push($all['content'], $row2["content"]);
                }
                $this->updateMessageReadStatus($myselfID, $senderID);
            }
            $db->mysqli->commit();
        } catch (Exception $ex) {
            $db->mysqli->rollback();
        }
        echo json_encode($all);
    }
    
    public function getLoginFromID($login) {
        $db = new Database();
        $result = $db->mysqli->query(
            sprintf("SELECT idAccount FROM account WHERE login='%s'", 
            mysqli_real_escape_string($db->mysqli, $login)));
        return $result;
    }
    
    public function updateMessageReadStatus($myselfID, $whoID) {
        $db = new Database();
        $db->mysqli->query("UPDATE message SET isRead=1 WHERE idUserReceiver=$myselfID AND idUserSender=$whoID AND isRead=0");
    }
    
    public function checkUnreadPeople($logins, $myselfID) {
        $db = new Database();
        $unreadMessages = [];
        try {
            foreach($logins as $login) {
                $result = $db->mysqli->query("SELECT isRead FROM message m INNER JOIN account a ON m.idUserSender=a.idAccount INNER JOIN account a2 ON m.idUserReceiver=a2.idAccount WHERE a.login='$login' AND a2.idAccount=$myselfID AND m.isRead=0");
                if($result->num_rows > 0) {
                    array_push($unreadMessages, true);
                }
                else {
                    array_push($unreadMessages, false);
                }
            }
        } catch (Exception $ex) {

        }
        return $unreadMessages;
    }
    
    public function countUnreadMessagesFromPeople() {
        $myselfID = $_SESSION['id'];
        $counter = 0;
        $db = new Database();
        try {
            $result = $db->mysqli->query("SELECT COUNT(DISTINCT idUserSender) AS counter FROM message WHERE isRead=0 AND idUserReceiver=$myselfID");
            if($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $counter = $row['counter'];
            }
        } catch (Exception $ex) {
            echo $counter;
        }
        echo $counter;
    }
}
