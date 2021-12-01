<?php

class Form {
    public static function loggedPersonNav() {
        ?>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="nav">
            <a class="navbar-brand text-light" href="controller.php"><img src="images/pictogram.png" width="50" height="50" class="mr-1" alt="Logo">Stacja Narciarska</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
              <ul class="navbar-nav">
                <li class="nav-item">
                  <a class="nav-link" href="forecast.php">Pogoda</a>
                </li>
                
                <li class="nav-item row px-3">
                    <a class="nav-link" id="messageLink" href="chat.php">Wiadomości</a>
                    <p class="rounded-circle bg-danger d-inline-block text-center" id="messagesCounterContainer"></p>
                </li>
                
                <li class="nav-item">
                  <a class="nav-link" href="accountSettings.php">Ustawienia konta</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="logout.php">Wyloguj się</a>
                </li>
              </ul>
            </div>
        </nav>
        <?php
    }
    
    public static function basicHead($title) {
        ?>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <meta name="description" content="Strona internetowa Stacji Narciarskiej">
        <meta name="keywords" content="stacja narciarska, stok narciarski, narty, snowboard, serwis, wypożyczalnia, zima">
        <meta name="author" content="PK, AK">

        <title><?php echo $title; ?></title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Lato:ital@1&display=swap" rel="stylesheet"> 
        <?php
    }
    
