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
        case 'timeslotManagement':
            require('views/timeslotManagement.php');
            break;
        case 'updateemail':
            UpdateEmail();
            break;
        case 'ajaxAddNewTimeslot':
            ajaxAddNewTimeslot();
            break;
        case 'ajaxUpdateTimeslot':
            ajaxUpdateTimeslot();
            break;
        case 'ajaxGetTimeslots':
            ajaxGetTimeSlots();
            break;
        case 'ajaxDeleteTimeslot':
            ajaxDeleteTimeSlot();
            break;
        case 'reserveappointment':
            ReserveAppointment();
            break;
        case "searchCustomer":
            SearchCustomer();
            break;
        case "showCustomerInfo":
            ShowCustomerInfo();
            break;
        default :
            error(404);
            break;
        }
    }
    else if(isset($_POST['email'])){
        CheckEmailInUse();
    }
    else if(isset($_POST['newpassword'])){
        CheckPasswords();
    }
    else if(isset($_POST['newemail'])){
        CheckNewEmailAvaillable();
    }
    else if(isset($_POST['timeslot'])){
        CheckTimeSlotAvailable();
    }
    else if(isset($_POST['customerPhone'])){
        GetCustomersByPhone();
    }
    else if(isset($_POST['customerName'])){
        GetCustomersByName();
    }
    else if(isset($_POST['customerId'])){
        GetCustomerInformation();
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
