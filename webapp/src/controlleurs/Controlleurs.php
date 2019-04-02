<?php
session_start();
require('models/ManagerUsers.php');
require('models/Customer.php');
require('services/callApiExtension.php');

$default_locale = 'fr';

if (!isset($_SESSION['locale'])) {
    $_SESSION['locale'] = $default_locale;
}

if (isset($_GET['setLocale'])) {
    $_SESSION['locale'] = $_GET['setLocale'];
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
        $userRegistered = new ManagerUsers;
        $email = htmlentities($_POST['email']);
        $password = htmlentities($_POST['password']);
        $user = $userRegistered->VerifierLogin($email, $password);
        if($donnees = $user->fetch())
        {
            $_SESSION['username'] = $donnees['first_name'] . ' ' . $donnees['last_name'];
            $_SESSION['userid'] = $donnees['id_user'];
            About();
        }
        else
        {
            require('views/login.php');
        }
        $user->closeCursor();
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
        $provinces = new ManagerUsers;
        $result = $provinces->GetProvinces();
        $phoneType = $provinces->GetPhoneType();
        $phoneType2 = $provinces->GetPhoneType();
        $phoneType3 = $provinces->GetPhoneType();
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
            $provinces = new ManagerUsers;
            $result = $provinces->GetProvinces();
            $phoneType = $provinces->GetPhoneType();
            $phoneType2 = $provinces->GetPhoneType();
            $phoneType3 = $provinces->GetPhoneType();
            $personalInformation = $provinces->GetPersonalInformation($_SESSION['userid']);
            require('views/personalinformation.php');
        }
    }
}

function AddOrUpdateUser(){
    if(isset($_POST)){
        if($_POST['address'] != '' and $_POST['city'] != '' 
        and $_POST['province'] != '' and $_POST['zipcode'] != '' and $_POST['occupation'] != ''
        and $_POST['phone1'] != '' and $_POST['type1'] != ''){
            $phone1 = array(htmlentities($_POST['phone1']),'',htmlentities($_POST['type1']));
            if(!empty($_POST['extension1'])){
                $phone1[1] = htmlentities($_POST['extension1']);
            }
            $phone2 = array('','','0');
            $phone3 = array('','','0');
            if(!empty($_POST['phone2'])){
                $phone2[0] = htmlentities($_POST['phone2']);
                $phone2[2] = htmlentities($_POST['type2']);
                if(!empty($_POST['extension2'])){
                    $phone2[1] = htmlentities($_POST['extension2']);
                }
            }
            if(!empty($_POST['phone3'])){
                $phone3[0] = htmlentities($_POST['phone3']);
                $phone3[2] = htmlentities($_POST['type3']);
                if(!empty($_POST['extension3'])){
                    $phone2[1] = htmlentities($_POST['extension3']);
                }
            }
            $newUser = new ManagerUsers;
            if(!isset($_SESSION['userid'])){
                $newUser->AddUser($_SESSION['email'],$_SESSION['password'],$_SESSION['firstname'],
            $_SESSION['lastname'],$_SESSION['gender'],htmlentities($_POST['address']),
            htmlentities($_POST['city']),htmlentities($_POST['province']),
            htmlentities($_POST['zipcode']),$_SESSION['dateofbirth'],
            htmlentities($_POST['occupation']),$phone1[0],$phone1[1],$phone1[2],
            $phone2[0],$phone2[1],$phone2[2],$phone3[0],$phone3[1],$phone3[2]);
            $_SESSION['registered'] = 'success';
            unset($_SESSION['email']);
            unset($_SESSION['password']);
            unset($_SESSION['firstname']);
            unset($_SESSION['lastname']);
            unset($_SESSION['gender']);
            unset($_SESSION['dateofbirth']);
            }else{
                $newUser->UpdateUser($_SESSION['userid'],htmlentities($_POST['address']),
                htmlentities($_POST['city']),htmlentities($_POST['province']),
                htmlentities($_POST['zipcode']), htmlentities($_POST['occupation']),
                $phone1[0],$phone1[1],$phone1[2], $phone2[0],$phone2[1],$phone2[2],
                $phone3[0],$phone3[1],$phone3[2]);
            }
        }
    }
}

function CheckEmailInUse(){
    $user = new ManagerUsers;
    $emailinUse = $user->CheckEmailInUse(htmlentities($_POST['email']));
    if($emailinUse->fetch())
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
                require('views/about.php');
            }else{
                require('views/UpdatePassword.php');
            }
        }
    }else{
        require('views/UpdatePassword.php');
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

function ReportBug()
{
    require('views/report_bug.php');
}

function SendBug()
{
    require('views/confirmation_message.php');
    if(isset($_POST['bug-description']))
    {
        $data = array( 'MessageContent' => htmlentities($_POST['bug-description']));
        CallAPI('POST','Email' , json_encode($data));
    }
    else
    {
        echo 'il n y a aucune donnee d entree';
    }
}
function Api()
{
    //https://www.weichieprojects.com/blog/curl-api-calls-with-php/
    //******* Prendre l'objet JSON dans SWAGGER, et ne pas oublier les foreign keys
    //$customer = array('H','Yannick','Jacques','2019-08-31','developper','1');

    require('services/callApiExtension.php');
    //$id = '1';
    //$get_Data = CallAPI('GET','Customers/FullName/' . $id);
    // $response = json_decode($get_Data,true);
    //$errors = $response['response']['errors'];
    //$data = $response['response']['data'][0];

   /* $new_object = array(
        "sex" => "H",
  "firstName"=> "Yannick",
  "lastName"=> "Jacques",
  "birthDate"=> "2019-03-25T17:12:40.000Z",
  "occupation"=> "Programmer",
  "idAddress"=> 1,
  "id"=> 0,
  "isActive" => "true"
    );*/
   // echo CallAPI("POST","Customers", json_encode($new_object));

  /* CallAPI("DELETE","Customers/4");
  echo  CallAPI("GET","Customers");*/
}
