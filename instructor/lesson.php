<?php
session_start();

$text = ""; $color = "";
if(isset($_SESSION['cancelLesson'])) {
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

<section class="pb-5 text-center bg-light">
    <h4>Zamówione lekcje</h4>
    <div class="row pt-2">
        <div class="col-3"><button type="button" class="col-9 col-sm-6 btn btn-info" onclick="prevDay()"><img src="images/leftArrow.png" class="img-fluid" alt="leftArrow"/></button></div>
        <div class="col-6"><input type="date" class="form-control" id="lessonDateInput"/></div>
        <div class="col-3"><button type="button" class="col-9 col-sm-6 btn btn-info" onclick="nextDay()"><img src="images/rightArrow.png" class="img-fluid" alt="rightArrow"/></button></div>
    </div>
    
    <div id="instructorLessonContainer" class="pt-2"></div>
</section>
