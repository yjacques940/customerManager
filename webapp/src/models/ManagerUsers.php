<?php

require_once("models/Connection.php"); 

class ManagerUsers extends Connexion 
{    
    public function VerifierLogin($email,$password){
        $sql = 'CALL login (:email, :password)';
        $userConnect = self::getConnexion()->prepare($sql);
        $userConnect->bindParam('email',$email,PDO::PARAM_STR);
        $userConnect->bindParam('password',$password,PDO::PARAM_STR);
        $userConnect->execute();
        return $userConnect;
    }

    public function CheckEmailInUse($email){
        $sql = 'CALL CheckEmailInUse(:email)';
        $userConnect = self::getConnexion()->prepare($sql);
        $userConnect->bindParam('email',$email,PDO::PARAM_STR);
        $userConnect->execute();
        return $userConnect;
    }

    public function AddUser($email, $password, $firstname,$lastname,$gender, $address,$city,$province,$zipcode,
                            $dateofbirth,$occupation,$phone1,$extension1,$type1,$phone2,$extension2,$type2,
                            $phone3,$extension3,$type3){
        $sql = ' CALL RegisterUser(:email, :password, :firstname,:lastname,:gender, :address,:city,:province,:zipcode,
        :dateofbirth,:occupation,:phone1,:extension1,:type1,:phone2,:extension2,:type2,:phone3,:extension3,:type3)';
        $registerUser = self::getConnexion()->prepare($sql);
        $registerUser->bindParam('email',$email, PDO::PARAM_STR);
        $registerUser->bindParam('password',$password, PDO::PARAM_STR);
        $registerUser->bindParam('firstname',$firstname, PDO::PARAM_STR);
        $registerUser->bindParam('lastname',$lastname, PDO::PARAM_STR);
        $registerUser->bindParam('gender',$gender, PDO::PARAM_STR);
        $registerUser->bindParam('address',$address, PDO::PARAM_STR);
        $registerUser->bindParam('city',$city, PDO::PARAM_STR);
        $registerUser->bindParam('province',$province, PDO::PARAM_INT);
        $registerUser->bindParam('zipcode',$zipcode, PDO::PARAM_STR);
        $registerUser->bindParam('dateofbirth',$dateofbirth, PDO::PARAM_STR);
        $registerUser->bindParam('occupation',$occupation, PDO::PARAM_STR);
        $registerUser->bindParam('phone1',$phone1, PDO::PARAM_STR);
        $registerUser->bindParam('extension1',$extension1, PDO::PARAM_STR);
        $registerUser->bindParam('type1',$type1, PDO::PARAM_INT);
        $registerUser->bindParam('phone2',$phone2, PDO::PARAM_STR);
        $registerUser->bindParam('extension2',$extension2, PDO::PARAM_STR);
        $registerUser->bindParam('type2',$type2, PDO::PARAM_INT);
        $registerUser->bindParam('phone3',$phone3, PDO::PARAM_STR);
        $registerUser->bindParam('extension3',$extension3, PDO::PARAM_STR);
        $registerUser->bindParam('type3',$type3, PDO::PARAM_INT);
        $registerUser->execute();
    }

    public function GetProvinces(){
        $sql = 'SELECT id_state, Name FROM states ORDER BY name';
        $result = self::getConnexion()->query($sql);
        return $result;
    }

    public function GetPhoneType(){
        $sql = 'SELECT * FROM phone_type ORDER BY name';
        $result = self::getConnexion()->query($sql);
        return $result;
    }

    public function UpdatePassword($password,$userid){
        $sql = 'CALL UpdatePassword(:password, :userid)';
        $updatePassword = self::getConnexion()->prepare($sql);
        $updatePassword->bindParam('password',$password, PDO::PARAM_STR);
        $updatePassword->bindParam('userid',$userid, PDO::PARAM_INT);
        $updatePassword->execute();
    }

    public function GetPassword($userid){
        $sql = 'CALL GetPassword(:userid)';
        $password = self::getConnexion()->prepare($sql);
        $password->bindParam('userid',$userid,PDO::PARAM_INT);
        $password->execute();
        if($donnees = $password->fetch())
        {
            return $donnees['password'];
        }else{
            return'';
        }
    }

    public function GetPersonalInformation($userid){
        $sql = 'CALL GetPersonnalInformation(:userid)';
        $personalInformation = self::getConnexion()->prepare($sql);
        $personalInformation->bindParam('userid',$userid,PDO::PARAM_INT);
        $personalInformation->execute();
        return $personalInformation;
    }

    public function UpdateUser($userid, $address,$city,$province,$zipcode,
    $occupation,$phone1,$extension1,$type1,$phone2,$extension2,$type2,
    $phone3,$extension3,$type3){
        $sql = ' CALL UpdateUser(:userid, :address,:city,:province,:zipcode,:occupation,
        :phone1,:extension1,:type1,:phone2,:extension2,:type2,:phone3,:extension3,:type3)';
        $registerUser = self::getConnexion()->prepare($sql);
        $registerUser->bindParam('userid',$userid, PDO::PARAM_INT);
        $registerUser->bindParam('address',$address, PDO::PARAM_STR);
        $registerUser->bindParam('city',$city, PDO::PARAM_STR);
        $registerUser->bindParam('province',$province, PDO::PARAM_INT);
        $registerUser->bindParam('zipcode',$zipcode, PDO::PARAM_STR);
        $registerUser->bindParam('occupation',$occupation, PDO::PARAM_STR);
        $registerUser->bindParam('phone1',$phone1, PDO::PARAM_STR);
        $registerUser->bindParam('extension1',$extension1, PDO::PARAM_STR);
        $registerUser->bindParam('type1',$type1, PDO::PARAM_INT);
        $registerUser->bindParam('phone2',$phone2, PDO::PARAM_STR);
        $registerUser->bindParam('extension2',$extension2, PDO::PARAM_STR);
        $registerUser->bindParam('type2',$type2, PDO::PARAM_INT);
        $registerUser->bindParam('phone3',$phone3, PDO::PARAM_STR);
        $registerUser->bindParam('extension3',$extension3, PDO::PARAM_STR);
        $registerUser->bindParam('type3',$type3, PDO::PARAM_INT);
        $registerUser->execute();
    }
}
?>