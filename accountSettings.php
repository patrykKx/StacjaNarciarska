<?php
require_once './classes/Form.php';
require_once './classes/Person.php';
require_once './classes/Access.php';
session_start();
Access::isAccessForLoggedPerson();
Person::putAccountDataToSession();
Form::accountSettingsForm();