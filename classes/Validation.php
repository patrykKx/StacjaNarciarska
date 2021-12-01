<?php
require_once 'Database.php';

class Validation{
    public static function firstnameValidation($firstname) {
        if(strlen($firstname) < 3 || strlen($firstname) > 30) {
            $_SESSION['firstnameValidation'] = 'Proszę podać imię, które zawieraa od 3 do 30 liter!';
            $_SESSION['addWorkerValidation'] = $_SESSION['firstnameValidation'];
            return false;
        }
        if(!preg_match('/^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ]*$/', $firstname)) {
            $_SESSION['firstnameValidation'] = 'Proszę podać imię, które zawiera wyłącznie litery!';
            $_SESSION['addWorkerValidation'] = $_SESSION['firstnameValidation'];
            return false;
        }
        if(preg_match('/^[^A-ZŁ]*$/', $firstname)) { 
            $_SESSION['firstnameValidation'] = 'Proszę podać imię, które zaczyna się od wielkiej litery!';
            $_SESSION['addWorkerValidation'] = $_SESSION['firstnameValidation'];
            return false;
        } 
        return true;     
    }
    public static function surnameValidation($surname) {
        if(strlen($surname) < 3 || strlen($surname) > 30) {
            $_SESSION['surnameValidation'] = 'Proszę podać nazwisko, które zawiera od 3 do 30 liter!';
            $_SESSION['addWorkerValidation'] = $_SESSION['surnameValidation'];
            return false;
        }
        if(!preg_match('/^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ-]*$/', $surname)) {
            $_SESSION['surnameValidation'] = 'Proszę podać nazwisko, które zawiera wyłącznie litery!';
            $_SESSION['addWorkerValidation'] = $_SESSION['surnameValidation'];
            return false;
        }
        /*if(preg_match('/*$^[ąćęłńóśźżĄĆĘŁŃÓŚŹŻ]*$/', $surname) && strlen($surname) < 5) { //polskie znaki są kodowane podwójnie
            $_SESSION['surnameValidation'] = 'Nazwisko musi zawierać od 3 do 30 liter!';
            return false;
        }*/
        if(preg_match('/^[^A-ZŁ]+$/', $surname)) { 
            $_SESSION['surnameValidation'] = 'Proszę podać nazwisko, które zaczyna się od wielkiej litery!';
            $_SESSION['addWorkerValidation'] = $_SESSION['surnameValidation'];
            return false;
        }
        return true;     
    }
    
    public static function birthdateValidation($birthdate) {
        $birthdateUNIX = strtotime($birthdate);
        if($birthdate == null) {
            $_SESSION['birthdateValidation'] = 'Proszę podać datę!';
            $_SESSION['addWorkerValidation'] = $_SESSION['birthdateValidation'];
            return false;
        }
        else if($birthdateUNIX == '' || $birthdateUNIX == null) {
            $_SESSION['birthdateValidation'] = 'Proszę podać poprawnie datę urodzenia!';
            $_SESSION['addWorkerValidation'] = $_SESSION['birthdateValidation'];
            return false;            
        }
        else {
            $date = getdate();
            $day = $date['mday']; //obecny dzień
            $month = $date['mon']; //obecny miesiąc
            $year = $date['year']; //obecny rok

            $birthdate_arr  = explode('-', $birthdate);
            
            /*echo $birthdate_arr[2]." "; //dzień
            echo $birthdate_arr[1]." "; //miesiąc
            echo $birthdate_arr[0]." "; //rok*/
  
            //Sprawdzenie limitu wieku (16 lat)
            if(((int)$year - (int)$birthdate_arr[0] > 16 || (int)$year - (int)$birthdate_arr[0] < 0) || 
               ((int)$year - (int)$birthdate_arr[0] == 16 && (int)$month - (int)$birthdate_arr[1] > 0) ||
               ((int)$year - (int)$birthdate_arr[0] == 16 && (int)$month - (int)$birthdate_arr[1] == 0 && (int)$day - (int)$birthdate_arr[2] >= 0)) {
            }
            else {
                $_SESSION['birthdateValidation'] = 'Użytkownik musi mieć co najmniej 16 lat!';
                $_SESSION['addWorkerValidation'] = $_SESSION['birthdateValidation'];
                return false;
            }
            if((int)$year - (int)$birthdate_arr[0] > 100 || (int)$birthdate_arr[0] > (int)$year) { //Poprawnosc daty(od 1921 do 2021)
                $_SESSION['birthdateValidation'] = 'Proszę podać poprawną datę urodzenia!';
                $_SESSION['addWorkerValidation'] = $_SESSION['birthdateValidation'];
                return false;
            }
        }
        return true;
    }
    
