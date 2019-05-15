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
            session_unset();
            session_destroy();
            $_SESSION['locale'] = 'fr';
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
        case 'makeAnAppointment':
            CheckTimeSlotAvailable();
            break;
        case 'changeAppointmentIsNewStatus':
            ChangeAppointmentIsNewStatus();
            break;
        case 'timeSlotManagement':
            require('views/timeSlotManagement.php');
            break;
        case 'updateemail':
            UpdateEmail();
            break;
        case 'ajaxAddNewTimeSlot':
            ajaxAddNewTimeSlot();
            break;
        case 'ajaxUpdateTimeSlot':
            ajaxUpdateTimeSlot();
            break;
        case 'ajaxGetTimeSlots':
            ajaxGetTimeSlots();
            break;
        case 'ajaxGetFreeTimeSlots':
            ajaxGetFreeTimeSlots();
            break;
        case 'ajaxDeleteTimeSlot':
            ajaxDeleteTimeSlot();
            break;
        case 'reserveappointment':
            ReserveAppointment();
            break;
        case "showCustomerInfo":
            ShowCustomerInfo();
            break;
        case 'medicalSurvey':
            OpenMedicalSurvey();
            break;
        case 'saveMedicalSurvey':
            SaveMedicalSurvey();
            break;
        case 'mainMedicalSurvey':
            MainMedicalSurvey();
            break;
        case 'medicalSurveyUpdate':
            MedicalSurveyUpdate();
            break;
        case 'userOldAppointments':
            OldAppointments();
            break;
        case 'forgotPassword':
            OpenForgotPasswordEmailSelector();
            break;
        case "runDailyCronJobs":
            Home();
            break;
        case 'cancelappointments':
            CancelAppointments();
            break;
        case "followuplist":
            FollowUpList(0);
            break;
        case "newFollowUp":
            NewFollowUp();
            break;
        case "consultFollowUp":
            ConsultFollowUp();
            break;
        case 'sendForgotPasswordEmail':
            SendForgotPasswordEmail();
            break;
        case "showAppointmentDetails":
            showAppointmentDetails();
            break;
        case "createNewCustomer" :
            ShowCreateNewCustomer();
            break;
        case "personalInformationToCreateCustomer":
            ShowPersonalInformationForCustomer();
            break;
        case 'openUpdatePassword':
            OpenUpdatePassword();
            break;
        case "manageDiaporama":
            ManageDiaporama();
            break;
        case 'ShowAddEmailForACustomer':
            ShowAddEmailForACustomer();
            break;
        case 'addEmailForCustomer':
            AddEmailForACustomer();
            break;
        case 'manageAboutText':
            ManageAboutText();
            break;
        default :
            error(404);
            break;
        }
    }
	else if(isset($_GET['token'])){
		ActionToken();
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
    else if(isset($_POST['timeSlot'])){
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