    public static function mainForm() {
        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <?php self::basicHead("Stacja Narciarska") ?>
            </head>
            <body>
                <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="nav">
                    <a class="navbar-brand text-light" href="#" id="linkStacja"><img src="images/pictogram.png" width="50" height="50" class="mr-1" alt="Logo">Stacja Narciarska</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                      <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse row" id="mainNavbar">
                      <ul class="navbar-nav col-lg-7 ml-lg-2" id="navUL">
                        <li class="nav-item">
                          <a class="nav-link py-1" href="#" id="linkKalendarz">Kalendarz</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link py-1" href="#" id="linkWarunki">Warunki</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link py-1" href="#" id="linkPogoda">Pogoda</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link py-1" href="#" id="linkCennik">Cennik</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link py-1" href="#" id="linkRegulamin">Regulamin</a>
                        </li>
                      </ul>
                      <ul class="navbar-nav col-lg-4 justify-content-lg-end font-weight-bolder">
                        <li class="nav-item ">
                          <a class="nav-link text-primary mr-lg-3 py-1" href="loginPage.php" id="loginUL">Logowanie</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link text-success py-1" href="registerPage.php" id="registerUL">Rejestracja</a>
                        </li> 
                      </ul>
                    </div>
                </nav>
                
                <div class="container-fluid firstBgColor" id="container">
                    <section class="py-3 px-sm-5 firstBgColor" id="sectionWelcome"> 
                        <h2 class="text-dark text-center p-sm-3" id="heading">Witaj na stronie Stacji Narciarskiej</h2>

                        <div id="carouselExampleInterval" class="col-xl-8 col-lg-10 mx-auto carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                              <div class="carousel-item active myPhoto" data-interval="5000">
                                  <img src="images/skiing2.jpg" class="img-fluid" alt="...">
                              </div>
                              <div class="carousel-item" data-interval="5000">
                                  <img src="images/snowboard2.jpg" class="img-fluid" alt="...">
                              </div>
                              <div class="carousel-item" data-interval="5000">
                                  <img src="images/serwis.jpg" class="img-fluid" alt="...">
                              </div>
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleInterval" role="button" data-slide="prev">
                              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                              <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleInterval" role="button" data-slide="next">
                              <span class="carousel-control-next-icon" aria-hidden="true"></span>
                              <span class="sr-only">Next</span>
                            </a>
                            <p class="py-2 text-center" id="quote">
                                <q>Kiedy pierwszy raz poczujesz zjazd, Twoje życie zmieni się na zawsze</q><br/> Warren Miller
                            </p>
                        </div>
                        
                        <div class="row text-center" id="loginIncentive">
                            <div class="col-md-6">
                                <p>Jesteś użytkownikiem?</p>
                                <a href="loginPage.php" class="col-10 col-lg-6 btn btn-primary">Zaloguj się</a>
                            </div>
                            <div class="col-md-6 mt-4 mt-md-0">
                                <p>Nie masz jeszcze konta?</p>
                                <a href="registerPage.php" class="col-10 col-lg-6 btn btn-success">Przejdź do rejestracji</a>
                            </div>   
                        </div>
                    </section>
                    
                    <section class="py-3 text-center bg-light" id="sectionKalendarz">
                        <h3 class="p-3">Kalendarz</h3>
                        <div class="col-12 col-sm-8 mx-auto pt-3" id="calendarContainer">
                            <div  id="calendarHeader"></div>
                            <div id="calendarBody"></div>
                        </div>
                        <div class="row" id="dayDescription">
                            <div class="col-6 py-1" id="day">&nbsp;</div>
                            <div class="col-6 py-1" id="isOpen">&nbsp;</div>
                            <div class="col-6 " id="startHour">&nbsp;</div>
                            <div class="col-6 " id="finishHour">&nbsp;</div>
                        </div>
                    </section>
                    
                    <section class="py-3 text-center bg-white" id="sectionWarunki">
                        <h3 class="p-3">Warunki</h3>
                        <div id="currentConditions"></div>
                    </section>

                    <section class="py-3 text-center" style="background-color: #c1e1ec;" id="sectionPogoda">
                        <h3 class="p-3">Aktualna pogoda</h3>
                        <h5 id="errorLoadingWeather"></h5>
                        <p class="row" id="weatherData"></p>
                    </section>

                    <section class="py-3 text-center bg-light" id="sectionCennik">
                        <h3 class="p-3">Cennik</h3>
                        <div id="currentTariff"></div>    
                    </section>

                    <section class="py-3 text-center firstBgColor" id="sectionRegulamin">
                        <h3 class="p-3">Regulamin</h3>
                        <p>1. Wzgląd na inne osoby<br/>
                        Każdy narciarz lub snowboarder powinien zachować się w taki sposób, aby nie stwarzać niebezpieczeństwa ani szkody dla innej osoby.
                        </p>
                        <p>2. Sposób jazdy na nartach i snowboardzie oraz panowanie nad prędkością<br/>
                        Każdy narciarz lub snowboarder powinien kontrolować sposób jazdy. Powinien on dostosować szybkość i sposób jazdy do swoich umiejętności, rodzaju i stanu trasy, warunków atmosferycznych oraz natężenia ruchu.
                        </p>
                        <p>3. Wybór kierunku jazdy<br/>
                        Narciarz lub snowboarder nadjeżdżający od tyłu musi wybrać taki tor jazdy, aby nie spowodować zagrożenia dla narciarzy lub snowboarderów znajdującym się przed nim.
                        </p>
                        <p>4. Wyprzedzanie<br/>
                        Narciarz lub snowboarder może wyprzedzać innego narciarza lub snowboardera z góry i z dołu, z prawej i z lewej strony pod warunkiem, że zostawi wystarczająco dużo przestrzeni wyprzedzanemu narciarzowi lub snowboarderowi na wykonanie przez niego wszelkich zamierzonych lub niezamierzonych manewrów.
                        </p>
                        <p>5. Ruszanie z miejsca i poruszanie się w górę stoku<br/>
                        Narciarz lub snowboarder, wjeżdżając na oznakowaną drogę zjazdu, ponownie ruszając po zatrzymaniu się, czy też poruszając się w górę stoku, musi spojrzeć i w górę, i w dół stoku, aby upewnić się, że może to uczynić bez zagrożenia dla siebie i innych.
                        </p>
                        <p>6. Zatrzymanie na trasie<br/>
                        O ile nie jest to absolutne konieczne, narciarz lub snowboarder musi unikać zatrzymania się na trasie zjazdu w miejscach zwężeń i miejscach o ograniczonej widoczności. Po upadku w takim miejscu narciarz lub snowboarder winien usunąć się z toru jazdy możliwie jak najszybciej.
                        </p>
                        <p>7. Podchodzenie i schodzenie na nogach<br/>
                        Narciarz lub snowboarder musi podchodzić lub schodzić na nogach wyłącznie skrajem trasy.
                        </p>
                        <p>8. Przestrzeganie znaków narciarskich<br/>
                        Każdy narciarz lub snowboarder winien stosować się do znaków narciarskich i oznaczeń tras.
                        </p>
                        <p>9. Wypadki<br/>
                        W razie wypadku każdy narciarz lub snowboarder winien udzielić poszkodowanym pomocy.
                        </p>
                        <p>10. Obowiązek ujawnienia tożsamości<br/>
                        Każdy narciarz, snowboarder, obojętnie czy sprawca wypadku, poszkodowany czy świadek musi w razie wypadku podać swoje dane osobowe.
                        </p>
                    </section>
                </div>

                <script src="js/jquery/jquery-3.5.1.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
                <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
                <script src="js/jquery/jquery.scrollTo.min.js"></script>
                <script src="js/currentWeather.js"></script>
                <script src="js/myCalendar.js"></script>
                <script src="js/index.js"></script> 
                <script>
                    jQuery(function($) {                              
                        $.scrollTo(0);
                        $('#linkStacja').click(function() { $.scrollTo(0,800); } );
                        $('#linkKalendarz').click(function() { $.scrollTo($('#sectionKalendarz').offset().top-$('#nav').innerHeight(),800); } );
                        $('#linkWarunki').click(function() { $.scrollTo($('#sectionWarunki').offset().top-$('#nav').innerHeight(),900); } );
                        $('#linkPogoda').click(function() { $.scrollTo($('#sectionPogoda').offset().top-$('#nav').innerHeight(),1000); } );
                        $('#linkCennik').click(function() { $.scrollTo($('#sectionCennik').offset().top-$('#nav').innerHeight(),1100); } );
                        $('#linkRegulamin').click(function() { $.scrollTo($('#sectionRegulamin').offset().top-$('#nav').innerHeight(),1200); } );       
                    });
                    $(window).scroll(function() {
                        if($(this).scrollTop() >= $('#sectionKalendarz').offset().top - 300  && $(this).scrollTop() < $('#sectionWarunki').offset().top - 300) {
                            $('#linkKalendarz').addClass("active");
                        }
                        else $('#linkKalendarz').removeClass("active");
                        
                        if($(this).scrollTop() >= $('#sectionWarunki').offset().top - 300  && $(this).scrollTop() < $('#sectionPogoda').offset().top - 300) {
                            $('#linkWarunki').addClass("active");
                        }
                        else $('#linkWarunki').removeClass("active");

                        if($(this).scrollTop() >= $('#sectionPogoda').offset().top - 300  && $(this).scrollTop() < $('#sectionCennik').offset().top - 300) {
                            $('#linkPogoda').addClass("active");
                        }
                        else $('#linkPogoda').removeClass("active");

                        if($(this).scrollTop() >= $('#sectionCennik').offset().top - 300  && $(this).scrollTop() < $('#sectionRegulamin').offset().top - 300) {
                            $('#linkCennik').addClass("active");
                        }
                        else $('#linkCennik').removeClass("active");

                        if($(this).scrollTop() >= $('#sectionRegulamin').offset().top - 300) {
                            $('#linkRegulamin').addClass("active");
                        }
                        else $('#linkRegulamin').removeClass("active");
                    });    
                </script>
            </body>
        </html>
        <?php
    }
    
