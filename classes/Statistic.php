<?php
require_once 'Database.php';

class Statistic {
    public function getTotalUserCost() {
        $db = new Database();
        $allUsers = [];
        try {
            $result = $db->mysqli->query("SELECT SUM(s.cost) AS sumValue, a.login FROM account a INNER JOIN skipass s ON a.idAccount=s.idUser WHERE a.role='Użytkownik' AND a.deletedDate IS NULL GROUP BY a.login ORDER BY 1 DESC");
            while($row = $result->fetch_assoc()) {
                $login = $row['login'];
                if(array_key_exists($login, $allUsers)) {
                    $allUsers[$login] += (float)$row['sumValue'];
                }
                else {
                    $allUsers[$login] = (float)$row['sumValue'];
                }
            }
            $result2 = $db->mysqli->query("SELECT SUM(les.cost) AS sumValue, a.login FROM account a INNER JOIN lesson les ON a.idAccount=les.idUser WHERE a.role='Użytkownik' AND a.deletedDate IS NULL GROUP BY a.login ORDER BY 1 DESC");
            while($row2 = $result2->fetch_assoc()) {
                $login = $row2['login'];
                if(array_key_exists($login, $allUsers)) {
                    $allUsers[$login] += (float)$row2['sumValue'];
                }
                else {
                    $allUsers[$login] = (float)$row2['sumValue'];
                }
            }
            $result3 = $db->mysqli->query("SELECT SUM(ser.cost) AS sumValue, a.login FROM account a INNER JOIN service ser ON a.idAccount=ser.idUser WHERE a.role='Użytkownik' AND a.deletedDate IS NULL GROUP BY a.login ORDER BY 1 DESC");
            while($row3 = $result3->fetch_assoc()) {
                $login = $row3['login'];
                if(array_key_exists($login, $allUsers)) {
                    $allUsers[$login] += (float)$row3['sumValue'];
                }
                else {
                    $allUsers[$login] = (float)$row3['sumValue'];
                }
            }
            $result4 = $db->mysqli->query("SELECT SUM(h.cost) AS sumValue, a.login FROM account a INNER JOIN hire h ON a.idAccount=h.idUser WHERE a.role='Użytkownik' AND a.deletedDate IS NULL GROUP BY a.login ORDER BY 1 DESC");
            while($row4 = $result4->fetch_assoc()) {
                if(array_key_exists($login, $allUsers)) {
                    $login = $row4['login'];
                    $allUsers[$login] += (float)$row4['sumValue'];
                }
                else {
                    $allUsers[$login] = (float)$row4['sumValue'];
                }
            }
        } catch (Exception $ex) {

        }
        arsort($allUsers);
        $allUsers = array_slice($allUsers, 0, 30);
        echo json_encode($allUsers);
    }
    
