<?php
session_start();
$text = ''; $color = '';
if(isset($_SESSION['addHire'])) {
    if($_SESSION['addHire'] == true) {
        $text = 'Pomyślnie dodałeś wypożyczenie sprzętu!';
        $color = 'success';
    }
    else {
        $text = 'Wystąpił błąd podczas dodania wypożyczenia sprzętu!';  
        $color = 'danger';
    } 
    unset($_SESSION['addHire']);
    unset($_SESSION['loginValidation']);
}
else if(isset($_SESSION['changeHireStatus'])) {
    if($_SESSION['changeHireStatus'] == true) {
        $text = 'Pomyślnie zmieniłeś status wypożyczenia sprzętu!';
        $color = 'success';
    }
    else {
        $text = 'Wystąpił błąd podczas zmiany statusu wypożyczenia sprzętu!';  
        $color = 'danger';
    } 
    unset($_SESSION['changeHireStatus']);
}
if($text != '' && $color != '') {
    echo '<h5 class="alert alert-'.$color.' alert-dismissible fade show pb-2 text-center" role="alert">'.$text.
             '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.
          '</h5>';
}
    
?>

<section class="pb-5 text-center bg-light">
    <h4>Wypożyczenia</h4>
    <form method="POST" action="controller.php">
        <div class="form-group">
            <article class="row" id="mainEquipmentArticle">
                <div class="col-md-3">
                    <label for="userLogin">Użytkownik</label>
                    <select class="custom-select" name="userLogin" id="userLogin"> 
                        <option disabled selected></option>
                    </select>
                </div>
                <div class="col-md-9 row align-items-center ml-2 equipmentCheckbox">
                    <div class="custom-control custom-checkbox col-6 col-lg-2 text-left text-lg-center">
                        <input type="checkbox" class="custom-control-input " id="checkboxSki" value="ski" name="checkboxSki">
                        <label class="custom-control-label" for="checkboxSki">Narty</label>
                    </div>
                    <div class="custom-control custom-checkbox col-6 col-lg-3 text-left text-lg-center">
                        <input type="checkbox" class="custom-control-input " id="checkboxSkiBoots" value="skiBoots" name="checkboxSkiBoots">
                        <label class="custom-control-label" for="checkboxSkiBoots">Buty narciarskie</label>
                    </div>
                    <div class="custom-control custom-checkbox col-6 col-lg-2 text-left text-lg-center">
                        <input type="checkbox" class="custom-control-input " id="checkboxSnowboard" value="snowboard" name="checkboxSnowboard">
                        <label class="custom-control-label" for="checkboxSnowboard">Snowboard</label>
                    </div>
                    <div class="custom-control custom-checkbox col-6 col-lg-3 text-left text-lg-center">
                        <input type="checkbox" class="custom-control-input " id="checkboxSnowboardBoots" value="snowboardBoots" name="checkboxSnowboardBoots">
                        <label class="custom-control-label" for="checkboxSnowboardBoots">Buty snowboardowe</label>
                    </div>
                    <div class="custom-control custom-checkbox col-6 col-lg-2 text-left text-lg-center">
                        <input type="checkbox" class="custom-control-input " id="checkboxHelmet" value="helmet" name="checkboxHelmet">
                        <label class="custom-control-label" for="checkboxHelmet">Kask</label>
                    </div>
                </div>
            </article>
            <div id="equipmentHireData">
                <article hidden="true" id="ski">
                    <h5 class="pt-4 ">Narty</h5>
                    <div class="row">
                        <div class="col-sm-3 mx-auto">
                            <label for="skiList">ID sprzętu</label>
                            <select class="custom-select" id="skiList" name="skiId"> 
                                <option disabled selected></option>
                            </select>
                        </div>
                        <div class="col-sm-6 mx-auto">
                            <label for="skiListName">Nazwa</label>
                            <input type="text" readonly="true" class="form-control disabled" id="skiListName"/>
                        </div>
                        <div class="col-sm-3 mx-auto">
                            <label for="skiListSize">Rozmiar</label>
                            <input type="text" readonly="true" class="form-control disabled" id="skiListSize"/>
                        </div>
                    </div>
                </article>
                <article hidden="true" id="skiBoots">
                    <h5 class="pt-4 ">Buty narciarskie</h5>
                    <div class="row">
                        <div class="col-sm-3 mx-auto">
                            <label for="skiBootsList">ID sprzętu</label>
                            <select class="custom-select" id="skiBootsList" name="skiBootsId"> 
                                <option disabled selected></option>
                            </select>
                        </div>
                        <div class="col-sm-6 mx-auto">
                            <label for="skiBootsListName">Nazwa</label>
                            <input type="text" readonly="true" class="form-control disabled" id="skiBootsListName"/>
                        </div>
                        <div class="col-sm-3 mx-auto">
                            <label for="skiBootsListSize">Rozmiar</label>
                            <input type="text" readonly="true" class="form-control disabled" id="skiBootsListSize"/>
                        </div>
                    </div>
                </article>
                <article hidden="true" id="snowboard">
                    <h5 class="pt-4 ">Snowboard</h5>
                    <div class="row">
                        <div class="col-sm-3 mx-auto">
                            <label for="snowboardList">ID sprzętu</label>
                            <select class="custom-select" id="snowboardList" name="snowboardId"> 
                                <option disabled selected></option>
                            </select>
                        </div>
                        <div class="col-sm-6 mx-auto">
                            <label for="snowboardListName">Nazwa</label>
                            <input type="text" readonly="true" class="form-control disabled" id="snowboardListName"/>
                        </div>
                        <div class="col-sm-3 mx-auto">
                            <label for="snowboardListSize">Rozmiar</label>
                            <input type="text" readonly="true" class="form-control disabled" id="snowboardListSize"/>
                        </div>
                    </div>
                </article>
                <article hidden="true" id="snowboardBoots">
                    <h5 class="pt-4 ">Buty snowboardowe</h5>
                    <div class="row">
                        <div class="col-sm-3 mx-auto">
                            <label for="snowboardBootsList">ID sprzętu</label>
                            <select class="custom-select" id="snowboardBootsList" name="snowboardBootsId"> 
                                <option disabled selected></option>
                            </select>
                        </div>
                        <div class="col-sm-6 mx-auto">
                            <label for="snowboardBootsListName">Nazwa</label>
                            <input type="text" readonly="true" class="form-control disabled" id="snowboardBootsListName"/>
                        </div>
                        <div class="col-sm-3 mx-auto">
                            <label for="snowboardBootsListSize">Rozmiar</label>
                            <input type="text" readonly="true" class="form-control disabled" id="snowboardBootsListSize"/>
                        </div>
                    </div>
                </article>
                <article hidden="true" id="helmet">
                    <h5 class="pt-4 ">Kask</h5>
                    <div class="row">
                        <div class="col-sm-3 mx-auto">
                            <label for="helmetList">ID sprzętu</label>
                            <select class="custom-select" id="helmetList" name="helmetId"> 
                                <option disabled selected></option>
                            </select>
                        </div>
                        <div class="col-sm-6 mx-auto">
                            <label for="helmetListName">Nazwa</label>
                            <input type="text" readonly="true" class="form-control disabled" id="helmetListName"/>
                        </div>
                        <div class="col-sm-3 mx-auto">
                            <label for="helmetListSize">Rozmiar</label>
                            <input type="text" readonly="true" class="form-control disabled" id="helmetListSize"/>
                        </div>
                    </div>
                </article>
                <article class="col-12 row pt-5" hidden="true" id="addHireButtons">
                    <button type="submit" name="submit" value="addHire" class="btn btn-success col-4 col-md-3 mx-auto">Potwierdź</button>
                    <button type="reset" class="btn btn-warning text-light col-4 col-md-3 mx-auto" id="addHireResetBTN">Wyczyść</button>
                </article>
            </div>
        </div>
    </form>
</section>

<section class="pb-5 text-center bg-light">
    <h4>Trwające wypożyczenia</h4>
    <div id="activeHireContainer"></div>
</section>
