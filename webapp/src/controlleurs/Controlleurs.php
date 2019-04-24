<?php
session_start();
require('models/ManagerUsers.php');
require('services/callApiExtension.php');

$default_locale = 'fr';

if (!isset($_SESSION['locale'])) {
    $_SESSION['locale'] = $default_locale;
}

if (isset($_GET['setLocale'])) {
    $_SESSION['locale'] = $_GET['setLocale'];
}

function error($errorCode) {
    require('views/error.php');
    die();
}

function userHasPermission(String $permission): bool
{
    return (isset($_SESSION['userid']))
        ? CallAPI('GET', 'Users/HasPermission', array(
                "idUser" =>  $_SESSION['userid'],
                "permission" => $permission
            ))['response']
        : false;
}

function localize($phrase)
{
    global $default_locale;
    static $translations = null;
    if (is_null($translations)) {
        $translations = getLocaleFile($_SESSION['locale']);
    }
    if ($translations[$phrase]) {
        return $translations[$phrase];
    } else {
        static $default_translations = null;
        if (is_null($default_translations)) {
            $default_translations = getLocaleFile($default_locale);
        }
        if ($default_translations[$phrase]) {
            return $default_translations[$phrase];
        } else {
            //Raise Error word not defined
            return $phrase;
        }
    }
}

function getLocaleFile($locale)
{
    global $default_locale;
    $lang_file = 'lang/' . $locale . '.json';
    if (!file_exists($lang_file)) {
        //Raise error locale not found
        $lang_file = 'lang/' . $default_locale . '.json';
    }
    $lang_file_content = file_get_contents($lang_file);
    return json_decode($lang_file_content, true);
}


function Login(){
    if(isset($_POST['email'])){
        unset($_SESSION['email']);
        $userIdentification = [
            "email" => htmlentities($_POST['email']),
            "password" => htmlentities($_POST['password'])
        ];
        $userAPI = CallAPI('POST', 'Users/Login', json_encode($userIdentification));
        if($userAPI['statusCode'] == 200)
        {
            $_SESSION['username'] = $userAPI['response']->fullName;
            $_SESSION['userid'] = $userAPI['response']->id;
            About();
        }
        else
        {
            require('views/login.php');
        }
    }
    else
    {
        require('views/login.php');
    }
}

function Home(){
    unset($_SESSION['email']);
    require('views/home.php');
}

function Inscription(){
    if(isset($_SESSION['email'])){
        $states = CallAPI('GET','States')['response'];
        $phoneTypes = CallAPI('GET', 'PhoneTypes')['response'];
        $phoneType = $phoneTypes;
        $phoneType2 = $phoneTypes;
        $phoneType3 = $phoneTypes;
        require('views/personalinformation.php');
    }
    else{
        require('views/inscription.php');
    }
}

function About(){
    unset($_SESSION['email']);
    require('views/about.php');
}

function PersonalInformation(){
    if(!isset($_SESSION['userid'])){
        AddOrUpdateUser();
        unset($_SESSION['email']);
        Login();
    }else{
        if(!empty($_POST)){
            AddOrUpdateUser();
            About();
        }else{
            $result = CallAPI('GET','States')['response'];
            $phoneTypes = CallAPI('GET', 'PhoneTypes')['response'];
            $phoneType = $phoneTypes;
            $phoneType2 = $phoneTypes;
            $phoneType3 = $phoneTypes;
            $personalInformation = CallAPI('GET','PersonalInformation/PersonalInformation/'.json_encode($_SESSION['userid']));
            require('views/personalinformation.php');
        }
    }
}

