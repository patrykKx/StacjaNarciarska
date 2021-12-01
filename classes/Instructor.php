<?php
require_once 'Database.php';
require_once 'Validation.php';

class Instructor {

    public function getInstructorLessonsByDay() {
        $instructorID = $_SESSION['id'];
        $date = filter_input(INPUT_POST, "date");
        $db = new Database();
        $all['idLesson'] = [];
        $all['user'] = [];
        $all['startDate'] = [];
        $all['finishDate'] = [];
        $all['numberOfParticipants'] = [];
        $all['type'] = [];
        $all['isCancelled'] = [];
        try {
            $result = $db->mysqli->query(
                sprintf("SELECT idLesson, login, firstname, surname, startDate, finishDate, numberOfParticipants, type, isCancelled FROM lesson les INNER JOIN account a ON les.idUser=a.idAccount INNER JOIN personaldata pd ON pd.idAccount=a.idAccount WHERE idInstructor=%s AND DATE(startDate)='%s' ORDER BY startDate DESC", 
                mysqli_real_escape_string($db->mysqli, $instructorID),
                mysqli_real_escape_string($db->mysqli, $date)));  
            while($row = $result->fetch_assoc()) {
                array_push($all['idLesson'], $row['idLesson']);
                array_push($all['user'], $row['firstname']." ".$row['surname']." (".$row['login'].")");
                array_push($all['startDate'], $row['startDate']);
                array_push($all['finishDate'], $row['finishDate']);
                array_push($all['numberOfParticipants'], $row['numberOfParticipants']);
                array_push($all['type'], $row['type']);
                array_push($all['isCancelled'], $row['isCancelled']);
            }
        } catch (Exception $ex) {
        }
        echo json_encode($all);
    }
    
    public function cancelLesson() {
        $_SESSION['goToInstructorPage'] = 'lesson';
        $lessonID = filter_input(INPUT_POST, "idLesson");
        $instructorID = $_SESSION['id'];
        if(Validation::isLessonToCancelByInstructor($instructorID, $lessonID)) {
            $db = new Database();
            try {
                $db->mysqli->query(
                        sprintf("UPDATE lesson SET isCancelled=1, cost=0 WHERE idLesson=%s",
                        mysqli_real_escape_string($db->mysqli, $lessonID)));
                $_SESSION['cancelLesson'] = true;
            } catch (Exception $ex) {
                $_SESSION['cancelLesson'] = false;
            }
        }
        else {
            $_SESSION['cancelLesson'] = false;
        }
        header("Location: instructorPage.php");
    }
}