    public static function emailValidation($email) {
        $email2 = filter_var($email, FILTER_SANITIZE_EMAIL); //usuniecie niedozwolonych znakow w emailu
        if(filter_var($email2, FILTER_VALIDATE_EMAIL) == false || $email2 != $email) {
            $_SESSION['emailValidation'] = 'Proszę podać poprawny adres e-mail!';
            $_SESSION['addWorkerValidation'] = $_SESSION['emailValidation'];
            return false;
        }       
        if(self::getEmailsNumber($email) > 0) {
            $_SESSION['emailValidation'] = 'Podany adres e-mail jest już używany!';
            $_SESSION['addWorkerValidation'] = $_SESSION['emailValidation'];
            return false;
        }
        return true;
    }  
    
    public static function getEmailsNumber($email) {
        $db = new Database();
        $result = $db->mysqli->query(
                sprintf("SELECT idAccount FROM personaldata WHERE email='%s'", 
                mysqli_real_escape_string($db->mysqli, $email))); //Czy email jest w bazie
        $foundedEmailsNumber = $result->num_rows;
        return $foundedEmailsNumber;
    }
    
    public static function loginValidation($login) {
        if(strlen($login)<3 || strlen($login)>25) {
            $_SESSION['loginValidation'] = 'Proszę podać login, który ma od 3 do 25 znaków!';
            $_SESSION['addWorkerValidation'] = $_SESSION['loginValidation'];
            return false;
        }
        if(ctype_alnum($login) == false) {//czy login sklada sie tylko z znakow alfanumerycznych {
            $_SESSION['loginValidation'] = 'Proszę podać login, który ma wyłącznie litery(bez polskich znaków) i cyfry!';
            $_SESSION['addWorkerValidation'] = $_SESSION['loginValidation'];
            return false;
        }
        if(!preg_match('/[A-Za-z]{1,}/', $login)) {
            $_SESSION['loginValidation'] = 'Proszę podać login, który zawiera co najmniej jedną literę!';
            $_SESSION['addWorkerValidation'] = $_SESSION['loginValidation'];
            return false;
        }
        if(preg_match('/^[^A-Za-z]+$/', $login)) {
            $_SESSION['loginValidation'] = 'Proszę podać login, który zaczynaa się literą!';
            $_SESSION['addWorkerValidation'] = $_SESSION['loginValidation'];
            return false;
        }
        if(self::getLoginsNumber($login) > 0) {
            $_SESSION['loginValidation'] = 'Podany login jest już używany!';
            $_SESSION['addWorkerValidation'] = $_SESSION['loginValidation'];
            return false;
        }
        return true;
    }
    
    public static function getLoginsNumber($login) {
        $db = new Database();
        $result = $db->mysqli->query(
                sprintf("SELECT idAccount FROM account WHERE login='%s'",
                mysqli_real_escape_string($db->mysqli, $login))); //Czy login jest w bazie 
        $foundedLoginsNumber = $result->num_rows;
        return $foundedLoginsNumber;
    }
    
