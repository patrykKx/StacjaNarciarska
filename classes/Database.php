<?php

class Database {
    public $mysqli; //uchwyt do BD
    
    public function __construct() {
        $this->mysqli = new mysqli('localhost', 'root', '', 'stacja');
        
        //Sprawdzenie połączenia
        if($this->mysqli->connect_errno) {
            printf("Nie udało się połączyć z serwerem! %s\n", $this->mysqli->connect_errno);
            exit();
        }
        
        //Zmiana kodowania na UTF8
        if($this->mysqli->set_charset("utf8")){
           //Udało się zmienić kodowanie
        }
    }
    
    function __destruct() {
        $this->mysqli->close();
    }
}
