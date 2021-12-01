<?php
    require_once '../classes/Person.php';
    session_start();
    $person = new Person();
    $person->getTariff('adminPage');

    $text = ''; $color = '';
    if(isset($_SESSION['tariffValidation'])) {
        $text = $_SESSION['tariffValidation']; $color = 'danger';
        unset($_SESSION['tariffValidation']);
    }
    else if(isset($_SESSION['conditionsValidation'])) {
        $text = $_SESSION['conditionsValidation']; $color = 'danger';
        unset($_SESSION['conditionsValidation']);
    }
    else if(isset($_SESSION['dayValidation'])) {
        $text = $_SESSION['dayValidation']; $color = 'danger';
        unset($_SESSION['dayValidation']);
    }
    else if(isset($_SESSION['addTariff'])) {
        if($_SESSION['addTariff'] == true) {
            $text = 'Pomyślnie zaktualizowałeś cennik!'; $color = 'success';
        }
        else {
            $text = 'Wystąpił błąd podczas aktualizacji cennika!'; $color = 'danger';
        } 
        unset($_SESSION['addTariff']);
    }
    else if(isset($_SESSION['addConditions'])) {
        if($_SESSION['addConditions'] == true) {
            $text = 'Pomyślnie zaktualizowałeś warunki!'; $color = 'success';
        }
        else {
            $text = 'Wystąpił błąd podczas aktualizacji warunków!'; $color = 'danger';
        } 
        unset($_SESSION['addConditions']);
    }
    else if(isset($_SESSION['addCalendar'])) {
        if($_SESSION['addCalendar'] == true) {
            $text = 'Pomyślnie zaktualizowałeś kalendarz!'; $color = 'success';
        }
        else {
            $text = 'Wystąpił błąd podczas aktualizacji kalendarza!'; $color = 'danger';
        } 
        unset($_SESSION['addCalendar']);
    }
    if($text != '' && $color != '') {
        echo '<h5 class="alert alert-'.$color.' alert-dismissible fade show pb-2 text-center" role="alert">'.$text.
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.
             '</h5>';
    }
    ?>

<section class="text-center pb-5" id="stationOpeningSection">
    <h4>Dni otwarcia</h4>
    <div class="col-12 col-sm-8 mx-auto py-3" id="calendarContainer">
        <div id="calendarHeader"></div>
        <div id="calendarBody"></div>
    </div>

    <form method="POST" action="controller.php" id="datasFromCalendar">
        <div class="form-group row mb-2">
            <div class="col-8 col-sm-4 mx-auto">
                <div class="text-left pb-1">Dzień</div>
                <input type="text" class="form-control" id="dayFromCalendar" name="date" onfocus="(this.type='date')" onblur="(this.type='text')"/>
            </div>
            <div class="col-8 col-sm-4 mx-auto" id="isOpen">
                <div class="text-left pb-1">Czy otwarte?</div>
                <select class="custom-select" name="isOpen" id="selectIsOpen"> 
                    <option disabled selected></option>
                    <option value="1">Otwarte</option>
                    <option value="0">Zamknięte</option>
                </select>
            </div>
        </div>
        <div class="form-group row" id="hours" hidden="true">
            <div class="col-8 col-sm-4 mx-auto">
                <div class="text-left pb-1">Godzina otwarcia</div>
                <input type="time" class="form-control" id="openingHour" name="openingHour"/>
            </div>
            <div class="col-8 col-sm-4 mx-auto">
                <div class="text-left pb-1">Godzina zamknięcia</div>
                <input type="time" class="form-control" id="closingHour" name="closingHour"/>
            </div>
        </div>
        <div class="col-12 row pt-4">
            <button type="submit" name="submit" value="submitCalendar" class="btn btn-success col-4 col-md-3 mx-auto">Potwierdź</button>
            <button type="reset" class="btn btn-warning text-light col-4 col-md-3 mx-auto">Wyczyść</button>
        </div>
    </form>
</section>