    public static function passwordValidation($pass, $pass2) {
        if(strlen($pass)<8 || strlen($pass2)>25){
            $_SESSION['passwordValidation'] = 'Proszę podać hasło, które ma od 8 do 25 znaków!';
            $_SESSION['addWorkerValidation'] = $_SESSION['passwordValidation'];
            return false;
        }
        if(preg_match('/[ąęćżźńłó ]{1,}/', $pass)) {
            $_SESSION['passwordValidation'] = 'Proszę podać hasło, które nie zawierać polskich znaków ani spacji!';
            $_SESSION['addWorkerValidation'] = $_SESSION['passwordValidation'];
            return false;
        }
        if(!preg_match('/[a-z]{1,}/', $pass) || !preg_match('/[A-Z]{1,}/', $pass) || !preg_match('/[0-9]{1,}/', $pass)) {
            $_SESSION['passwordValidation'] = 'Proszę podać hasło, które zawieraa co najmniej jedną wielką literę, jedną małą literę oraz jedną cyfrę!';
            $_SESSION['addWorkerValidation'] = $_SESSION['passwordValidation'];
            return false;
        }
        if($pass != $pass2) {
            $_SESSION['passwordValidation'] = 'Proszę podać identyczne hasła!';
            $_SESSION['addWorkerValidation'] = $_SESSION['passwordValidation'];
            return false;
        }
        return true;
    }
    
    public static function instructorValidation($role, $checkboxSkiing, $checkboxSnowboard) {
        if($role == "Instruktor") {
            if($checkboxSkiing != 'ski' && $checkboxSnowboard != 'snowboard') {
                $_SESSION['addWorkerValidation'] = "Proszę zaznaczyć specjalizację instruktora!";
                return false;
            }
        }
        return true;
    }
    
    public static function roleValidation($role) {
        if($role != 'Admin' && $role != 'Instruktor' && $role != 'Techniczny' && $role != 'Serwisant') {
            $_SESSION['roleValidation'] = 'Proszę wybrać nazwę stanowiska z listy!';
            $_SESSION['addWorkerValidation'] = $_SESSION['roleValidation'];
            return false;
        }
        return true;
    }
    
    public static function tariffValidation($value) {
        if(!is_numeric($value)) {
            $_SESSION['tariffValidation'] = 'Proszę podać wyłącznie cyfry!';
            return false;
        }
        return true;
    }
    
    public static function conditionsValidation($min, $max, $conditions, $snowType) {
        if($min < 0 || $max < 0) {
            $_SESSION['conditionsValidation'] = 'Proszę wypełnić poprawnie pola!';
            return false;
        }
        if($conditions != 'Znakomite' && $conditions != 'Bardzo dobre' && $conditions != 'Dobre' && $conditions != 'Dostateczne' && $conditions != 'Brak') {
            $_SESSION['conditionsValidation'] = 'Proszę wypełnić poprawnie pola!';
            return false;
        }
        if($snowType != 'Świeży puch' && $snowType != 'Przewiany gips' && $snowType != 'Zbity gips' && $snowType != 'Lodoszreń' && $snowType != 'Mokry' && $snowType != 'Ziarnisty' &&$snowType != 'Sztuczny' && $snowType != 'Mieszany' && $snowType != 'Brak') {
            $_SESSION['conditionsValidation'] = 'Proszę wypełnić poprawnie pola!';
            return false;
        }
        return true;
    }
    
    public static function equipmentTypeValidation($type) {
        if($type != 'Narty' && $type != 'Snowboard' && $type != 'Buty narciarskie' && $type != 'Buty snowboardowe' && $type != 'Kask') {
            $_SESSION['equipmentValidation'] = 'Proszę podać poprawny typ sprzętu!';
            return false;
        }
        return true;
    }
    
    public static function equipmentIdValidation($ID, $type) {
        if(!self::equipmentIdCodeValidation($ID, $type)) {
            return false;
        }
        if(self::getIDsNumber($ID) > 0) {
            $_SESSION['equipmentValidation'] = 'Podany identyfikator sprzętu jest już w bazie!';
            return false; 
        }
        return true;
    }
    