    public static function loginForm() {
        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <?php self::basicHead("Stacja Narciarska - Logowanie") ?>
                <link rel="stylesheet" type="text/css" href="css/loginAndRegister.css">
            </head>
            
            <body class="firstBgColor">
                <nav class="navbar navbar-dark bg-dark fixed-top" id="nav">
                    <a class="navbar-brand text-light" href="index.php" id="linkStacja"><img src="images/pictogram.png" width="50" height="50" class="mr-1" alt="Logo">Stacja Narciarska</a>      
                </nav>               
                
                <section class="pt-5 text-center mx-auto" > 
                    <div class="mx-auto pt-2" id="loginForm">
                        ?>
                        <?php
                            if(isset($_SESSION['registrationSuccess'])) {
                                if($_SESSION['registrationSuccess'] == true) {
                                    $text = "Pomyślnie założyłeś konto! Możesz teraz zalogować się po raz pierwszy.";
                                    $color = "success";
                                    echo '<h5 class="col-sm-6 mx-auto alert alert-'.$color.' alert-dismissible fade show pb-2 text-center" role="alert">'.$text.
                                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.
                                         '</h5>';
                                }
                                unset($_SESSION['registrationSuccess']);
                            }
                        ?>
                        <form method="POST" action="controller.php">
                            <div class="form-group font-weight-bolder">
                                <h5 class="pt-2 text-primary">Login</h5>
                                <input class="col-10 col-sm-4 mb-2 " type="text" name="loginL"/>            
                                <h5 class="pt-2 text-primary">Hasło</h5>             
                                <input class="col-10 col-sm-4 mb-1" type="password" name="passL"/>
                                <div class="col-10 mx-auto loginError">   &nbsp;
                                    <?php 
                                        if(isset($_SESSION['loginFail'])) {
                                            echo $_SESSION['loginFail']; 
                                            unset($_SESSION['loginFail']); 
                                        }
                                    ?>
                                </div>
                            </div>
                            <button type="submit" value="login" name="submit" class="col-8 col-sm-3 my-3 my-sm-1 mx-sm-2 btn btn-success text-light">Zaloguj się</button>
                            <button type="reset" class="col-8 col-sm-3 my-3 my-sm-1 mx-sm-2 btn btn-warning text-light">Wyczyść</button>
                        </form>
                    </div>     
                </section>
                
                <script src="js/jquery/jquery-3.5.1.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
                <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
            </body>
        </html>
        <?php
    }
    
