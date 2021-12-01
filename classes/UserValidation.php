<?php
require_once 'Database.php';

class UserValidation{
    public static function numberOfHoursValidation($numberOfHours) {
        if($numberOfHours == '' || !preg_match('/^[0-9]*$/', $numberOfHours)) {
            $_SESSION['numberOfHoursValidation'] = 'Proszę podaj poprawną liczbę godzin!';
            return false;
        }
        return true;
    }
    
    public static function startHourValidation($day, $startHour) {
        if($startHour == '' || $day == '') {
            $_SESSION['startHourValidation'] = 'Proszę wypełnij pola!';
            return false;
        }
        else if(strtotime($day." ".$startHour) == null) {
            $_SESSION['startHourValidation'] = 'Proszę wypełnij poprawnie pola!';
            return false;
        }
        return true;
    }
    
    public static function ticketDateValidation($day, $startHour, $finishHour) {
        $startHourUnix = strtotime($startHour);
        $finishHourUnix = strtotime($finishHour);
        if(!(self::isFutureDate($startHourUnix))) {
            $_SESSION['ticketDayValidation'] = 'Nie można zarezerwować karnetu w przeszłości!';
            return false;
        }
        else if(!(self::isDayOpened($day))) {
            $_SESSION['ticketDayValidation'] = 'W tym dniu stacja jest zamknięta!';
            return false;
        }
        else if(!(self::isDateBetweenOpening($day, $startHourUnix, $finishHourUnix))) {
            $_SESSION['ticketDayValidation'] = 'Proszę podać czas trwania karnetu zawierający się pomiędzy godzinami otwarcia!';
            return false;
        }

        return true;    
    }
    
    public static function isDayOpened($day) {
        $db = new Database();
        try {
            $result = $db->mysqli->query(
                sprintf("SELECT date FROM openingschedule WHERE date='%s' AND isOpen=1",
                mysqli_real_escape_string($db->mysqli, $day)));
            if($result->num_rows > 0) {
                return true;
            }
            else {
                return false;
            }
        } catch (Exception $ex) {
            return false;
        }
    }
    
    public static function isDateBetweenOpening($day, $startHourUnix, $finishHourUnix) {
        $db = new Database();
        try {
            $result = $db->mysqli->query(
                sprintf("SELECT date, startTime, finishTime FROM openingschedule WHERE date='%s' AND isOpen=1",
                mysqli_real_escape_string($db->mysqli, $day)));
            $row = $result->fetch_assoc();
            $openingStationUnix = strtotime($row['date']." ".$row['startTime']);
            $closingStationUnix = strtotime($row['date']." ".$row['finishTime']);
            if($startHourUnix >= $openingStationUnix && $finishHourUnix <= $closingStationUnix) {
                return true;
            }
            else {
                return false;
            }
        } catch (Exception $ex) {
            return false;
        }
    }
    
    public static function isFutureDate($startHourUnix) {
        $nowUnix = strtotime(date("Y-m-d H:i:s"));
        if($startHourUnix >= $nowUnix) {
            return true;
        }
        else {
            return false;
        }
    }
    
    public static function ticketCostValidation($cost) {
        if($cost == '' || $cost == null) {
            $_SESSION['buyTicket'] = false;
            return false;
        }
        return true;
    }
    
    public static function lessonHoursValidation($hours) {
        if($hours != 1 && $hours != 2 && $hours != 3) {
            $_SESSION["bookLessonValidation"] = "Proszę podać poprawnie liczbę godzin!";
            return false;
        }
        return true;
    }
    
    public static function lessonParticipantsValidation($participants) {
        if($participants != 1 && $participants != 2 && $participants != 3 && $participants != 4 && $participants != 5) {
            $_SESSION["bookLessonValidation"] = "Proszę podać poprawnie liczbę uczestników!";
            return false;
        }
        return true;
    }
    
    public static function onlyDayValidation($day) {
        $dayUnix = strtotime($day);
        if($dayUnix == "" || is_null($dayUnix)) {
            $_SESSION['bookLessonValidation'] = 'Proszę podać poprawnie dzień!';
            return false;
        }
        return true;
    }
    
    public static function onlyHourValidation($hour) {
        $hourUnix = strtotime($hour);
        if($hourUnix == "" || is_null($hourUnix)) {
            $_SESSION['bookLessonValidation'] = 'Proszę podać poprawnie godzinę rozpoczęcia!';
            return false;
        }
        else if(strlen($hour) != 5) {
            $_SESSION['bookLessonValidation'] = 'Proszę podać poprawnie godzinę rozpoczęcia!';
            return false;
        }
        else if($hour[2] != ":" || ($hour[3] != '0' && $hour[3] != '3') || $hour[4] != '0') {
            $_SESSION['bookLessonValidation'] = 'Proszę podać czas rozpoczęcia tak, aby lekcja zaczynała się o pełnej godzinie lub 30 minut po pełnej godzinie!';
            return false;
        }
        return true;
    }
    
    public static function lessonTypeValidation($type) {
        if($type != "Narciarstwo" && $type != "Snowboard") {
            $_SESSION['bookLessonValidation'] = 'Proszę wybierz poprawnie rodzaj sportu!';
            return false;
        }
        return true;
    }
    