    public function getOpeningDayByMonths() {
        $db = new Database();
        $all['countValue'] = [];
        $all['date'] = [];
        try {
            $result = $db->mysqli->query("SELECT COUNT(*) AS countValue, YEAR(date) AS year, MONTH(date) AS month FROM openingschedule WHERE isOpen=1 GROUP BY YEAR(date), MONTH(date) ORDER BY date");
            while($row = $result->fetch_assoc()) {
                array_push($all['countValue'], $row['countValue']);
                array_push($all['date'], $row['year']."-".$row['month']);
            }
        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function getStationIncomeByMonths() {
        $db = new Database();
        $all['skipassCost'] = []; $all['lessonCost'] = []; $all['hireCost'] = []; $all['serviceCost'] = [];
        $dates = [];
        $correctAll['skipassCost'] = []; $correctAll['lessonCost'] = []; $correctAll['hireCost'] = []; $correctAll['serviceCost'] = [];
        try {
            $result = $db->mysqli->query("SELECT SUM(cost) AS skipassCost, YEAR(startTime) AS year, MONTH(startTime) AS month FROM skipass GROUP BY YEAR(startTime), MONTH(startTime) ORDER BY YEAR(startTime), MONTH(startTime)");
            while($row = $result->fetch_assoc()) {
                $date = $row["year"]."-".$row["month"];
                $all['skipassCost'][$date] = $row['skipassCost'];
                if(!in_array($date, $dates)) {
                    array_push($dates, $date);
                }
            }
            $result2 = $db->mysqli->query("SELECT SUM(cost) AS hireCost, YEAR(startTime) AS year, MONTH(startTime) AS month FROM hire GROUP BY YEAR(startTime), MONTH(startTime) ORDER BY YEAR(startTime), MONTH(startTime)");
            while($row2 = $result2->fetch_assoc()) {
                $date = $row2["year"]."-".$row2["month"];
                $all['hireCost'][$date] = $row2['hireCost'];
                if(!in_array($date, $dates)) {
                    array_push($dates, $date);
                }
            }
            $result3 = $db->mysqli->query("SELECT SUM(cost) AS lessonCost, YEAR(startDate) AS year, MONTH(startDate) AS month FROM lesson GROUP BY YEAR(startDate), MONTH(startDate) ORDER BY YEAR(startDate), MONTH(startDate)");
            while($row3 = $result3->fetch_assoc()) {
                $date = $row3["year"]."-".$row3["month"];
                $all['lessonCost'][$date] = $row3['lessonCost'];
                if(!in_array($date, $dates)) {
                    array_push($dates, $date);
                }
            }
            $result4 = $db->mysqli->query("SELECT SUM(cost) AS serviceCost, YEAR(startTime) AS year, MONTH(startTime) AS month FROM service GROUP BY YEAR(startTime), MONTH(startTime) ORDER BY YEAR(startTime), MONTH(startTime)");
            while($row4 = $result4->fetch_assoc()) {
                $date = $row4["year"]."-".$row4["month"];
                $all['serviceCost'][$date] = $row4['serviceCost'];
                if(!in_array($date, $dates)) {
                    array_push($dates, $date);
                }
            }
        } catch (Exception $ex) {

        }
        asort($dates);
        foreach($dates as $arrayDate) {
            if(array_key_exists($arrayDate, $all['skipassCost'])) {
                $correctAll['skipassCost'][$arrayDate] = (float)$all['skipassCost'][$arrayDate];
            }
            else {
                $correctAll['skipassCost'][$arrayDate] = 0;
            }
            if(array_key_exists($arrayDate, $all['hireCost'])) {
                $correctAll['hireCost'][$arrayDate] = (float)$all['hireCost'][$arrayDate];
            }
            else {
                $correctAll['hireCost'][$arrayDate] = 0;
            }
            if(array_key_exists($arrayDate, $all['lessonCost'])) {
                $correctAll['lessonCost'][$arrayDate] = (float)$all['lessonCost'][$arrayDate];
            }
            else {
                $correctAll['lessonCost'][$arrayDate] = 0;
            }
            if(array_key_exists($arrayDate, $all['serviceCost'])) {
                $correctAll['serviceCost'][$arrayDate] = (float)$all['serviceCost'][$arrayDate];
            }
            else {
                $correctAll['serviceCost'][$arrayDate] = 0;
            }
        }
        echo json_encode($correctAll);
    }
    
    public function getWorkerHoursByMonths() {
        $db = new Database();
        $all['instructor'] = [];
        $all['serviceman'] = [];
        $all['technician'] = [];
        $date = "";
        try {
            $result = $db->mysqli->query("SELECT SUM(UNIX_TIMESTAMP(ws.finishTime)-UNIX_TIMESTAMP(ws.startTime)) AS unixSeconds, a.role, YEAR(ws.startTime) AS year, MONTH(ws.startTime) AS month FROM workschedule ws INNER JOIN account a ON ws.idUser=a.idAccount WHERE ws.startTime<NOW() GROUP BY YEAR(ws.startTime), MONTH(ws.startTime), a.role ORDER BY YEAR(ws.startTime), MONTH(ws.startTime)");
            while($row = $result->fetch_assoc()) {
                if($date != $row['year']."-".$row['month']) {
                    $date = $row['year']."-".$row['month'];
                    $all['instructor'][$date] = 0;
                    $all['serviceman'][$date] = 0;
                    $all['technician'][$date] = 0;
                }
                if($row['role'] == "Instruktor") {
                    $all['instructor'][$date] = (float)($row['unixSeconds']/3600);
                }
                else if($row['role'] == "Serwisant") {
                    $all['serviceman'][$date] = (float)($row['unixSeconds']/3600);
                }
                else if($row['role'] == "Techniczny") {
                    $all['technician'][$date] = (float)($row['unixSeconds']/3600);
                }  
            }
        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function getTicketHighlights() {
        $all['oneHour'] = 0;
        $all['twoHours'] = 0;
        $all['threeHours'] = 0;
        $all['allDay'] = 0;
        $db = new Database();
        try {
            $result = $db->mysqli->query("SELECT UNIX_TIMESTAMP(finishTime)-UNIX_TIMESTAMP(startTime) as unixSeconds FROM skipass WHERE isCancelled=0");
            while($row = $result->fetch_assoc()) {
                $hours = (int)($row['unixSeconds']/3600);
                if($hours > 3) $all['allDay']++;
                else if($hours > 2) {
                    $all['threeHours']++;
                }
                else if($hours > 1) {
                    $all['twoHours']++;
                }
                else if($hours > 0) {
                    $all['oneHour']++;
                }
            }
        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function getHireHighlights() {
        $db = new Database();
        $all["skis"] = 0;
        $all["skiBoots"] = 0;
        $all["snowboard"] = 0;
        $all["snowboardBoots"] = 0;
        $all["helmet"] = 0;
        try {
            $result = $db->mysqli->query("SELECT COUNT(*) as countValue, e.type FROM hire h INNER JOIN hire_has_equipment hhe ON h.idHire=hhe.idHire INNER JOIN equipment e ON e.idEquipment=hhe.idEquipment WHERE h.cost <> 0 GROUP BY e.type");
            while($row = $result->fetch_assoc()) {
               switch($row['type']) {
                   case "Narty": $all['skis'] = (int)$row['countValue']; break;
                   case "Buty narciarskie": $all['skiBoots'] = (int)$row['countValue']; break;
                   case "Snowboard": $all['snowboard'] = (int)$row['countValue']; break;
                   case "Buty snowboardowe": $all['snowboardBoots'] = (int)$row['countValue']; break;
                   case "Kask": $all['helmet'] = (int)$row['countValue']; break;
               }
            }
        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function getLessonHighlights() {
        $db = new Database();
        $all["skiing"] = 0;
        $all["snowboard"] = 0;
        try {
            $result = $db->mysqli->query("SELECT COUNT(*) as countValue, type FROM lesson WHERE isCancelled=0 GROUP BY type ");
            while($row = $result->fetch_assoc()) {
               switch($row['type']) {
                   case "Narciarstwo": $all['skiing'] = (int)$row['countValue']; break;
                   case "Snowboard": $all['snowboard'] = (int)$row['countValue']; break;
               }
            }
        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function getWorkerHighlightsByMonthAndTypeToAdmin() {
        $year = filter_input(INPUT_POST, "year");
        $month = filter_input(INPUT_POST, "month");
        $workerType = filter_input(INPUT_POST, "workerType");
        $all["name"] = [];
        $all["hours"] = [];
        $db = new Database();
        try {
            $result = $db->mysqli->query(
                sprintf("SELECT SUM(UNIX_TIMESTAMP(ws.finishTime)-UNIX_TIMESTAMP(ws.startTime)) AS unixSeconds, a.login, pd.firstname, pd.surname FROM workschedule ws INNER JOIN account a ON ws.idUser=a.idAccount INNER JOIN personaldata pd ON a.idAccount=pd.idAccount WHERE ws.startTime<NOW() AND YEAR(ws.startTime)='%s' AND MONTH(ws.startTime)='%s' AND a.role='%s' GROUP BY a.login, pd.firstname, pd.surname ORDER BY 1 DESC",
                    mysqli_real_escape_string($db->mysqli, $year),
                    mysqli_real_escape_string($db->mysqli, $month),
                    mysqli_real_escape_string($db->mysqli, $workerType)));
            while($row = $result->fetch_assoc()) {
                array_push($all['name'], $row['surname']." ".$row['firstname']);
                array_push($all['hours'], (float)($row['unixSeconds']/3600));
            }
        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function getTotalUserActivity() {
        $db = new Database();
        $all["skipass"] = 0;
        $all["service"] = 0;
        $all["hire"] = 0;
        $all["lesson"] = 0;
        $userID = $_SESSION['id'];
        try {
            $skipassResult = $db->mysqli->query("SELECT COUNT(*) AS skipassCount FROM account a INNER JOIN skipass s ON a.idAccount=s.idUser WHERE a.idAccount=$userID AND s.startTime < NOW()");
            $row = $skipassResult->fetch_assoc();
            $all['skipass'] = (int)$row['skipassCount'];
            $serviceResult = $db->mysqli->query("SELECT COUNT(*) AS serviceCount FROM account a INNER JOIN service ser ON a.idAccount=ser.idUser WHERE a.idAccount=$userID AND ser.startTime < NOW()");
            $row2 = $serviceResult->fetch_assoc();
            $all['service'] = (int)$row2['serviceCount'];
            $hireResult = $db->mysqli->query("SELECT COUNT(*) AS hireCount FROM account a INNER JOIN hire h ON a.idAccount=h.idUser WHERE a.idAccount=$userID AND h.startTime < NOW()");
            $row3 = $hireResult->fetch_assoc();
            $all['hire'] = (int)$row3['hireCount'];
            $lessonResult = $db->mysqli->query("SELECT COUNT(*) AS lessonCount FROM account a INNER JOIN lesson les ON a.idAccount=les.idUser WHERE a.idAccount=$userID AND les.isCancelled=0 AND les.finishDate < NOW()");
            $row4 = $lessonResult->fetch_assoc();
            $all['lesson'] = (int)$row4['lessonCount'];
        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function getUserSkipassHourByMonths() {
        $db = new Database();
        $all["date"] = [];
        $all["sum"] = [];
        $userID = $_SESSION['id'];
        try {
            $result = $db->mysqli->query("SELECT SUM(UNIX_TIMESTAMP(s.finishTime)-UNIX_TIMESTAMP(s.startTime)) AS sumValue, YEAR(s.startTime) AS year, MONTH(s.startTime) AS month FROM account a INNER JOIN skipass s ON a.idAccount=s.idUser WHERE a.idAccount=$userID AND s.isCancelled=0 AND s.startTime<NOW() GROUP BY YEAR(s.startTime), MONTH(s.startTime) ORDER BY YEAR(s.startTime), MONTH(s.startTime)");
            while($row = $result->fetch_assoc()) {
                 array_push($all['date'], $row['year']."-".$row['month']);
                 array_push($all['sum'], (float)($row['sumValue']/3600));
            }
        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function getHireHighlightsByMonths() {
        $db = new Database();
        $all["skis"] = [];
        $all["skiBoots"] = [];
        $all["snowboard"] = [];
        $all["snowboardBoots"] = [];
        $all["helmet"] = [];
        $date = "";
        try {
            $result = $db->mysqli->query("SELECT COUNT(*) AS countValue, e.type, YEAR(h.startTime) AS year, MONTH(h.startTime) AS month FROM hire h INNER JOIN hire_has_equipment hhe ON h.idHire=hhe.idHire INNER JOIN equipment e ON e.idEquipment=hhe.idEquipment GROUP BY YEAR(h.startTime), MONTH(h.startTime), e.type ORDER BY YEAR(h.startTime), MONTH(h.startTime)");
            while($row = $result->fetch_assoc()) {
                if($date != $row['year']."-".$row['month']) {
                    $date = $row['year']."-".$row['month'];
                    $all['skis'][$date] = 0;
                    $all['skiBoots'][$date] = 0;
                    $all['snowboard'][$date] = 0;
                    $all['snowboardBoots'][$date] = 0;
                    $all['helmet'][$date] = 0;
                }
                if($row['type'] == "Narty") {
                    $all['skis'][$date] = (int)$row['countValue'];
                }
                else if($row['type'] == "Buty narciarskie") {
                    $all['skiBoots'][$date] = (int)$row['countValue'];
                }
                else if($row['type'] == "Snowboard") {
                    $all['snowboard'][$date] = (int)$row['countValue'];
                }
                else if($row['type'] == "Buty narciarskie") {
                    $all['snowboardBoots'][$date] = (int)$row['countValue'];
                }
                else if($row['helmet'] == "Kask") {
                    $all['helmet'][$date] = (int)$row['countValue'];
                }
            }
        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function getWorkerHighlightsByMonths() {
        $db = new Database();
        $workerID = $_SESSION['id'];
        $all['date'] = [];
        $all['hours'] = [];
        try {
            $result = $db->mysqli->query("SELECT SUM(UNIX_TIMESTAMP(finishTime)-UNIX_TIMESTAMP(startTime)) AS unixSeconds, YEAR(ws.startTime) AS year, MONTH(ws.startTime) AS month FROM workschedule ws INNER JOIN account a ON ws.idUser=a.idAccount WHERE a.idAccount=$workerID AND ws.startTime < NOW() GROUP BY YEAR(ws.startTime), MONTH(ws.startTime) ORDER BY YEAR(ws.startTime), MONTH(ws.startTime)");
            while($row = $result->fetch_assoc()) {
                array_push($all['date'], $row['year']."-".$row['month']);
                array_push($all['hours'], (float)($row['unixSeconds']/3600));
            }
        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function getMostActiveInstructors() {
        $db = new Database();
        $all['hours'] = [];
        $all['numberOfParticipants'] = [];
        $all['instructor'] = [];
        try {
            $result = $db->mysqli->query("SELECT SUM(UNIX_TIMESTAMP(finishDate)-UNIX_TIMESTAMP(startDate)) AS unixSeconds, SUM(numberOfParticipants) AS numberOfParticipants, a.login, pd.firstname, pd.surname FROM lesson les INNER JOIN account a ON les.idInstructor=a.idAccount INNER JOIN personaldata pd ON pd.idAccount=a.idAccount WHERE les.isCancelled=0 AND les.finishDate < NOW() GROUP BY a.login, pd.firstname, pd.surname ORDER BY 1 DESC LIMIT 25");
            while($row = $result->fetch_assoc()) {
                array_push($all['hours'], (float)($row['unixSeconds']/3600));
                array_push($all['numberOfParticipants'], (int)$row['numberOfParticipants']);
                array_push($all['instructor'], $row['surname']." ".$row['firstname']);
            }
        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
    
    public function getInstructorLessonHighlights() {
        $db = new Database();
        $workerID = $_SESSION['id'];
        $all['skiing'] = [];
        $all['snowboard'] = [];
        $date = "";
        try {
            $result = $db->mysqli->query("SELECT SUM(UNIX_TIMESTAMP(les.finishDate)-UNIX_TIMESTAMP(les.startDate)) AS unixSeconds, YEAR(les.startDate) AS year, MONTH(les.startDate) AS month, les.type FROM lesson les INNER JOIN account a ON les.idInstructor=a.idAccount WHERE les.isCancelled=0 AND les.finishDate < NOW() AND a.idAccount=$workerID GROUP BY YEAR(startDate), MONTH(startDate), les.type ORDER BY YEAR(startDate), MONTH(startDate)");
            while($row = $result->fetch_assoc()) {
                if($date != $row['year']."-".$row['month']) {
                    $date = $row['year']."-".$row['month'];
                    $all['skiing'][$date] = 0;
                    $all['snowboard'][$date] = 0;
                }
                if($row['type'] == "Narciarstwo") {
                    $all['skiing'][$date] = (float)($row['unixSeconds']/3600);
                }
                else if($row['type'] == "Snowboard") {
                    $all['snowboard'][$date] = (float)($row['unixSeconds']/3600);
                } 
            }
        } catch (Exception $ex) {

        }
        echo json_encode($all);
    }
}