    public static function registerForm() {
        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <?php self::basicHead("Stacja Narciarska - Rejestracja") ?>
                <link rel="stylesheet" type="text/css" href="css/loginAndRegister.css">
            </head>
            
            <body class="registerBackground">
                <nav class="navbar navbar-dark bg-dark fixed-top" id="nav">
                    <a class="navbar-brand text-light" href="index.php" id="linkStacja"><img src="images/pictogram.png" width="50" height="50" class="mr-1" alt="Logo">Stacja Narciarska</a>      
                </nav>
                
                <section class="pt-5 text-center mx-auto" >    
                    <div class="mx-auto pt-5 pb-2" id="registerForm">
                        <div class="row">
                            <div class="col-lg-4 mx-auto"></div>
                            <h2 class="col-lg-6" id="registerString">Rejestracja</h2>
                        </div>
                        <form method="POST" action="controller.php">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-4 mx-auto"></div>
                                    <input class="col-8 col-sm-6 col-lg-4 mx-auto border-primary" type="text" name="firstnameR" placeholder="Imię" id="firstnameInput"
                                           value="<?php 
                                                    if(isset($_SESSION['rememberFirstname'])) {
                                                        echo $_SESSION['rememberFirstname']; 
                                                        unset($_SESSION['rememberFirstname']); 
                                                    }
                                                  ?>"/>                                    
                                </div>
                                <div class="row pb-lg-2">
                                    <div class="col-lg-6 mx-auto"></div>
                                    <div class="col-sm-12 col-lg-6 text-danger font-weight-bolder validationError">
                                      &nbsp;
                                    <?php
                                    if(isset($_SESSION['firstnameValidation'])) {
                                        echo $_SESSION['firstnameValidation'];
                                        unset($_SESSION['firstnameValidation']);
                                    }
                                    ?>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-4 mx-auto"></div>
                                    <input class="col-8 col-sm-6 col-lg-4 mx-auto border-primary" type="text" name="surnameR" placeholder="Nazwisko" id="surnameInput"
                                           value="<?php 
                                                    if(isset($_SESSION['rememberSurname'])) {
                                                        echo $_SESSION['rememberSurname']; 
                                                        unset($_SESSION['rememberSurname']); 
                                                    }
                                                  ?>"/>
                                </div>
                                <div class="row pb-lg-2">
                                    <div class="col-lg-6 mx-auto"></div>
                                    <div class="col-sm-12 col-lg-6 text-danger font-weight-bolder validationError">
                                      &nbsp;    
                                    <?php
                                    if(isset($_SESSION['surnameValidation'])) {
                                        echo $_SESSION['surnameValidation'];
                                        unset($_SESSION['surnameValidation']);
                                    }
                                    ?>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-4 mx-auto"></div>
                                    <input class="col-8 col-sm-6 col-lg-4 mx-auto border-info" placeholder="Data urodzenia" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" name="birthdateR" id="birthdateInput" min="1900-01-01" max=""
                                           value="<?php 
                                                    if(isset($_SESSION['rememberBirthdate'])) {
                                                        echo $_SESSION['rememberBirthdate']; 
                                                        unset($_SESSION['rememberBirthdate']); 
                                                    }
                                                  ?>"/>
                                </div> 
                                <div class="row pb-lg-2">
                                    <div class="col-lg-6 mx-auto"></div>
                                    <div class="col-sm-12 col-lg-6 text-danger font-weight-bolder validationError">
                                      &nbsp;    
                                    <?php
                                    if(isset($_SESSION['birthdateValidation'])) {
                                        echo $_SESSION['birthdateValidation'];
                                        unset($_SESSION['birthdateValidation']);
                                    }
                                    ?>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-4 mx-auto"></div>        
                                    <input class="col-8 col-sm-6 col-lg-4 mx-auto border-info" type="text" name="emailR" placeholder="Email" id="emailInput"
                                           value="<?php 
                                                    if(isset($_SESSION['rememberEmail'])) {
                                                        echo $_SESSION['rememberEmail']; 
                                                        unset($_SESSION['rememberEmail']); 
                                                    }
                                                  ?>"/>
                                </div> 
                                <div class="row pb-lg-2">
                                    <div class="col-lg-6 mx-auto"></div>
                                    <div class="col-sm-12 col-lg-6 text-danger font-weight-bolder validationError">
                                      &nbsp;    
                                    <?php
                                    if(isset($_SESSION['emailValidation'])) {
                                        echo $_SESSION['emailValidation'];
                                        unset($_SESSION['emailValidation']);
                                    }
                                    ?>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-4 mx-auto"></div>    
                                    <input class="col-8 col-sm-6 col-lg-4 mx-auto border-info" type="text" name="loginR" placeholder="Login" id="loginInput"
                                           value="<?php 
                                                    if(isset($_SESSION['rememberLogin'])) {
                                                        echo $_SESSION['rememberLogin']; 
                                                        unset($_SESSION['rememberLogin']); 
                                                    }
                                                  ?>"/>
                                </div> 
                                <div class="row pb-lg-2">
                                    <div class="col-lg-6 mx-auto"></div>
                                    <div class="col-sm-12 col-lg-6 text-danger font-weight-bolder validationError">
                                      &nbsp;
                                    <?php
                                    if(isset($_SESSION['loginValidation'])) {
                                        echo $_SESSION['loginValidation'];
                                        unset($_SESSION['loginValidation']);
                                    }
                                    ?>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-4 mx-auto"></div>        
                                    <input class="col-8 col-sm-6 col-lg-4 mx-auto border-danger" type="password" name="passwordR" placeholder="Hasło" id="passInput"
                                           value="<?php 
                                                    if(isset($_SESSION['rememberPass'])) {
                                                        echo $_SESSION['rememberPass']; 
                                                        unset($_SESSION['rememberPass']); 
                                                    }
                                                  ?>"/>
                                </div>  
                                <div class="row pb-lg-2">
                                    <div class="col-lg-6 mx-auto"></div>
                                    <div class="col-sm-12 col-lg-6 text-danger font-weight-bolder validationError">
                                      &nbsp;    
                                    <?php
                                    if(isset($_SESSION['passwordValidation'])) {
                                        echo $_SESSION['passwordValidation'];
                                        unset($_SESSION['passwordValidation']);
                                    }
                                    ?>
                                    </div>
                                </div>
                                
                                <div class="row pb-3 pb-lg-5">
                                    <div class="col-lg-4 mx-auto"></div>    
                                    <input class="col-8 col-sm-6 col-lg-4 mx-auto border-danger" type="password" name="password2R" placeholder="Powtórz hasło" id="pass2Input"
                                           value="<?php 
                                                    if(isset($_SESSION['rememberPass2'])) {
                                                        echo $_SESSION['rememberPass2']; 
                                                        unset($_SESSION['rememberPass2']); 
                                                    }
                                                  ?>"/>
                                </div> 
                                
                                <div class="row">
                                    <div class="col-lg-6"></div>
                                    <button type="submit" value="register" name="submit" class="col-8 col-sm-4 col-md-2 mt-3 mt-xl-0 mr-lg-2 mx-auto btn btn-success text-light">Zarejestruj się</button>
                                    <button type="reset" class="col-8 col-sm-4 col-md-2 mt-3 mt-xl-0 ml-lg-2 mx-auto btn btn-warning text-light" id="resetBTN">Wyczyść</button>
                                </div>
                            </div>     
                        </form>    
                    </div>
                </section>
                     
                <script src="js/jquery/jquery-3.5.1.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/ulg/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
                <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
                <script src="js/registerPage.js"></script>            
            </body>
        </html>
        <?php
    }
    
