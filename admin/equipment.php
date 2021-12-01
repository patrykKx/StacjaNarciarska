<?php
    session_start();
    $text = ''; $color = '';
    if(isset($_SESSION['addEquipment'])) {
        if($_SESSION['addEquipment'] == true) {
            $text = 'Pomyślnie dodałeś sprzęt!';
            $color = 'success';
        }
        else {
            $text = 'Wystąpił błąd podczas dodania sprzętu!';  
            $color = 'danger';
        } 
        unset($_SESSION['addEquipment']);
    }
    else if(isset($_SESSION['editEquipment'])) {
        if($_SESSION['editEquipment'] == true) {
            $text = 'Pomyślnie edytowałeś sprzęt!';
            $color = 'success';
        }
        else {
            $text = 'Wystąpił błąd podczas edycji sprzętu!';  
            $color = 'danger';
        } 
        unset($_SESSION['editEquipment']);
    }
    else if(isset($_SESSION['equipmentValidation'])) {
        $text = $_SESSION['equipmentValidation'];
        $color = 'danger';
        unset($_SESSION['equipmentValidation']);
    }
    
    if($text != '' && $color != '') {
        echo '<h5 class="alert alert-'.$color.' alert-dismissible fade show pb-2 text-center" role="alert">'.$text.
                 '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.
              '</h5>';
    }
?>

<section class="text-center">
    <h4 id="firstBanner">Dodaj nowy sprzęt</h4>   
    <form method="POST" action="controller.php">
        <div class="form-group row pt-2 mb-2" id="equipmentInputs">
            <div class="col-sm-3 mx-auto">
                <label for="typeEquipment">Wybierz rodzaj</label>
                <select class="custom-select" name="type" id="typeEquipment"> 
                    <option disabled selected></option>
                    <option value="Narty">Narty</option>
                    <option value="Snowboard">Snowboard</option>
                    <option value="Buty narciarskie">Buty narciarskie</option>
                    <option value="Buty snowboardowe">Buty snowboardowe</option>
                    <option value="Kask">Kask</option>
                </select>
                <?php
                    if(isset($_SESSION['rememberEquipmentType'])) {
                        ?>
                        <script>
                            document.getElementById('typeEquipment').value = <?php echo '"'.$_SESSION['rememberEquipmentType'].'"'?>;
                        </script>
                        <?php
                        unset($_SESSION['rememberEquipmentType']);
                    }
                ?>
            </div> 
            <div class="col-sm-2 mx-auto">
                <label for="idEquipment">Identyfikator</label>
                <div class="input-group-prepend" id="allEquipmentId">
                    <input class="input-group-text" id="prefixIdEquipment" name="prefixId" style="max-width: 46px;"
                           value="<?php
                                    if(isset($_SESSION['rememberEquipmentPrefixId'])) {
                                        echo $_SESSION['rememberEquipmentPrefixId'];
                                        unset($_SESSION['rememberEquipmentPrefixId']);
                                    }
                                  ?>"/>
                    <input type="number" min="1" class="form-control" id="idEquipment" name="id" 
                           value="<?php
                                    if(isset($_SESSION['rememberEquipmentId'])) {
                                        echo $_SESSION['rememberEquipmentId'];
                                        unset($_SESSION['rememberEquipmentId']);
                                    }
                                  ?>"/>
                </div>
            </div> 
            <div class="col-sm-4 mx-auto">
                <label for="nameEquipment">Nazwa</label>
                <input type="text" class="form-control" id="nameEquipment" name="name" 
                       value="<?php
                                    if(isset($_SESSION['rememberEquipmentName'])) {
                                        echo $_SESSION['rememberEquipmentName'];
                                        unset($_SESSION['rememberEquipmentName']);
                                    }
                              ?>"/>
            </div> 
        </div>
        <div class="form-group row mb-2">
            <div class="col-sm-3  ">
                <label for="sizeEquipment">Rozmiar</label>
                <input type="number" class="form-control" id="sizeEquipment" name="size" 
                       value="<?php
                                    if(isset($_SESSION['rememberEquipmentSize'])) {
                                        echo $_SESSION['rememberEquipmentSize'];
                                        unset($_SESSION['rememberEquipmentSize']);
                                    }
                              ?>"/>
            </div> 
            <div class="col-sm-9  ">
                <label for="photoURL">Link do zdjęcia</label>
                <input type="url" class="form-control" id="photoURL" name="photoURL" 
                       value="<?php
                                    if(isset($_SESSION['rememberEquipmentURL'])) {
                                        echo $_SESSION['rememberEquipmentURL'];
                                        unset($_SESSION['rememberEquipmentURL']);
                                    }
                             ?>"/>
            </div>             
        </div>
        <div class="row pt-4">
            <button type="submit" name="submit" value="addEquipment" class="btn btn-success col-4 col-md-3 mx-auto" id="submitEquipmentBTN">Potwierdź</button>
            <button type="reset" class="btn btn-warning text-light col-4 col-md-3 mx-auto" id="resetEquipmentBTN">Wyczyść</button>
        </div>
    </form>
</section>

<section class="text-center mt-4">
    <h4>Lista sprzętu</h4>
    <div class="py-2" id="equipmentTypeRadios">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="skiRadio" value="Narty">
            <label class="form-check-label" for="skiRadio">Narty</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="snowboardRadio" value="Snowboard">
            <label class="form-check-label" for="snowboardRadio">Snowboard</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="skiBootsRadio" value="Buty narciarskie">
            <label class="form-check-label" for="skiBootsRadio">Buty narciarskie</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="snowboardBootRadio" value="Buty snowboardowe">
            <label class="form-check-label" for="snowboardBootRadio">Buty snowboardowe</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="helmetRadio" value="Kask">
            <label class="form-check-label" for="helmetRadio">Kaski</label>
        </div>
    </div>
    <img src="images/snowspinner.gif" alt="alt" id="equipmentListSpinner" style="display:none;position:absolute;left:48%;"/>
    
    <div class="py-3" id="equipmentList">
        
    </div>
</section>
             