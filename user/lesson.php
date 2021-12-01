<?php
session_start();

$text = ""; $color = "";
if(isset($_SESSION['bookLessonValidation'])) {
    $text = $_SESSION['bookLessonValidation'];
    $color = "danger";
    unset($_SESSION['bookLessonValidation']);
}
else if(isset($_SESSION['bookLesson'])) {
    if($_SESSION['bookLesson'] == true) {
        $text = "Pomyślnie zarezerwowałeś lekcję z instruktorem!";
        $color = "success";
    }
    else {
        $text = "Wystąpił błąd podczas dokonywania rezerwacji lekcji z instruktorem!";
        $color = "danger";
    }
    unset($_SESSION['bookLesson']);
}
else if(isset($_SESSION['cancelLesson'])) {
    if($_SESSION['cancelLesson'] == true) {
        $text = "Pomyślnie odwołałeś lekcję z instruktorem!";
        $color = "success";
    }
    else {
        $text = "Wystąpił błąd podczas odwoływania lekcji!";
        $color = "danger";
    }
    unset($_SESSION['cancelLesson']);
}
if($text != "" && $color != "") {
    echo '<h5 class="alert alert-'.$color.' alert-dismissible fade show pb-2 text-center" role="alert">'.$text.
             '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.
          '</h5>';
}
?>

<section class="text-center bg-light pb-5">
    <h4>Lekcje z instruktorem</h4>
    <form method="POST" action="controller.php">
        <div class="form-group">
            <div class="row">
                <div class="col-10 col-sm-8 col-xl-3 py-2 mx-auto">
                    <label for="inputDay">Dzień</label>
                    <input type="date" class="form-control" id="inputDay" name="inputDay"/>
                </div>
                <div class="col-6 col-sm-4 col-xl-2 py-2">
                    <label for="startLessonHour">Godzina rozpoczęcia</label>
                    <input type="time" class="form-control" id="startLessonHour" name="startLessonHour"/>
                </div>
                <div class="col-6 col-sm-4 col-xl-2 py-2">
                    <label for="numberOfHours">Liczba godzin</label>
                    <input type="number" min="1" max="3" class="form-control" id="numberOfHours" name="numberOfHours"/>
                </div>
                <div class="col-6 col-sm-4 col-xl-2 py-2">
                    <label for="numberOfParticipants">Ilość uczestników</label>
                    <input type="number" min="1" max="5" class="form-control" id="numberOfParticipants" name="numberOfParticipants"/>
                </div>
                <div class="col-6 col-sm-4 col-xl-3 py-2">
                    <label for="cost">Koszt</label>
                    <input type="text" readonly class="form-control" id="cost" name="cost"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 py-2 mx-auto">
                    <label for="instructorTypeRadios">Sport</label>
                    <div class="" id="instructorTypeRadios">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="lessonSport" id="lessonSkiRadio" value="Narciarstwo">
                            <label class="form-check-label" for="lessonSkiRadio">Narciarstwo</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="lessonSport" id="lessonSnowboardRadio" value="Snowboard">
                            <label class="form-check-label" for="lessonSnowboardRadio">Snowboard</label>
                        </div>
                    </div>
                </div>
                <div class="col-10 col-md-6 py-2 mx-auto">
                    <label for="instructorSelect">Instruktor</label>
                    <select class="custom-select" name="instructorSelect" id="instructorSelect">
                    <option value="" disabled selected></option>  
                  </select>
                </div>
                <div class="col-12 row pt-4">
                    <button type="submit" name="submit" value="bookLesson" class="btn btn-success col-4 col-md-3 mx-auto">Potwierdź</button>
                    <button type="reset" class="btn btn-warning text-light col-4 col-md-3 mx-auto">Wyczyść</button>
                </div>
            </div>
        </div>
    </form>
    
</section>

<section class="text-center bg-light pb-4">
    <h4>Kalendarz</h4>
    <div class="col-12 col-sm-8 mx-auto pt-3" id="lessonContainer">
        <div  id="calendarHeader"></div>
        <div id="calendarBody"></div>
    </div>
    <div class="row" id="dayDescription">
        <div class="col-6 py-1" id="day">&nbsp;</div>
        <div class="col-6 py-1" id="isOpen">&nbsp;</div>
        <div class="col-6 " id="startHour"></div>
        <div class="col-6 " id="finishHour"></div>
    </div>
    <div id="instructorTimetableContainer"></div>
</section>

<section class="text-center bg-light pb-5">
    <h4>Twoje lekcje</h4>
    <div id="userLessonsContainer"></div>
</section>