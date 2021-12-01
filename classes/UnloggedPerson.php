<?php
require_once 'Database.php';
require_once 'Validation.php';

class UnloggedPerson {
    public function login() {
        if(!filter_input(INPUT_POST, 'loginL') && !filter_input(INPUT_POST, 'passL')) {
            header('Location: loginPage.php');
            exit();
        }   
        $login = filter_input(INPUT_POST, 'loginL');
        $login = htmlentities($login, ENT_QUOTES, "UTF-8"); //ENT_QUOTES zamiana apostrofów i cudzyslowów na encje
        $pass = filter_input(INPUT_POST, 'passL');

        $db = new Database();
        $result = $db->mysqli->query(
                sprintf("SELECT * FROM account WHERE login='%s'", //%s zmienna string podana z mysqli_escape
                mysqli_real_escape_string($db->mysqli, $login)));  //obrona przed wstrzykiwaniem MySQL
        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if(password_verify($pass, $row['password'])) { //Sprawdzam haslo z hashem
                self::putUserValuesToSession($row);
                self::goToPage($row['role']);
            }
            else {
                $_SESSION['loginFail'] = 'Podałeś zły login lub hasło!';
                header('Location: loginPage.php');
            }
        }
        else {
            $_SESSION['loginFail'] = 'Podałeś zły login lub hasło!';
            header('Location: loginPage.php');
        }  
    }
    
    public static function putUserValuesToSession($row) {
        $_SESSION['isLogged'] = true;
        $_SESSION['id'] = $row['idAccount'];
        $_SESSION['login'] = $row['login'];
        $_SESSION['role'] = $row['role'];
        unset($_SESSION['loginFail']);
    }
    
    public static function goToPage($role) {
        switch($role) {
            case 'Admin': header('Location: adminPage.php'); break;
            case 'Użytkownik': header('Location: userPage.php'); break;
            case "Instruktor" : header('Location: instructorPage.php'); break;
            case "Serwisant" : header('Location: servicemenPage.php'); break;
            case "Techniczny" : header('Location: technicianPage.php'); break;
            default : header('Location: index.php'); break;
        }
    }
    
    public function register() {
        $firstname = filter_input(INPUT_POST, 'firstnameR');
        $surname = filter_input(INPUT_POST, 'surnameR');
        $birthdate = filter_input(INPUT_POST, 'birthdateR');
        $email = filter_input(INPUT_POST, 'emailR');
        $login = filter_input(INPUT_POST, 'loginR');
        $pass = filter_input(INPUT_POST, 'passwordR');
        $pass2 = filter_input(INPUT_POST, 'password2R');                                

        $validationResult = [];
        array_push($validationResult, Validation::firstnameValidation($firstname));
        array_push($validationResult, Validation::surnameValidation($surname));
        array_push($validationResult, Validation::birthdateValidation($birthdate));
        array_push($validationResult, Validation::emailValidation($email));
        array_push($validationResult, Validation::loginValidation($login));
        array_push($validationResult, Validation::passwordValidation($pass, $pass2));
        $OK = true;
        for($i=0; $i<count($validationResult); $i++) {
            if($validationResult[$i] === false) {
                $OK = false;
            }
        }

        if($OK) {
            self::insertNewUser($firstname, $surname, $birthdate, $email, $login, $pass);
        }
        else {
            self::putRegisterValuesToSession($firstname, $surname, $birthdate, $email, $login, $pass, $pass2);
            header('Location: registerPage.php');
        }      
    }
    
    public static function insertNewUser($firstname, $surname, $birthdate, $email, $login, $pass) {
        $passHash = password_hash($pass, PASSWORD_DEFAULT);
        $creationDate = date("Y-m-d");
        $db = new Database();
        //Start transaction
        $db->mysqli->begin_transaction();
        try {
            $db->mysqli->query(
                sprintf("INSERT INTO account VALUES(NULL, '%s', '%s', 'Użytkownik', '$creationDate', NULL)", 
                mysqli_real_escape_string($db->mysqli, $login),
                mysqli_real_escape_string($db->mysqli, $passHash)));  
            $idAccount = $db->mysqli->query(
                sprintf("SELECT idAccount FROM account WHERE login='%s'", 
                mysqli_real_escape_string($db->mysqli, $login)));
            $row = $idAccount->fetch_assoc(); //Tworzę tab.asocjacyjną o kluczu z nagłówków
            $id = $row['idAccount'];
            $db->mysqli->query(
                sprintf("INSERT INTO personaldata VALUES('%s', '%s', '%s', '%s', '$birthdate')", 
                mysqli_real_escape_string($db->mysqli, $id),
                mysqli_real_escape_string($db->mysqli, $firstname),
                mysqli_real_escape_string($db->mysqli, $surname),
                mysqli_real_escape_string($db->mysqli, $email)));
            /* If code reaches this point without errors then commit the data in the database */
            $db->mysqli->commit();
            $_SESSION['registrationSuccess'] = true;
            header('Location: loginPage.php');
        } catch (mysqli_sql_exception $exception) {
            $db->mysqli->rollback();
            $_SESSION['registrationSuccess'] = false;
            header('Location: registerPage.php');
        }
    }
     
    public static function putRegisterValuesToSession($firstname, $surname, $birthdate, $email, $login, $pass, $pass2) {
        $_SESSION['rememberFirstname'] = $firstname;
        $_SESSION['rememberSurname'] = $surname;
        $_SESSION['rememberBirthdate'] = $birthdate;
        $_SESSION['rememberEmail'] = $email;
        $_SESSION['rememberLogin'] = $login;
        $_SESSION['rememberPass'] = $pass;
        $_SESSION['rememberPass2'] = $pass2;
    }
    
}