function AddOrUpdateUser(){
    if(isset($_POST)){
        if($_POST['address'] != '' and $_POST['city'] != ''
        and $_POST['province'] != '' and $_POST['zipcode'] != '' and $_POST['occupation'] != ''
        and $_POST['phone1'] != '' and $_POST['type1'] != ''){
            $phone1 = array(
                'phone'=>htmlentities($_POST['phone1']),
                'extension'=>'',
                'idPhoneType'=>htmlentities($_POST['type1'])
            );
            if(!empty($_POST['extension1'])){
                $phone1['extension'] = htmlentities($_POST['extension1']);
            }
            if(!empty($_POST['phone2'])){
                $phone2 = array(
                    'phone'=>htmlentities($_POST['phone2']),
                    'extension'=>'',
                    'idPhoneType'=>htmlentities($_POST['type2'])
                );
                if(!empty($_POST['extension2'])){
                    $phone2['extension'] = htmlentities($_POST['extension2']);
                }
            }
            if(!empty($_POST['phone3'])){
                $phone3 = array(
                    'phone'=>htmlentities($_POST['phone3']),
                    'extension'=>'',
                    'idPhoneType'=>htmlentities($_POST['type3'])
                );
                if(!empty($_POST['extension3'])){
                    $phone3['extension'] = htmlentities($_POST['extension3']);
                }
            }
            $physicalAddress = array(
                'physicalAddress'=>htmlentities($_POST['address']),
                'cityName'=>htmlentities($_POST['city']),
                'zipCode'=>htmlentities($_POST['zipcode']),
                'idState'=>htmlentities($_POST['province'])
            );
            $user = array(
                'email'=>(isset($_SESSION['email']))?$_SESSION['email']:'',
                'password'=>(isset($_SESSION['password']))?$_SESSION['password']:''
            );
            $customer = array(
                'sex'=>(isset($_SESSION['gender']))? $_SESSION['gender']:'',
                'firstName'=>(isset($_SESSION['firstname']))? $_SESSION['firstname']:'',
                'lastName'=>(isset($_SESSION['lastname']))? $_SESSION['lastname']:'',
                'birthDate'=>(isset($_SESSION['dateofbirth']))? $_SESSION['dateofbirth']:'',
                'occupation'=>htmlentities($_POST['occupation'])
            );
            $phones = array($phone1);
            if (isset($phone2))
                array_push($phones, $phone2);
            if (isset($phone3))
                array_push($phones, $phone3);

            $registeringInformation = array(
                'physicalAddress'=>$physicalAddress,
                'customer'=>$customer,
                'user'=>$user,
                'phoneNumbers'=>$phones
            );
            $result = CallAPI('POST','Registration/Register', json_encode($registeringInformation));
            if(!isset($_SESSION['userid'])){
                $_SESSION['registered'] = 'success';
                unset($_SESSION['email']);
                unset($_SESSION['password']);
                unset($_SESSION['firstname']);
                unset($_SESSION['lastname']);
                unset($_SESSION['gender']);
                unset($_SESSION['dateofbirth']);
            }
        }
    }
}

function CheckEmailInUse(){
    $email = [
        "email" => htmlentities($_POST['email'])
    ];
    $emailInUse = CallAPI('POST','Users/CheckEmailInUse',json_encode($email));
    if($emailInUse['statusCode'] == 200)
    {
        echo 'taken';
    }
    else
    {
        if($_POST['email'] != $_POST['email2']){
            echo 'emailerror';
        }else if($_POST['password'] != $_POST['password2']){
            echo 'passworderror';
        }else if($_POST['email'] != '' and $_POST['password'] != ''
                and $_POST['firstname'] != '' and $_POST['lastname'] != ''
                and $_POST['gender']!=''){
            $_SESSION['email'] = htmlentities($_POST['email']);
            $_SESSION['password'] = htmlentities($_POST['password']);
            $_SESSION['firstname'] = htmlentities($_POST['firstname']);
            $_SESSION['lastname'] = htmlentities($_POST['lastname']);
            $_SESSION['gender'] = htmlentities($_POST['gender']);
            $_SESSION['dateofbirth'] = htmlentities($_POST['dateofbirth']);
            echo 'availlable';
        }
        else{
            echo 'emptyfield';
        }
    }
}

function UpdatePassword(){
    if(!empty($_POST)){
        if(isset($_POST['oldpassword']) and isset($_POST['newpassword']) and isset($_POST['confirmedpassword'])){
            if(CheckPasswords()){
                $updatePassword = new ManagerUsers;
                $updatePassword->UpdatePassword(htmlentities($_POST['newpassword']),$_SESSION['userid']);
                About();
            }else{
                require('views/UpdatePassword.php');
            }
        }
    }else{
        require('views/UpdatePassword.php');
    }
}