    public static function equipmentIdCodeValidation($ID, $type) {
        if($ID == '' || strlen($ID) < 2) {
            $_SESSION['equipmentValidation'] = 'Proszę podać poprawnie ID!';
            return false; 
        }
        else if($type == 'Narty') {
            if(substr($ID, 0, 1) != 'N') {
                $_SESSION['equipmentValidation'] = 'Dla nart, proszę podać przedrostek N!';
                return false; 
            }
            else if(preg_match('/^[0]*$/', $ID[1])) {
                $_SESSION['equipmentValidation'] = 'Proszę podać część liczbową ID, która nie zaczyna się od 0';
                return false;
            }
            else if(!preg_match('/^[0-9]*$/', substr($ID, 1))) {
                $_SESSION['equipmentValidation'] = 'Proszę podać ID, które składa się z literowego przedrostka i numeru!';
                return false;
            }
        }
        else if($type == 'Snowboard') {
            if(substr($ID, 0, 1) != 'S') {
                $_SESSION['equipmentValidation'] = 'Dla snowboardu, proszę podać przedrostek musi być S!';
                return false; 
            }
            else if(preg_match('/^[0]*$/', $ID[1])) {
                $_SESSION['equipmentValidation'] = 'Proszę podać część liczbową ID, która nie zaczyna się od 0';
                return false;
            }
            else if(!preg_match('/^[0-9]*$/', substr($ID, 1))) {
                $_SESSION['equipmentValidation'] = 'Proszę podać ID, które składa się z literowego przedrostka i numeru!';
                return false;
            }
        }
        else if($type == 'Buty narciarskie') {
            if(substr($ID, 0, 2) != 'BN') {
                $_SESSION['equipmentValidation'] = 'Dla butów narciarskich, proszę podać przedrostek BN!';
                return false; 
            }
            else if(preg_match('/^[0]*$/', $ID[2])) {
                $_SESSION['equipmentValidation'] = 'Proszę podać część liczbową ID, która nie zaczyna się od 0';
                return false;
            }
            else if(!preg_match('/^[0-9]*$/', substr($ID, 2))) {
                $_SESSION['equipmentValidation'] = 'Proszę podać ID, które składa się z literowego przedrostka i numeru!';
                return false;
            }
        }
        else if($type == 'Buty snowboardowe') {
            if(substr($ID, 0, 2) != 'BS') {
                $_SESSION['equipmentValidation'] = 'Dla butów snowboardowych, proszę podać przedrostek BS!';
                return false; 
            }
            else if(preg_match('/^[0]*$/', $ID[2])) {
                $_SESSION['equipmentValidation'] = 'Proszę podać część liczbową ID, która nie zaczyna się od 0';
                return false;
            }
            else if(!preg_match('/^[0-9]*$/', substr($ID, 2))) {
                $_SESSION['equipmentValidation'] = 'Proszę podać ID, które składa się z literowego przedrostka i numeru!';
                return false;
            }
        }
        else if($type == 'Kask') {
            if(substr($ID, 0, 1) != 'K') {
                $_SESSION['equipmentValidation'] = 'Dla kasków, proszę podać przedrostek BN!';
                return false; 
            }
            else if(preg_match('/^[0]*$/', $ID[1])) {
                $_SESSION['equipmentValidation'] = 'Proszę podać część liczbową ID, która zaczynaa się od 0';
                return false;
            }
            else if(!preg_match('/^[0-9]*$/', substr($ID, 1))) {
                $_SESSION['equipmentValidation'] = 'Proszę podać ID, które składa się z literowego przedrostka i numeru!';
                return false;
            }
        }
        return true;
    }
    
    public static function equipmentNameValidation($name) {
        if(strlen($name) < 3) {
            $_SESSION['equipmentValidation'] = 'Proszę podać poprawną nazwę sprzętu!';
            return false;  
        }
        return true;
    }
    
    public static function equipmentSizeValidation($size) {
        if($size == "" || is_null($size)) {
            $_SESSION['equipmentValidation'] = 'Proszę podać poprawny rozmiar!';
            return false; 
        }
        else if($size < 1) {
            $_SESSION['equipmentValidation'] = 'Proszę podać rozmiar, który będzie liczbą dodatnią!';
            return false; 
        }
        else if(!preg_match('/^[0-9]*$/', $size)) {
            $_SESSION['equipmentValidation'] = 'Proszę podać rozmiar, który składa się wyłącznie z cyfr!';
            return false; 
        }
        return true;
    }
    