    public static function adminForm() {
        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <?php self::basicHead("Stacja Narciarska - Admin") ?>
            </head>
            <body>
                <?php self::loggedPersonNav(); ?>
                
                <div class="container bg-light">
                    <header class="pt-2 pb-4 text-center">
                        <h3 class="pt-2">Panel administratora</h3>
                        <nav class="navbar navbar-light bg-light text-center" id="navbarNav2">
                            <div class="row col-12 justify-content-center">
                                <button class="btn btn-outline-primary col-5 col-md-2 m-1 m-md-0" type="button" id="station">Stacja</button>
                                <button class="btn btn-outline-primary col-5 col-md-2 m-1 m-md-0" type="button" id="workers">Pracownicy</button>
                                <button class="btn btn-outline-primary col-5 col-md-2 m-1 m-md-0" type="button" id="timetable">Dyżury</button>
                                <button class="btn btn-outline-primary col-5 col-md-2 m-1 m-md-0" type="button" id="users">Użytkownicy</button>
                                <button class="btn btn-outline-primary col-5 col-md-2 m-1 m-md-0" type="button" id="equipment">Sprzęt</button>
                                <button class="btn btn-outline-primary col-5 col-md-2 m-1 m-md-0" type="button" id="statistic">Statystyki</button>
                            </div>
                        </nav>                      
                    </header>
                        
                    <img src="images/snowspinner.gif" alt="alt" id="spinner" style="display:none;position:absolute;left:48%;"/>
                    <div id="mainSection">
                        
                    </div>
                </div>
                
                <!-- Informacja po kliknięciu przycisku Usuń -->
                <div id="modalContainer"></div>
                
                <script src="js/jquery/jquery-3.5.1.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
                <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
                <script src="js/jquery/jquery.scrollTo.min.js"></script>
                <script src="https://www.gstatic.com/charts/loader.js"></script>
                <script src="js/checkSessionStatus.js"></script>
                <script src="js/myCalendar.js"></script>
                <script src="js/admin.js"></script>
                <script src="js/admin/station.js"></script>
                <script src="js/admin/timetable.js"></script>
                <script src="js/admin/workers.js"></script>
                <script src="js/admin/users.js"></script>
                <script src="js/admin/equipment.js"></script>
                <script src="js/admin/statistic.js"></script>
                <script src="js/messageHeaderListener.js"></script>    
            </body>
        </html>
        <?php
    }
    
    public static function forecastForm() {
        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <?php self::basicHead("Stacja Narciarska - Pogoda") ?>
                <link rel="stylesheet" type="text/css" href="css/forecast.css"> 
            </head>
            <body>
                <?php self::loggedPersonNav(); ?>
                <img src="images/snowspinner.gif" alt="alt" id="spinner" style="display:none;position:absolute;left:48%;top:250px;"/>           
                <section class="py-3 text-center bg-light" id="sectionWeather">
                    <h3>Aktualna pogoda</h3>
                    <h5 id="errorLoadingWeather"></h5>
                    <p class="row" id="weatherData"></p>
                </section>
                
                <section class="container-fluid text-center pt-3">                 
                    <h3>Prognoza pogody</h3>
                    <div id="forecastData"></div>
                </section>
                
                <script src="js/jquery/jquery-3.5.1.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
                <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
                <script src="js/checkSessionStatus.js"></script>
                <script src="js/currentWeather.js"></script>
                <script src="js/advancedForecast.js"></script>
                <script src="js/messageHeaderListener.js"></script>
            </body>
        </html>
        <?php
    }
    