<section class="text-center pb-5" id="conditionsSection">
    <h4>Warunki</h4>
    <form method="POST" action="controller.php">
        <div class="form-group row  mb-2">
            <div class="col-6 col-sm-3">
                <label for="minSnowCover">Minimalna pokrywa (cm)</label>
                <input type="number" min="0" class="form-control" id="minSnowCover" name="minSnowCover"/>
            </div>
            <div class="col-6 col-sm-3">
                <label for="maxSnowCover">Maksymalna pokrywa (cm)</label>
                <input type="number" min="0" class="form-control" id="maxSnowCover" name="maxSnowCover"/>
            </div>
            <div class="col-6 col-sm-3">
                <label for="snowType">Rodzaj śniegu</label>
                <select class="custom-select" id="snowType" name="snowType">
                   <option selected disabled>Wybierz rodzaj śniegu</option>
                   <option value="Świeży puch">Świeży puch</option>
                   <option value="Przewiany gips">Przewiany gips</option>
                   <option value="Zbity gips">Zbity gips</option>
                   <option value="Lodoszreń">Lodoszreń</option>
                   <option value="Mokry">Mokry</option>
                   <option value="Ziarnisty">Ziarnisty</option>
                   <option value="Sztuczny">Sztuczny</option>
                   <option value="Mieszany">Mieszany</option>
                   <option value="Brak">Brak</option>
                </select>
            </div>
            <div class="col-6 col-sm-3">
                <label for="conditions">Warunki</label>
                <select class="custom-select" id="conditions" name="conditions">
                   <option selected disabled>Określ rodzaj warunków</option>
                   <option value="Znakomite">Znakomite</option>
                   <option value="Bardzo dobre">Bardzo dobre</option>
                   <option value="Dobre">Dobre</option>
                   <option value="Dostateczne">Dostateczne</option>
                   <option value="Brak">Brak</option>
                </select>
            </div>
            <div class="col-10 col-sm-8 mx-auto pt-2">
                <label for="description">Opis</label>
                <textarea class="form-control" id="description" name="description"></textarea>
            </div>
            <div class="col-12 row pt-4">
                <button type="submit" name="submit" value="submitConditions" class="btn btn-success col-4 col-md-3 mx-auto">Potwierdź</button>
                <button type="reset" class="btn btn-warning text-light col-4 col-md-3 mx-auto">Wyczyść</button>
            </div>
        </div>
    </form>
</section>

