<?php
session_start();
$text = ""; $color = "";
if(isset($_SESSION['buyTicket'])) {
    if($_SESSION['buyTicket'] == true) {
        $text = 'Pomyślnie kupiłeś karnet!';
        $color = 'success';
    }
    else {
        $text = 'Wystąpił błąd podczas kupna karnetu!';  
        $color = 'danger';
    } 
    unset($_SESSION['buyTicket']);
}
else if(isset($_SESSION['cancelTicket'])) {
    if($_SESSION['cancelTicket'] == true) {
        $text = 'Pomyślnie anulowałeś karnet!';
        $color = 'success';
    }
    else {
        $text = 'Wystąpił błąd podczas zmiany statusu karnetu!';  
        $color = 'danger';
    } 
    unset($_SESSION['cancelTicket']);
}
else if(isset($_SESSION['ticketDayValidation'])) {
    $text = $_SESSION['ticketDayValidation'];
    $color = "danger";
    unset($_SESSION['ticketDayValidation']);
}
else if(isset($_SESSION['startHourValidation'])) {
    $text = $_SESSION['startHourValidation'];
    $color = "danger";
    unset($_SESSION['startHourValidation']);
}
else if(isset($_SESSION['numberOfHoursValidation'])) {
    $text = $_SESSION['numberOfHoursValidation'];
    $color = "danger";
    unset($_SESSION['numberOfHoursValidation']);
}
if($text != "" && $color != "") {
    echo '<h5 class="alert alert-'.$color.' alert-dismissible fade show pb-2 text-center" role="alert">'.$text.
             '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.
          '</h5>';
}
?>


<section class="text-center bg-light" id="buyTicket">
    <h4>Zarezerwuj karnet</h4>
    <form method="POST" action="controller.php">
        <div class="form-group">
            <div class="row">
                <div class="col-md-4 py-2">
                    <label for="inputDay">Dzień</label>
                    <input type="date" class="form-control" id="inputDay" name="ticketDay"
                           value="<?php if(isset($_SESSION['ticketDay'])){echo $_SESSION['ticketDay']; unset($_SESSION['ticketDay']);} ?>"/>
                </div>
                <div class="col-8 col-md-3 py-2">
                    <label for="startTicketHour">Godzina rozpoczęcia</label>
                    <input type="time" class="form-control" id="startTicketHour" name="startTicketHour"
                           value="<?php if(isset($_SESSION['ticketStartHour'])){echo $_SESSION['ticketStartHour']; unset($_SESSION['ticketStartHour']);} ?>"/>
                </div>
                <div class="col-4 col-md-2 py-2">
                    <label for="numberOfHours">Liczba godzin</label>
                    <input type="number" min="1" max="16" class="form-control" id="numberOfHours" name="numberOfHours"
                           value="<?php if(isset($_SESSION['ticketNumberOfHours'])){echo $_SESSION['ticketNumberOfHours']; unset($_SESSION['ticketNumberOfHours']);} ?>"/>
                </div>
                <div class="col-md-3 py-2">
                    <label for="cost">Koszt</label>
                    <input type="text" readonly class="form-control" id="cost" name="cost"
                           value="<?php if(isset($_SESSION['ticketCost'])){echo $_SESSION['ticketCost']; unset($_SESSION['ticketCost']);} ?>"/>
                </div>
                <div class="col-12 row pt-4">
                    <button type="submit" name="submit" value="buyTicket" class="btn btn-success col-4 col-md-3 mx-auto">Potwierdź</button>
                    <button type="reset" class="btn btn-warning text-light col-4 col-md-3 mx-auto">Wyczyść</button>
                </div>
            </div>
        </div>
    </form>
</section>

<section class="text-center bg-light">
    <h4>Kalendarz</h4>
    <div class="col-12 col-sm-8 mx-auto pt-3" id="calendarContainer">
        <div  id="calendarHeader"></div>
        <div id="calendarBody"></div>
    </div>
    <div class="row" id="dayDescription">
        <div class="col-6 py-1" id="day">&nbsp;</div>
        <div class="col-6 py-1" id="isOpen">&nbsp;</div>
        <div class="col-6 " id="startHour"></div>
        <div class="col-6 " id="finishHour"></div>
    </div>
</section>

<section class="text-center bg-light pb-5" id="sectionKalendarz">
    <h4>Twoje karnety</h4>
    <div class="row py-3">
        <div class="col-3"><button type="button" class="col-9 col-sm-6 btn btn-info" onclick="prevYear()"><img src="images/leftArrow.png" class="img-fluid" alt="leftArrow"/></button></div>
        <div class="col-6"><input type="text" readonly="true" class="col-sm-6 mx-auto form-control text-center" id="ticketYearInput"/></div>
        <div class="col-3"><button type="button" class="col-9 col-sm-6 btn btn-info" onclick="nextYear()"><img src="images/rightArrow.png" class="img-fluid" alt="rightArrow"/></button></div>
    </div>
    <div id="ticketsContainer"></div>
</section>