    public static function accountSettingsForm() {
        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <?php self::basicHead("Stacja Narciarska - Ustawienia") ?> 
            </head>
            <body>
                <?php self::loggedPersonNav(); ?>
                <img src="images/snowspinner.gif" alt="alt" id="spinner" style="display:none;position:absolute;left:48%;top:150px;"/>           
                <section class="container-fluid text-center bg-light pb-5">                 
                    <h3 class="py-3">Twoje dane</h3>
                    <?php
                        if(isset($_SESSION['getAccountDataError'])) {echo '<h4 class="text-danger">'.$_SESSION['firstname'].'</h4>'; unset($_SESSION['firstname']);}
                        else if(isset($_SESSION['editPassword']) && $_SESSION['editPassword'] == true) {echo '<h4 class="text-success">Hasło zostało zmienione!</h4>'; unset($_SESSION['editPassword']);}
                        else if(isset($_SESSION['editPassword']) && $_SESSION['editPassword'] == false) {echo '<h4 class="text-danger">Wystąpił błąd podczas zmiany hasła!</h4>'; unset($_SESSION['editPassword']);}
                        else if(isset($_SESSION['editEmail']) && $_SESSION['editEmail'] == true) {echo '<h4 class="text-success">Adres e-mail został zmieniony</h4>'; unset($_SESSION['editEmail']);}
                        else if(isset($_SESSION['editEmail']) && $_SESSION['editEmail'] == false) {echo '<h4 class="text-danger">Wystąpił błąd podczas zmiany adresu e-mail!</h4>'; unset($_SESSION['editEmail']);}
                    ?>
                    <div class="row" style="font-size:125%;">
                        <div class="col-sm-6 py-2">
                            Imię:&nbsp; <b><?php if(isset($_SESSION['firstname'])) {echo $_SESSION['firstname'];} ?></b>
                        </div>
                        <div class="col-sm-6 py-2">
                            Nazwisko:&nbsp; <b><?php if(isset($_SESSION['surname'])) {echo $_SESSION['surname'];} ?></b>
                        </div>
                        <div class="col-sm-6 py-2">
                            Login:&nbsp; <b><?php if(isset($_SESSION['login'])) {echo $_SESSION['login'];} ?></b>
                        </div>
                        <div class="col-sm-6 py-2">
                            Email:&nbsp; <b><?php if(isset($_SESSION['email'])) {echo $_SESSION['email'];} ?></b>
                        </div>
                        <div class="col-sm-6 py-2">
                            Typ konta:&nbsp; <b><?php if(isset($_SESSION['email'])) {echo $_SESSION['role'];} ?></b>
                        </div>
                        <div class="col-sm-6 py-2">
                            Data utworzenia:&nbsp; <b><?php if(isset($_SESSION['email'])) {echo $_SESSION['creationDate'];} ?></b>
                        </div> 
                    </div>
                </section>
                
                <section class="container-fluid text-center bg-light">
                    <h4 class="py-3">Edycja danych</h4>
                    <div class="row">
                        <div class="col-sm-6">
                            <p><button class="btn btn-info col-8 col-sm-6" type="button" data-toggle="collapse" data-target="#collapse" aria-expanded="false" aria-controls="collapse">
                            Zmień hasło
                            </button></p>
                            <div class="collapse" id="collapse">
                              <div class="form-group card card-body" id="editPassword">
                                  <div class="pb-2">
                                    <input class="col-10 col-sm-8" type="password" placeholder="Podaj stare hasło" id="oldPass">
                                    <div class="text-danger" id="oldPassError">&nbsp;</div>
                                  </div>
                                  <div class="pb-2">
                                    <input class="col-10 col-sm-8" type="password" placeholder="Podaj nowe hasło" id="newPass" title="Hasło musi zawierać od 8 do 25 znaków, w tym przynajmniej jedną wielką literę, jedną małą literę i jedną cyfrę, a ponadto nie może mieć polskich znaków ani spacji.">
                                    <div class="text-danger" id="newPassError">&nbsp;</div>
                                  </div>
                                  <div class="pb-4">
                                    <input class="col-10 col-sm-8" type="password" placeholder="Powtórz nowe hasło" id="newPass2">
                                  </div>
                                  <div class="row">
                                    <button class="btn btn-outline-success col-8 col-md-4 mx-auto" id="submitEditPassword">Potwierdź</button>
                                    <button class="btn btn-outline-danger col-8 col-md-4 mx-auto mt-3 mt-md-0" id="resetEditPassword">Reset</button>
                                  </div>
                              </div>
                            </div>
                        </div>

                        <div class="col-sm-6 pt-2 pt-sm-0">
                            <p><button class="btn btn-secondary col-8 col-sm-6" type="button" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                            Zmień adres e-mail
                            </button></p>
                            <div class="collapse" id="collapse2">
                              <div class="form-group card card-body" id="editEmail">
                                <div class="pb-2">
                                    <input class="col-10 col-sm-8" type="email" placeholder="Podaj nowy email" id="newEmail">
                                    <div class="text-danger" id="emailError">&nbsp;</div>
                                </div>
                                <div class="row">
                                    <button class="btn btn-outline-success col-8 col-md-4 mx-auto" id="submitEditEmail">Potwierdź</button>
                                    <button class="btn btn-outline-danger col-8 col-md-4 mx-auto mt-3 mt-md-0" id="resetEditEmail">Reset</button>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <script src="js/jquery/jquery-3.5.1.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
                <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
                <script src="js/checkSessionStatus.js"></script>
                <script src="js/accountSettings.js"></script>
                <script src="js/messageHeaderListener.js"></script>
            </body>
        </html>
        <?php
    }
    