<section class="text-center pb-5">
    <h4>Cennik</h4>
    <form method="POST" action="controller.php">
        <article class="pt-3">
            <h5>Karnety</h5>
            <div class="form-group row  mb-2">
                <div class="col-6 col-sm-3">
                    <label for="skipass_1h">1h</label>
                    <input type="number" min="0" class="form-control" id="skipass_1h" name="tariff[]"
                           value="<?php
                                  if(isset($_SESSION['tariffValues'])) {
                                     echo $_SESSION['tariffValues']['skipass_1h']; 
                                  }
                                 ?>"/>
                </div>
                <div class="col-6 col-sm-3">
                    <label for="skipass_2h">2h</label>
                    <input type="number" min="0" class="form-control" id="skipass_2h" name="tariff[]" 
                           value="<?php
                                  if(isset($_SESSION['tariffValues'])) {
                                     echo $_SESSION['tariffValues']['skipass_2h']; 
                                  }
                                 ?>"/>
                </div>
                <div class="col-6 col-sm-3">
                    <label for="skipass_3h">3h</label>
                    <input type="number" min="0" class="form-control" id="skipass_3h" name="tariff[]"
                           value="<?php
                                  if(isset($_SESSION['tariffValues'])) {
                                     echo $_SESSION['tariffValues']['skipass_3h']; 
                                  }
                                 ?>"/>
                </div>
                <div class="col-6 col-sm-3">
                    <label for="skipass_allDay">Cały dzień</label>
                    <input type="number" min="0" class="form-control" id="skipass_allDay" name="tariff[]"
                           value="<?php
                                  if(isset($_SESSION['tariffValues'])) {
                                     echo $_SESSION['tariffValues']['skipass_allDay']; 
                                  }
                                 ?>"/>
                </div>
            </div>
        </article>
        <article class="pt-3">
            <h5>Wypożyczenie sprzętu</h5>
            <div class="form-group row mb-2">
                <div class="col-6 col-sm-3">
                    <label for="setRental_1h">Komplet 1h</label>
                    <input type="number" min="0" class="form-control" id="setRental_1h" name="tariff[]"
                           value="<?php
                                  if(isset($_SESSION['tariffValues'])) {
                                     echo $_SESSION['tariffValues']['setRental_1h']; 
                                  }
                                 ?>"/>
                </div>
                <div class="col-6 col-sm-3">
                    <label for="setRental_2h">Komplet 2h</label>
                    <input type="number" min="0" class="form-control" id="setRental_2h" name="tariff[]"
                           value="<?php
                                  if(isset($_SESSION['tariffValues'])) {
                                     echo $_SESSION['tariffValues']['setRental_2h']; 
                                  }
                                 ?>"/>
                </div>
                <div class="col-6 col-sm-3">
                    <label for="setRental_3h">Komplet 3h</label>
                    <input type="number" min="0" class="form-control" id="setRental_3h" name="tariff[]"
                           value="<?php
                                  if(isset($_SESSION['tariffValues'])) {
                                     echo $_SESSION['tariffValues']['setRental_3h']; 
                                  }
                                 ?>"/>                
                </div>
                <div class="col-6 col-sm-3">
                    <label for="setRental_allDay">Komplet Cały dzień</label>
                    <input type="number" min="0" class="form-control" id="setRental_allDay" name="tariff[]"
                           value="<?php
                                  if(isset($_SESSION['tariffValues'])) {
                                     echo $_SESSION['tariffValues']['setRental_allDay']; 
                                  }
                                 ?>"/>  
                </div>
                <div class="col-6 col-sm-3">
                    <label for="oneItemRental_1h">Jedna rzecz 1h</label>
                    <input type="number" min="0" class="form-control" id="oneItemRental_1h" name="tariff[]"
                           value="<?php
                                  if(isset($_SESSION['tariffValues'])) {
                                     echo $_SESSION['tariffValues']['oneItemRental_1h']; 
                                  }
                                 ?>"/>
                </div>
                <div class="col-6 col-sm-3">
                    <label for="oneItemRental_2h">Jedna rzecz 2h</label>
                    <input type="number" min="0" class="form-control" id="oneItemRental_2h" name="tariff[]"
                           value="<?php
                                  if(isset($_SESSION['tariffValues'])) {
                                     echo $_SESSION['tariffValues']['oneItemRental_2h']; 
                                  }
                                 ?>"/> 
                </div>
                <div class="col-6 col-sm-3">
                    <label for="oneItemRental_3h">Jedna rzecz 3h</label>
                    <input type="number" min="0" class="form-control" id="oneItemRental_3h" name="tariff[]"
                           value="<?php
                                  if(isset($_SESSION['tariffValues'])) {
                                     echo $_SESSION['tariffValues']['oneItemRental_3h']; 
                                  }
                                 ?>"/>
                </div>
                <div class="col-6 col-sm-3">
                    <label for="oneItemRental_allDay">Jedna rzecz Cały dzień</label>
                    <input type="number" min="0" class="form-control" id="oneItemRental_allDay" name="tariff[]"
                           value="<?php
                                  if(isset($_SESSION['tariffValues'])) {
                                     echo $_SESSION['tariffValues']['oneItemRental_allDay']; 
                                  }
                                 ?>"/>      
                </div>
            </div>
        </article>
        <article class="pt-3">
            <h5>Lekcje</h5>
            <div class="form-group row  mb-2">
                <div class="col-6 col-sm-3">
                    <label for="lesson_1h">1h</label>
                    <input type="number" min="0" class="form-control" id="lesson_1h" name="tariff[]"
                           value="<?php
                                  if(isset($_SESSION['tariffValues'])) {
                                     echo $_SESSION['tariffValues']['lesson_1h']; 
                                  }
                                 ?>"/>        
                </div>
                <div class="col-6 col-sm-3">
                    <label for="lesson_2h">2h</label>
                    <input type="number" min="0" class="form-control" id="lesson_2h" name="tariff[]"
                           value="<?php
                                  if(isset($_SESSION['tariffValues'])) {
                                     echo $_SESSION['tariffValues']['lesson_2h']; 
                                  }
                                 ?>"/>             
                </div>
                <div class="col-6 col-sm-3">
                    <label for="lesson_3h">3h</label>
                    <input type="number" min="0" class="form-control" id="lesson_3h" name="tariff[]"
                           value="<?php
                                  if(isset($_SESSION['tariffValues'])) {
                                     echo $_SESSION['tariffValues']['lesson_3h']; 
                                  }
                                 ?>"/> 
                </div>
            </div>
        </article>   
        <article class="pt-3">
            <h5>Serwis</h5>
            <div class="form-group row mb-2">
                <div class="col-6 col-sm-3">
                    <label for="smallService">Podstawowy serwis</label>
                    <input type="number" min="0" class="form-control" id="smallService" name="tariff[]"
                           value="<?php
                                  if(isset($_SESSION['tariffValues'])) {
                                     echo $_SESSION['tariffValues']['smallService']; 
                                  }
                                 ?>"/>        
                </div>
                <div class="col-6 col-sm-3">
                    <label for="mediumService">Średni serwis</label>
                    <input type="number" min="0" class="form-control" id="mediumService" name="tariff[]"
                           value="<?php
                                  if(isset($_SESSION['tariffValues'])) {
                                     echo $_SESSION['tariffValues']['mediumService']; 
                                  }
                                 ?>"/>             
                </div>
                <div class="col-6 col-sm-3">
                    <label for="bigService">Zaawansowany serwis</label>
                    <input type="number" min="0" class="form-control" id="bigService" name="tariff[]"
                           value="<?php
                                  if(isset($_SESSION['tariffValues'])) {
                                     echo $_SESSION['tariffValues']['bigService']; 
                                  }
                                 ?>"/> 
                </div>
            </div>
        </article>
        <div class="row pt-4">
            <button type="submit" name="submit" value="submitTariff" class="btn btn-success col-4 col-md-3 mx-auto">Potwierdź</button>
            <button type="reset" class="btn btn-warning text-light col-4 col-md-3 mx-auto">Wyczyść</button>
        </div>
    </form>
</section>