function UpdateEmail(){
    if(!empty($_POST)){
        if(isset($_POST['newemail']) and isset($_POST['newemailconfirmed']) and isset($_POST['password'])){
            if($_POST['newemail'] == $_POST['newemailconfirmed']){
                $userIdentification = [
                    "userid" => htmlentities($_SESSION['userid']),
                    "password" => htmlentities($_POST['password'])
                ];
                $user = CallAPI('GET', 'Users/CheckPassword',$userIdentification);
                if($user['statusCode'] == 200){
                    $newEmail = [
                        "id" => htmlentities($_SESSION['userid']),
                        "email" => htmlentities($_POST['newemail'])
                    ];
                    $emailUpdate = CallAPI('POST', 'Users/UpdateUserEmail',json_encode($newEmail));
                    $_SESSION['emailchanged'] = true;
                }else{
                    echo 'emailerror';
                }
            }else{
                echo 'emaildontmatch';
            }
        }
    }else{
        require('views/updateEmail.php');
    }
}

function CheckNewEmailAvaillable(){
    $email = [
        "email" => htmlentities($_POST['newemail'])
    ];
    $emailInUse = CallAPI('POST','Users/CheckEmailInUse',json_encode($email));
    if($emailInUse['statusCode'] == 200)
    {
        echo 'taken';
    }else{
        UpdateEmail();
        echo 'available';
    }
}

function CheckPasswords(){
    $currentPassword = new ManagerUsers;
    $oldpassword = $currentPassword->GetPassword($_SESSION['userid']);
    if($oldpassword != htmlentities($_POST['oldpassword'])){
        return false;
    }else if(htmlentities($_POST['confirmedpassword']) != htmlentities($_POST['newpassword'])){
        return false;
    }else if(htmlentities($_POST['oldpassword']) == '' or htmlentities($_POST['confirmedpassword']) ==''
            or htmlentities($_POST['newpassword']) == ''){
        return false;
    }else if(htmlentities($_POST['oldpassword']) == htmlentities($_POST['newpassword'])){
        return false;
    }else{
        return true;
    }
}

function AskForAppointment()
{
    require('views/ask_for_appointment.php');
}

function SendAskForAppointment()
{
    if(isset($_POST['askForAppointmentDate']) && isset($_POST['appointmentTimeOfDay'])
    && isset($_POST['TypeOfTreatment']))
    {
        $data = array(
            'date' => htmlentities($_POST['askForAppointmentDate']),
            'timeOfDay' => htmlentities($_POST['appointmentTimeOfDay']),
            'typeOfTreatment' => htmlentities($_POST['TypeOfTreatment']),
            'moreInformation' => isset($_POST['moreInformation']) ?
                htmlentities($_POST['moreInformation']) : "",
            'email' => isset($_POST['AskForAppointmentEmail']) ?
                htmlentities($_POST['AskForAppointmentEmail']) : "",
            'userId' => isset($_SESSION['userid']) ? htmlentities($_SESSION['userid']) : "",
            'phoneNumber' => isset($_POST['AskForAppointmentPhoneNumber']) ?
                htmlentities($_POST['AskForAppointmentPhoneNumber']):"",
            'userName' => isset($_POST['AskForAppointmentUserName']) ?
                htmlentities($_POST['AskForAppointmentUserName']) : ""
        );
        $result = CallAPI('POST','Appointments/AskForAppointment',json_encode($data));
        if($result['statusCode'] == 200)
        {
            require('views/confirmation_message.php');
        }
    }
}

function NewAppointments(){
    if (userHasPermission('appointments_read')) {
        $result = CallAPI('GET','Appointments/NewAppointments');
        $newAppointments = $result['response'];
        if($newAppointments)
        {
            require('views/new_appointments.php');
        }
        else
        {
            require('views/appointments.php');
        }
    } else error(403);
}

function Appointments(){
  require('views/appointments.php');
}

function ChangeAppointmentIsNewStatus() {
    if(isset($_POST['newAppointmentIds'])) {
        if (userHasPermission('appointments-write')) {
            CallAPI('POST', 'Appointments/ChangeIsNewStatus',json_encode($_POST['newAppointmentIds']));
            echo 'success';
        } else {
            echo 'Permission Denied';
        }
    } else {
        echo 'No data received';
    }
}