    public static function userForm() {
        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <?php self::basicHead("Stacja Narciarska - Użytkownik") ?>
            </head>
            <body>
                <?php self::loggedPersonNav(); ?>
                <div class="container bg-light">
                    <header class="pt-2 pb-4 text-center">
                        <h3 class="pt-2">Panel użytkownika</h3>
                        <nav class="navbar navbar-light bg-light text-center">
                            <button class="btn btn-outline-primary col-5 col-sm-2 m-1 m-sm-0 " type="button" id="ticket">Karnety</button>
                            <button class="btn btn-outline-primary col-5 col-sm-2 m-1 m-sm-0 " type="button" id="hire">Wypożyczenia</button> 
                            <button class="btn btn-outline-primary col-5 col-sm-2 m-1 m-sm-0 " type="button" id="service">Serwis</button>
                            <button class="btn btn-outline-primary col-5 col-sm-2 m-1 m-sm-0 " type="button" id="lesson">Lekcje</button>
                            <button class="btn btn-outline-primary col-5 col-sm-2 m-1 m-sm-0 " type="button" id="statistic">Statystyki</button>
                        </nav>                      
                    </header>
                        
                    <img src="images/snowspinner.gif" alt="alt" id="spinner" style="display:none;position:absolute;left:48%;"/>
                    <div id="mainSection">
                        
                    </div>
                    
                    <div id="modalContainer"></div>
                </div>    
                <script src="js/jquery/jquery-3.5.1.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
                <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
                <script src="https://www.gstatic.com/charts/loader.js"></script>
                <script src="js/checkSessionStatus.js"></script>
                <script src="js/myCalendar.js"></script>
                <script src="js/user.js"></script>
                <script src="js/user/ticket.js"></script>
                <script src="js/user/service.js"></script>
                <script src="js/user/hire.js"></script>
                <script src="js/user/lesson.js"></script>
                <script src="js/user/statistic.js"></script>
                <script src="js/messageHeaderListener.js"></script>
            </body>
        </html>
        <?php
    }
    
    public static function instructorForm() {
        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <?php self::basicHead("Stacja Narciarska - Instruktor") ?>
            </head>
            <body>
                <?php self::loggedPersonNav(); ?>
                <div class="container bg-light">
                    <header class="pt-2 pb-4 text-center">
                        <h3 class="pt-2">Panel instruktora</h3>
                        <nav class="col-sm-8 navbar navbar-light bg-light mx-auto">
                            <button class="btn btn-outline-primary col-5 col-sm-3 m-1 m-sm-0 " type="button" id="timetable">Grafik</button>
                            <button class="btn btn-outline-primary col-5 col-sm-3 m-1 m-sm-0 " type="button" id="lesson">Lekcje</button>
                            <button class="btn btn-outline-primary col-5 col-sm-3 m-1 m-sm-0 " type="button" id="statistic">Statystyki</button>
                        </nav>                      
                    </header>
                        
                    <img src="images/snowspinner.gif" alt="alt" id="spinner" style="display:none;position:absolute;left:48%;"/>
                    <div id="mainSection">
                        
                    </div>
                    <div id="modalContainer"></div>
                </div>    
                <script src="js/jquery/jquery-3.5.1.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
                <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
                <script src="js/jquery/jquery.scrollTo.min.js"></script>
                <script src="https://www.gstatic.com/charts/loader.js"></script>
                <script src="js/checkSessionStatus.js"></script>
                <script src="js/myCalendar.js"></script>
                <script src="js/instructor.js"></script>
                <script src="js/instructor/timetable.js"></script>
                <script src="js/instructor/lesson.js"></script>
                <script src="js/instructor/statistic.js"></script>
                <script src="js/messageHeaderListener.js"></script>
            </body>
        </html>
        <?php
    }
    
    public static function servicemenForm() {
        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <?php self::basicHead("Stacja Narciarska - Serwisant") ?>
            </head>
            <body>
                <?php self::loggedPersonNav(); ?>
                <div class="container bg-light">
                    <header class="pt-2 pb-4 text-center">
                        <h3 class="pt-2">Panel serwisanta</h3>
                        <nav class="navbar navbar-light bg-light text-center">
                            <button class="btn btn-outline-primary col-5 col-sm-2 m-1 m-sm-0 " type="button" id="hire">Wypożyczenia</button>
                            <button class="btn btn-outline-primary col-5 col-sm-2 m-1 m-sm-0 " type="button" id="service">Serwis</button>
                            <button class="btn btn-outline-primary col-5 col-sm-2 m-1 m-sm-0 " type="button" id="history">Historia</button>
                            <button class="btn btn-outline-primary col-5 col-sm-2 m-1 m-sm-0 " type="button" id="timetable">Grafik</button>
                            <button class="btn btn-outline-primary col-5 col-sm-2 m-1 m-sm-0 " type="button" id="statistic">Statystyki</button>
                        </nav>                      
                    </header>
                        
                    <img src="images/snowspinner.gif" alt="alt" id="spinner" style="display:none;position:absolute;left:48%;"/>
                    <div id="mainSection">
                        
                    </div>
                </div>    
                <script src="js/jquery/jquery-3.5.1.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
                <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
                <script src="js/jquery/jquery.scrollTo.min.js"></script>
                <script src="https://www.gstatic.com/charts/loader.js"></script>
                <script src="js/checkSessionStatus.js"></script>
                <script src="js/myCalendar.js"></script>
                <script src="js/servicemen.js"></script>
                <script src="js/servicemen/service.js"></script>
                <script src="js/servicemen/history.js"></script>
                <script src="js/servicemen/hire.js"></script>
                <script src="js/servicemen/timetable.js"></script>
                <script src="js/servicemen/statistic.js"></script>
                <script src="js/messageHeaderListener.js"></script>
            </body>
        </html>
        <?php
    }
    
