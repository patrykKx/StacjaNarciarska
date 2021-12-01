<?php
require_once './classes/User.php';
require_once './classes/Admin.php';
require_once './classes/Person.php';
require_once './classes/UnloggedPerson.php';
require_once './classes/Servicemen.php';
require_once './classes/Instructor.php';
require_once './classes/Statistic.php';
session_start();
$user = new User();
$admin = new Admin();
$person = new Person();
$unloggedPerson = new UnloggedPerson();
$servicemen = new Servicemen();
$instructor = new Instructor();
$statistic = new Statistic();

if(filter_input(INPUT_POST, "submit")) {
    $action = filter_input(INPUT_POST, "submit");
    
    switch($action) {
        case "checkSessionStatus": $person->checkSessionStatus(); break;
        case "login" : $unloggedPerson->login(); break;
        case "register" : $unloggedPerson->register(); break;
        case "showConditions": $person->getConditions(); break;
        case "showTariff": $person->getTariff(); break;
        case "showDayDetails": $person->getAdvancedCalendar(); break;
        case "showDays": $person->getCalendar(); break;
        case "addWorker" : $admin->addWorker(); break;
        case "showPersonList" : $admin->getPerson(); break;
        case "deletePerson" : $admin->deletePerson(); break;
        case "submitTariff" : $admin->submitTariff(); break;
        case "submitConditions": $admin->submitConditions(); break;
        case "addEquipment": $admin->addEquipment(); break;
        case "showEquipment": $admin->getEquipment(); break;
        case "editEquipment": $admin->editEquipment(); break;
        case "deleteEquipment": $admin->deleteEquipment(); break;
        case "changePassword": $person->editPassword(); break;
        case "changeEmail": $person->editEmail(); break;
        case "submitCalendar": $admin->submitCalendar(); break;
        case "showWorkersNames": $admin->getWorkersNamesAndLogins(); break;
        case "addTimetable": $admin->addTimetable(); break;
        case "showDayTimetable": $admin->getTimetable(); break;
        case "deleteFromTimetable": $admin->deletePersonFromTimetable(); break;
        case "showWorkerDays" :$admin->showWorkerDays(); break;
        case "buyTicket": $user->buyTicket(); break;
        case "getTicketCost": $user->getTicketCost(); break;
        case "getTicketList": $user->getTicketList(); break;
        case "cancelTicket": $user->cancelTicket(); break;
        case "addService": $servicemen->addService(); break;
        case "showUserLogins": $servicemen->getUserLogins(); break;
        case "getServiceCost": $servicemen->getServiceCost(); break;
        case "getCurrentService": $servicemen->getCurrentService(); break;
        case "changeServiceStatusToReadyToPickUp": $servicemen->changeServiceStatusToReadyToPickUp(); break;
        case "changeServiceStatusToPickedUp": $servicemen->changeServiceStatusToPickedUp(); break;
        case "getServiceHistory": $servicemen->getServiceHistory(); break;
        case "addHire": $servicemen->addHire(); break;
        case "getEquipmentToServiceman": $servicemen->getEquipmentByType(); break;
        case "getEquipmentNameAndSize": $servicemen->getEquipmentNameAndSize(); break;
        case "getActiveHire": $servicemen->getActiveHire(); break;
        case "changeActiveHireStatus": $servicemen->changeActiveHireStatus(); break;
        case "getHireHistory": $servicemen->getHireHistory(); break;
        case "getWorkerDays": $person->getWorkerDays(); break;
        case "getWorkerDayHighlights": $person->getWorkerDayHighlights(); break;
        case "getUserServiceList": $user->getUserServiceList(); break;
        case "getUserHireList": $user->getUserHireList(); break;
        case "getEquipmentData": $admin->getEquipmentData(); break;
        case "getCorrectEquipmentId": $admin->getCorrectEquipmentId(); break;
        case "bookLesson": $user->bookLesson(); break;
        case "getInstructorTimetableByDay": $user->getInstructorTimetableByDay(); break;
        case "getInstructorLessonsByDay": $user->getInstructorLessonsByDay(); break;
        case "showInstructorDays": $user->showInstructorDays(); break;
        case "getLessonCost": $user->getLessonCost(); break;
        case "getInstructorList": $user->getInstructorList(); break;
        case "getUserLessons": $user->getUserLessons(); break;
        case "cancelLessonByUser": $user->cancelLesson(); break;
        case "getInstructorLessonsByDayToInstructorPanel": $instructor->getInstructorLessonsByDay(); break;
        case "cancelLessonByInstructor": $instructor->cancelLesson(); break;
        case "getTotalUserCost": $statistic->getTotalUserCost(); break;
        case "getOpeningDayByMonths": $statistic->getOpeningDayByMonths(); break;
        case "getStationIncomeByMonths": $statistic->getStationIncomeByMonths(); break;
        case "getWorkerHoursByMonths": $statistic->getWorkerHoursByMonths(); break;
        case "getHireHighlights": $statistic->getHireHighlights(); break;
        case "getTicketHighlights": $statistic->getTicketHighlights(); break;
        case "getLessonHighlights": $statistic->getLessonHighlights(); break;
        case "getTotalUserActivity": $statistic->getTotalUserActivity(); break;
        case "getUserSkipassHourByMonths": $statistic->getUserSkipassHourByMonths(); break;
        case "getHireHighlightsByMonths": $statistic->getHireHighlightsByMonths(); break;
        case "getWorkerHighlightsByMonths": $statistic->getWorkerHighlightsByMonths(); break;
        case "getWorkerHighlightsByMonthAndType": $statistic->getWorkerHighlightsByMonthAndTypeToAdmin(); break;
        case "getMostActiveInstructors": $statistic->getMostActiveInstructors(); break;
        case "getInstructorLessonHighlights": $statistic->getInstructorLessonHighlights(); break;
        case "getPersonListToChat": $person->getPersonListToChat(); break;
        case "getMessages": $person->getMessages(); break;
        case "saveMessage": $person->saveMessage(); break;
        case "getRecentlyMessagePeople": $person->getRecentlyMessagePeople(); break;
        case "refreshRecentlyMessagePeople": $person->refreshRecentlyMessagePeople(); break;
        case "getUnreadMessages": $person->getUnreadMessages(); break;
        case "checkUnreadPeople": $person->checkUnreadPeople(); break;
        case "countUnreadMessagesFromPeople": $person->countUnreadMessagesFromPeople(); break;
        case "getMatchingLogins": $admin->getMatchingLogins(); break;
        case "getInfoAboutUser": $admin->getInfoAboutUser(); break;
    }
}
else {
    header('Location: index.php');
}