function MakeAppointment(){
    if (isset($_POST)){
        if (userHasPermission('appointments-write')) {
            $appointment = array(
                'AppointmentDateTime' => htmlentities($_POST['appointmentDate'].' '.$_POST['appointmentTime']),
                'DurationTime' => htmlentities($_POST['appointmentDuration']),
                'IdCustomer' => htmlentities($_POST['idCustomer'])
            );
            $result = CallAPI('POST', 'Appointments/CheckAppointmentIsAvailable/%23definition', json_encode($appointment));
            if (!$result->title){
                echo  'success';
            } else {
                echo $result->title;
            }
        } else {
            echo 'Permission Denied';
        }
    } else {
        echo 'No data received';
    }
}

function ajaxAddNewTimeslot() {
    if (isset($_POST)) {
        if (isset($_POST['startDatetime']) && isset($_POST['endDatetime']) && isset($_POST['isPublic'])) {
            $startDatetime = DateTime::createFromFormat('d/m/Y, H:i:s', htmlentities($_POST['startDatetime']));
            $endDatetime = DateTime::createFromFormat('d/m/Y, H:i:s', htmlentities($_POST['endDatetime']));
            $newTimeslot = array(
                "startDateTime" => $startDatetime->format("Y-m-d H:i"),
                "endDateTime" => $endDatetime->format("Y-m-d H:i"),
                "durationTime" => $endDatetime->format("Y-m-d H:i"),
                "notes" => (htmlentities($_POST['notes']) != '') ? htmlentities($_POST['notes']) : null,
                "isPublic" => htmlentities($_POST['isPublic']),
                "isAvailable" => htmlentities($_POST['isAvailable'])
            );
            $result = CallAPI('POST', 'TimeSlots/Add', json_encode($newTimeslot));
            if ($result['statusCode'] == 200)
                echo json_encode($result['response']);
            else if ($result['statusCode'] == 409)
                echo 'La nouvelle plage horaire est en conflit avec une ou plusieurs autres plages horaire.';
            else if ($result['statusCode'] == 400)
                echo "La nouvelle plage horaire n'a pas pu être créée.";
        } else echo 'Incomplete or invalid data received';
    } else echo 'No data received';
}
function SearchCustomer(){
    require('views/customerSearch.php');
}

function GetCustomersByPhone(){
    $customerNames = CallAPI('POST', 'Customers/GetCustomersByPhone/',json_encode(htmlentities($_POST['customerPhone'])));
    if($customerNames['response'] != null){
        $select = '<select id="customerNames" name="customerNames">
            <option value="0"></option>';
        foreach($customerNames['response'] as $customer){
            $select = $select .'<option value="'. $customer->id.'">'.$customer->firstName.' '. $customer->lastName.'</option>';
        }
        $select = $select . '</select>';
        echo $select;
    }else{
        echo localize('CustomerSearch-InvalidNumber');
    }
}

function GetCustomersByName(){
    $customerNames = CallAPI('GET', 'Customers/GetCustomersByName/'.htmlentities($_POST['customerName']));
    if($customerNames['response'] != null){
        $select = '<select id="customerSelect" onchange="GetCustomerById();" name="customerSelect">
            <option value="0"></option>';
        foreach($customerNames['response'] as $customer){
            $select = $select .'<option value="'. $customer->id.'">'.$customer->firstName.' '. $customer->lastName.'</option>';
        }
        $select = $select . '</select>';
        echo $select;
    }else{
        echo'';
    }
}

function GetCustomerInformation(){
    $customerId = htmlentities($_POST['customerId']);
    if($customerId != '0'){
        $customer = CallAPI('GET','Customers/'.$customerId);
        $output = '<table class="table table-sm table-hover" id="tbl_customers">
                                <thead class="thead-dark">
                                    <tr> 
                                        <th scope="col">';
        $output = $output. localize('Appointment-Customer').'</th>
                                <th scope="col">'. localize('Personal-Occupation').'</th>
                                <th scope="col">'. localize('Personal-Phone').'</th>
                                    </tr>
                                </thead>
                                <tbody>';
        $output = $output . '<tr class="clickable-row" id="'.$customerId.'">
                                        <td scope="row">'.
                                        $customer['response']->firstName.' '.
                                        $customer['response']->lastName.'</td><td>'.
                                        $customer['response']->occupation.'</td><td>';
        $phoneResult = CallAPI('GET', 'PhoneNumbers/ForCustomer/'.$customerId);
        $phoneNumbers = $phoneResult['response']; 
        foreach ($phoneNumbers as $phoneNumber) {
            $output = $output . '
            <table style="width:100%; background-color: rgba(255,255,255,0)">
                <tr>
                    <th>'.$phoneNumber->idPhoneType.'</th>
                    <td>'.$phoneNumber->phone.$phoneNumber->extension.'</td>
                </tr>
            </table>';
        }
        $output = $output . '</td> </tr></tbody></table>';
        echo $output;
    }else{
        echo '';
    }
}