    public static function technicianForm() {
        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <?php self::basicHead("Stacja Narciarska - Pracownik techniczny") ?> 
            </head>
            <body>
                <?php self::loggedPersonNav(); ?>
                
                <div class="container bg-light">
                    <header class="pt-2 pb-4 text-center">
                        <h3 class="pt-2">Panel pracownika technicznego</h3>
                        <nav class="col-sm-8 navbar navbar-light bg-light mx-auto">
                            <button class="btn btn-outline-primary col-5 col-sm-3 m-1 m-sm-0 " type="button" id="timetable">Grafik</button>
                            <button class="btn btn-outline-primary col-5 col-sm-3 m-1 m-sm-0 " type="button" id="statistic">Statystyki</button>
                        </nav>                      
                    </header>
                        
                    <img src="images/snowspinner.gif" alt="alt" id="spinner" style="display:none;position:absolute;left:48%;"/>
                    <div id="mainSection">
                        
                    </div>
                </div>    
                <script src="js/jquery/jquery-3.5.1.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
                <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
                <script src="js/jquery/jquery.scrollTo.min.js"></script>
                <script src="https://www.gstatic.com/charts/loader.js"></script>
                <script src="js/checkSessionStatus.js"></script>
                <script src="js/myCalendar.js"></script>
                <script src="js/technician.js"></script>
                <script src="js/technician/timetable.js"></script>
                <script src="js/technician/statistic.js"></script>
                <script src="js/messageHeaderListener.js"></script>
            </body>
        </html>
        <?php
    }
    
    public static function chatForm() {
        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <?php self::basicHead("Stacja Narciarska - Wiadomości") ?> 
            </head>
            <body>
                <nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="nav">
                    <a class="navbar-brand text-light" href="controller.php"><img src="images/pictogram.png" width="50" height="50" class="mr-1" alt="Logo">Stacja Narciarska</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                      <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarNav">
                      <ul class="navbar-nav">
                        <li class="nav-item">
                          <a class="nav-link" href="forecast.php">Pogoda</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link active" href="chat.php">Wiadomości</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="accountSettings.php">Ustawienia konta</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="logout.php">Wyloguj się</a>
                        </li>
                      </ul>
                    </div>
                </nav>

                <div class="container">
                    <main class="row pt-3">
                        <section class="col-sm-5 col-lg-4" style="background-color: #b5b5b5; height: 85vh;">
                            <div class="pt-3 inputSearch" style="height: 160px">
                                <select class="custom-select col-12 form-group rounded" id="searchPeopleInput">
                                    <option value="" disabled selected></option>
                                </select>
                                <div class="row ml-1" id="peopleTypeRadios">
                                    <div class="form-check form-check-inline col-5 mx-auto ">
                                        <input class="form-check-input" type="radio" name="peopleRadio" id="adminRadio" value="Admin">
                                        <label class="form-check-label" for="adminRadio">Admini</label>
                                    </div>
                                    <div class="form-check form-check-inline col-5 mx-auto">
                                        <input class="form-check-input" type="radio" name="peopleRadio" id="servicemanRadio" value="Serwisant">
                                        <label class="form-check-label" for="servicemanRadio">Serwisanci</label>
                                    </div>
                                    <div class="form-check form-check-inline col-5 mx-auto">
                                        <input class="form-check-input" type="radio" name="peopleRadio" id="technicianRadio" value="Techniczny">
                                        <label class="form-check-label" for="technicianRadio">Techniczni</label>
                                    </div>
                                    <div class="form-check form-check-inline col-5 mx-auto">
                                        <input class="form-check-input" type="radio" name="peopleRadio" id="instructorRadio" value="Instruktor">
                                        <label class="form-check-label" for="instructorRadio">Instruktorzy</label>
                                    </div>
                                    <div class="form-check form-check-inline col-5 mx-auto">
                                        <input class="form-check-input d-block" type="radio" name="peopleRadio" id="userRadio" value="Użytkownik">
                                        <label class="form-check-label" for="userRadio">Użytkownicy</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="recentlyPeople text-center mt-2" id="recentlyPeople" style="">
                                
                            </div>
                        </section>
                        <section class="position-relative col-sm-7 col-lg-8" style="background-color: #b5b5b5; border: 2px solid white; height: 85vh">
                            <div class="messageHeader h5 p-3 m-0" id="messageHeader" > 
                            
                            </div>
                            
                            <div class="messagesField pb-2 row" id="messagesField" style="background-color: #e6e7f0">
                                
                            </div> 
                                        
                            <div class="col-12 row messageInput position-absolute bottom-0 align-content-center" hidden="true" id="messageSubmitDiv">
                                <textarea class="col-10 text-left form-group rounded mb-0" id="messageTextarea" rows="2" style="resize: none"></textarea>
                                <button class="col-2 col-lg-1 btn btn-success p-0 rounded " id="sendMessageBTN" style="max-height: 70px"><img src="images/sendIcon.png" class="col-12 img-fluid p-0" alt="Send icon"/></button>
                            </div>
                            
                        </section>
                    </main>
                </div>   
                <script src="js/jquery/jquery-3.5.1.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
                <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
                <script src="js/jquery/jquery.scrollTo.min.js"></script>
                <script src="js/checkSessionStatus.js"></script>
                <script src="js/chat.js"></script> 
            </body>
        </html>
        <?php
    }
}