    public static function equipmentURLValidation($url) {
        return true;
    }
    
    public static function getIDsNumber($ID) {
        $db = new Database();
        $editEquipmentID = -1;
        if(isset($_SESSION["oldEquipmentId"])) {
            $editEquipmentID = $_SESSION["oldEquipmentId"];
        }
        $result = $db->mysqli->query(
                sprintf("SELECT idEquipment FROM equipment WHERE idEquipment='%s' AND idEquipment<>'%s'",
                mysqli_real_escape_string($db->mysqli, $ID),
                mysqli_real_escape_string($db->mysqli, $editEquipmentID))); 
        $foundedIDsNumber = $result->num_rows;
        return $foundedIDsNumber;
    }
    
    public static function oldPasswordValidation($oldPass) {
        $db = new Database();
        try {
            $result = $db->mysqli->query(
                sprintf("SELECT password FROM account WHERE login='%s'",
                mysqli_real_escape_string($db->mysqli, $_SESSION['login']))); 
            $row = $result->fetch_assoc();
            if(password_verify($oldPass, $row['password'])) {
               return true;
            }
            else {
               return false;
            }
        } catch (Exception $ex) {
            return false;
        }
    }
    
    public static function dayValidation($day, $isOpen, $startTime, $finishTime) {
        if($day == '' || $isOpen == '') {
            $_SESSION['dayValidation'] = 'Proszę wypełnij pola!';
            return false;
        }
        $datetimeUNIX = strtotime(date($day));
        $nowUNIX = strtotime(date("Y-m-d"));
        if($datetimeUNIX < $nowUNIX) {
            $_SESSION['dayValidation'] = 'Nie możesz edytować dat z przeszłości!';
            return false;
        }
        if($isOpen == 1 && ($startTime == '' || $finishTime == '')) {
            $_SESSION['dayValidation'] = 'Proszę wypełnij poprawnie godziny otwarcia!';
            return false;
        }
        if($isOpen == 1 && ($startTime >= $finishTime)) {
            $_SESSION['dayValidation'] = 'Proszę wypełnij poprawnie godziny otwarcia!';
            return false;
        }
        return true;
    }
    
    public static function isDayInDatabase($day) {
        $db = new Database();
        try {
            $result = $db->mysqli->query(
                sprintf("SELECT date FROM openingschedule WHERE date='%s'",
                mysqli_real_escape_string($db->mysqli, $day))); 
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
    
    public static function timetableValidation($day, $startHour, $finishHour, $login) {
        if($day == '' || $startHour == '' || $finishHour == '' || $login == '') {
            $_SESSION['timetableValidation'] = 'Proszę wypełnij pola!';
            return false;
        }
        if($startHour >= $finishHour) {
            $_SESSION['timetableValidation'] = 'Proszę wypełnij poprawnie godziny otwarcia!';
            return false;
        }
        return true;
    }
    
    public static function isLessonToCancelByInstructor($instructorID, $lessonID) {
        $db = new Database();
        try {
            $result = $db->mysqli->query(
                sprintf("SELECT startDate FROM lesson WHERE idLesson=%s AND idInstructor=%s",
                mysqli_real_escape_string($db->mysqli, $lessonID),
                mysqli_real_escape_string($db->mysqli, $instructorID)));
            if($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $nowUNIX = strtotime(date("Y-m-d H:i"));
                $datetime = new DateTime($row['startDate']);
                $datetime->modify('+1 day');
                $datetime->setTime(0, 0, 0); //hours=0, miunutes=0, seconds=0
                $tomorrowOfLesson = $datetime->format('Y-m-d H:i');
                $tomorrowOfLessonUNIX = strtotime($tomorrowOfLesson);
                if($nowUNIX >= $tomorrowOfLessonUNIX) { //Instruktor może odwołać lekcje do tego samego dnia włącznie co lekcja
                    return false;
                }
            }
            else {
                return false;
            }
        } catch (Exception $ex) {
            return false;
        }
        return true;
    }
    
    
}
