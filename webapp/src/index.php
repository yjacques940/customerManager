<?php
require('controlleurs/Controlleurs.php');
try
{
    if (isset($_GET['action'])) {
    switch ($_GET['action']){
        case 'inscription':
            Inscription();
            break;
        case 'home':
            Home();
            break;
        case 'login':
            Login();
            break;
        case 'about':
            About();
            break;
        case 'personalinformation':
            PersonalInformation();
            break;
        case 'updatepassword':
            UpdatePassword();
            break;
        case 'logout':
            unset($_SESSION['userid']);
            unset($_SESSION['username']);
            Home();
            break;
        case 'ask_for_appointment' :
            AskForAppointment();
            break;
        case 'send_ask_for_appointment' :
            SendAskForAppointment();
            break;
        case 'appointmentCreator' :
            AppointmentCreator();
            break;
        case 'appointments':
            Appointments();
            break;
        case 'newAppointments' :
            NewAppointments();
            break;
        case 'customers' :
            Customers();
            break;
        case 'makeAppointment':
            MakeAppointment();
            break;
        case 'changeAppointmentIsNewStatus':
            ChangeAppointmentIsNewStatus();
            break;
        case 'updateemail':
            UpdateEmail();
            break;
        default :
            Home();
            break;
        }
    }
    else if(isset($_POST['email'])){
        CheckEmailInUse();
    }
    else if(isset($_POST['newpassword'])){
        CheckPasswords();
    }
    else
    {
    Home();
    }
}
catch (PDOException $e) {
    $msgErreur = $e->getMessage();
    require 'views/vueErreur.php';
}
?>