function ajaxDeleteTimeslot() {
    if (isset($_POST)){
        if (isset($_POST["idTimeslot"])) {
            $result = CallAPI('DELETE', 'TimeSlots/'.htmlentities($_POST['idTimeslot']));
            echo ($result['statusCode'] == 204)
                ? 'success'
                : "Une erreur c'est produite lors de la suppression";
        } else echo 'Invalid data received';
    } else echo 'No data received';
}

function ajaxUpdateTimeslot() {
    if (isset($_POST)) {
        if (isset($_POST['idTimeslot']) && isset($_POST['notes'])) {
            $originalTimeslot = CallAPI('GET', 'TimeSlots/'.htmlentities($_POST['idTimeslot']))['response'];
            $updatedTimeslot = array(
                "startDateTime" => $originalTimeslot->startDateTime,
                "endDateTime" => $originalTimeslot->endDateTime,
                "durationTime" => $originalTimeslot->durationTime,
                "isPublic" => $originalTimeslot->isPublic,
                "isAvailable" => $originalTimeslot->isAvailable,
                "notes" => (htmlentities($_POST['notes']) != '') ? htmlentities($_POST['notes']) : null,
                "id" => $originalTimeslot->id,
                "isActive" => $originalTimeslot->isActive
            );
            $result = CallAPI('POST', 'TimeSlots', json_encode($updatedTimeslot));
            if ($result['statusCode'] == 200)
                echo 'success';
            else
                echo "La plage horaire n'a pas pu être mise à jour";
        } else echo 'Incomplete or invalid data received';
    } else echo 'No data received';
}

function ajaxGetTimeSlots() {
    $result = CallAPI('Get', 'TimeSlots');
    if ($result['statusCode'] == 200)
        echo json_encode($result['response']);
    else
        echo 'error';
}

function AppointmentCreator()
{
    require('views/appointment_creator.php');
}
function Customers()
{
    if (userHasPermission('MedicalSurveys-Read')) {
        $customers = CallAPI('GET','Customers/CustomersWithPhoneInfo')['response'];
        require('views/customers_list.php');
    } else error(403);
}

function ReserveAppointment(){
    if(isset($_SESSION['userid'])){
        $availableTimeSlots = CallAPI('GET','TimeSlots/GetFreeTimeSlots')['response'];
        require('views/reserveAppointment.php');
    }else{
        error(403);
    }
}

function CheckTimeSlotAvailable(){
    $result = CallAPI('Get','TimeSlots/CheckTimeSlotAvailable/'.$_POST['timeslot']);
    if($result['statusCode']== 200){
        echo 'available';
        ReserveTimeSlotForAppointment(htmlentities($_POST['timeslot']), htmlentities($_POST['therapist']));
        $_SESSION['appointmenttaken'] = true;
    }else{
        echo $result['statusCode'];
    }
}

function ReserveTimeSlotForAppointment($timeslot, $therapist){
    $appointment = array(
        'idTimeslot' => $timeslot,
        'therapist' => $therapist,
        'idUser' => (isset($_SESSION['userid']))? htmlentities($_SESSION['userid']):0
    );
    CallAPI('POST','Appointments/ReserveAnAppointment',json_encode($appointment));
    $_SESSION['appointmenttaken'] = true;
}

function ShowCustomerInfo()
{
    if(userHasPermission('customers-read') && userHasPermission('customers-write'))
    {
        $customerId = htmlentities($_GET['customerId']);
        require('views/customer_info.php');
    }
    else
    {
        error(403);
    }
}
?>
