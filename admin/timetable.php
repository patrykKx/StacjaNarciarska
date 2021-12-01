<?php
    session_start();
    $text = ''; $color = '';
    if(isset($_SESSION['timetableValidation'])) {
        $text = $_SESSION['timetableValidation']; $color = 'danger';
        unset($_SESSION['timetableValidation']);
    }
    else if(isset($_SESSION['addTimetable'])) {      
        if($_SESSION['addTimetable'] == true) {
            $text = 'Pomyślnie dodałeś pracownika do grafiku!'; $color = 'success';
        }
        else {
            $text = 'Wystąpił błąd podczas aktualizacji grafiku!'; $color = 'danger';
        } 
        unset($_SESSION['addTimetable']);
    }
    if($text != '' && $color != '') {
        echo '<h5 class="alert alert-'.$color.' alert-dismissible fade show pb-2 text-center" role="alert">'.$text.
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.
             '</h5>';
    }
?>

<section class="text-center bg-light" id="timetableContainer">
    <h4>Kalendarz</h3>
    <div class="col-12 col-sm-8 mx-auto pt-3" id="calendarSection">
        <div id="calendarHeader"></div>
        <div id="calendarBody"></div>
    </div>
    <div class="row" id="dayDescription" style="font-size: 120%;">
        <div class="col-6 py-1" id="day">&nbsp;</div>
        <div class="col-6 py-1" id="isOpen">&nbsp;</div>
        <div class="col-6 " id="startHour">&nbsp;</div>
        <div class="col-6 " id="finishHour">&nbsp;</div>
    </div>
</section>

<section class="text-center bg-light pb-5" id="timetableTable" hidden="true">
    <h4>Grafik</h4>
    <div class="" id="timetableServiceman"></div>
    <div class="" id="timetableTechnicans"></div>
    <div class="" id="timetableInstructors"></div>
</section>

<section class="pb-5 text-center bg-light" id="addTimetable" hidden="true">
    <h4>Dodaj pracownika do grafiku</h4>
    <div class="py-3" id="workerTypeRadios">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="servicemanRadio" value="Serwisant">
            <label class="form-check-label" for="servicemanRadio">Serwisant</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="technicianRadio" value="Techniczny">
            <label class="form-check-label" for="technicianRadio">Techniczny</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="instructorRadio" value="Instruktor">
            <label class="form-check-label" for="instructorRadio">Instruktor</label>
        </div>
    </div>
    <div class="form-group pb-3" id="addTimetableDetails" hidden="true">
        <form method="POST" action="controller.php">
            <div class="row pb-sm-2">
                <div class="col-sm-4 mx-auto">
                    <label for="dayInput">Dzień</label>
                    <input type="date" class="custom-select" name="day" id="dayInput" readonly="readonly"/> 
                </div>
                <div class="col-sm-4 mx-auto">
                    <label for="workerStartHour">Godzina rozpoczęcia</label>
                    <input type="time" class="custom-select" name="workerStartHour" id="workerStartHour"/> 
                </div>
                <div class="col-sm-4 mx-auto">
                    <label for="workerFinishHour">Godzina zakończenia</label>
                    <input type="time" class="custom-select" name="workerFinishHour" id="workerFinishHour"/> 
                </div>
            </div>
            <div class="row pb-5">
                <div class="col-sm-8 mx-auto">
                    <label for="workerName">Pracownik</label>
                    <select class="custom-select" name="workerName" id="workerName"> 
                        <option disabled selected></option>
                    </select>
                </div>
                <input type="hidden" name="workerLogin" id="workerLogin"/>
            </div>
            <div class="row">
                <button type="submit" name="submit" value="addTimetable" class="btn btn-success col-4 col-md-3 mx-auto">Potwierdź</button>
                <button type="reset" class="btn btn-warning text-light col-4 col-md-3 mx-auto">Wyczyść</button>
            </div>
        </form>
    </div>
</section>

<?php
    if(isset($_SESSION['calendarDay'])) {
        ?> 
            <script>
                let id = <?php echo $_SESSION['calendarDay'];?>;
                dayClickTimetable(id);
            </script>
        <?php
        unset($_SESSION['calendarDay']);
    }