    public static function instructorValidation($login, $type, $day) {
        $typeSQL = "";
        if($type == "Narciarstwo") {
            $typeSQL = "isSkiing=1";
        }
        else if($type == "Snowboard") {
            $typeSQL = "isSnowboard=1";
        }
        if(!self::isCorrectInstructor($login, $typeSQL, $day)) {
            $_SESSION['bookLessonValidation'] = 'Proszę wybierz poprawnie instruktora!';
            return false;
        }
        return true;
    }
    
    public static function isCorrectInstructor($login, $typeSQL, $day) {
        $db = new Database();
        try {
            $result = $db->mysqli->query(
                sprintf("SELECT idAccount FROM account a INNER JOIN instructortype it ON a.idAccount=it.idInstructor INNER JOIN workschedule ws ON a.idAccount=ws.idUser WHERE a.login='%s' AND a.role='Instruktor' AND a.deletedDate IS NULL AND DATE(ws.startTime)='%s' AND $typeSQL",
                mysqli_real_escape_string($db->mysqli, $login),
                mysqli_real_escape_string($db->mysqli, $day)));
            if($result->num_rows > 0) {
                return true;
            }
            else {
                return false;
            }
        } catch (Exception $ex) {
            return false;
        }
    }
    
    public static function isLessonBeetwenInstructorTimetable($lessonStartTime, $lessonFinishTime, $instructorLogin) {
        $db = new Database();
        try {
            $result = $db->mysqli->query( //Pobieramy grafik pracy instruktora z danego dnia
                sprintf("SELECT startTime, finishTime FROM workschedule ws INNER JOIN account a ON ws.idUser=a.idAccount WHERE a.login='%s' AND DATE(ws.startTime)=DATE('%s')",
                mysqli_real_escape_string($db->mysqli, $instructorLogin),
                mysqli_real_escape_string($db->mysqli, $lessonStartTime)));
            $row = $result->fetch_assoc();
            $instructorStartTimeUnix = strtotime($row['startTime']);
            $instructorFinishTimeUnix = strtotime($row['finishTime']);
            $lessonStartTimeUnix = strtotime($lessonStartTime);
            $lessonFinishTimeUnix = strtotime($lessonFinishTime);
            if($lessonStartTimeUnix < $instructorStartTimeUnix || $lessonFinishTimeUnix > $instructorFinishTimeUnix) {
                $_SESSION['bookLessonValidation'] = 'Proszę podać prawidłową datę! Zobacz grafik instruktora klikając w wybrany dzień w kalendarzu.';
                return false;
            }
        } catch (Exception $ex) {
            return false;
        }
        return true;
    }
        
    public static function isLessonDatetimeAvailable($lessonStartTime, $lessonFinishTime, $instructorLogin) {
        $db = new Database();
        $userStartTimeUnix = strtotime($lessonStartTime);
        $userFinishTimeUnix = strtotime($lessonFinishTime);
        try {
            $result = $db->mysqli->query( //Pobieramy wszystkie lekcje danego instruktora z danego dnia
                sprintf("SELECT les.startDate, les.finishDate FROM lesson les INNER JOIN account a ON les.idInstructor=a.idAccount WHERE a.login='%s' AND DATE(les.startDate)=DATE('%s')",
                mysqli_real_escape_string($db->mysqli, $instructorLogin),
                mysqli_real_escape_string($db->mysqli, $lessonStartTime)));
            while($row = $result->fetch_assoc()) {
                $instructorStartLessonUnix = strtotime($row["startDate"]);
                $instructorFinishLessonUnix = strtotime($row["finishDate"]);
                if($userFinishTimeUnix > $instructorStartLessonUnix && $userStartTimeUnix < $instructorFinishLessonUnix) {
                    $_SESSION['bookLessonValidation'] = 'Proszę podać datę dostępną dla wybranego instruktora! Zobacz grafik instruktora klikając w wybrany dzień w kalendarzu.';
                    return false;
                }
            }
        } catch (Exception $ex) {
            return false;
        }
        return true;
    }
    
    public static function isLessonDatetimeFuture($lessonStartTime) {
        $lessonStartTimeUnix = strtotime($lessonStartTime);
        $nowUnix = strtotime(date("Y-m-d H:i"));
        if($lessonStartTimeUnix < $nowUnix) {
            $_SESSION['bookLessonValidation'] = 'Proszę podać datę z przyszłosći!';
            return false;
        }
        else if($lessonStartTimeUnix < $nowUnix + 3600) {
            $_SESSION['bookLessonValidation'] = 'Możesz zarezerwować lekcję z co najmniej godzinnym wyprzedzeniem!';
            return false;
        }
        return true;
    }
    
    public static function isLessonToCancel($lessonID, $userID) {
        $db = new Database();
        try {
            $result = $db->mysqli->query(
                sprintf("SELECT startDate FROM lesson WHERE idLesson=%s AND idUser=%s",
                mysqli_real_escape_string($db->mysqli, $lessonID),
                mysqli_real_escape_string($db->mysqli, $userID)));
            if($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $startDateUNIX = strtotime($row['startDate']);
                $nowUNIX = strtotime(date("Y-m-d H:i"));
                if($startDateUNIX < $nowUNIX+3600) { //Odwołać można co najmniej 1h przed lekcją
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
