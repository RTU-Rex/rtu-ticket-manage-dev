<?php
session_start(); 
include "dbConnect.php";

    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if(isset($_POST['getAccess'])){
        $sql = "SELECT id, accessName FROM tblAccess";
		$result = mysqli_query($conn, $sql);
    	if (mysqli_num_rows($result) >= 1) {
            $value = array();
            $int = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $value[$int] =  array("id" => $row['id'],"name" => $row['accessName'] );
                $int = $int + 1;
            }           
            echo json_encode($value);
          
		}
    }
   
    if(isset($_POST['getUserDetails'])){
        $userId = validate($_POST['userId']);
 
        $sql = "SELECT  email, accessId , firstName, lastName, IFNULL(contactNumber,'') contactNumber , 
                        isActive 
                FROM tblUser where id = $userId ;";

       $result = mysqli_query($conn, $sql);
       if (mysqli_num_rows($result) >= 1) {
           $value = array();
           $int = 0;
           while ($row = mysqli_fetch_assoc($result)) {
               $value[$int] =  array(  "email" => $row['email'],
                                       "accessId" => $row['accessId'],
                                       "firstName" => $row['firstName'],
                                       "lastName" => $row['lastName'],
                                       "contactNumber" => $row['contactNumber'],
                                       "isActive" => $row['isActive']);
               $int = $int + 1;
           }           
           echo json_encode($value);
         
       }
    }

    if(isset($_POST['updateUser'])){

        $sessionId = $_SESSION['id'];
        $id = validate($_POST['userId']);
        $txtEmail = validate($_POST['txtEmail']);
        $txtUserFirstName = validate($_POST['txtUserFirstName']);
        $txtUserLastName = validate($_POST['txtUserLastName']);
        $txtUserContact = validate($_POST['txtUserContact']);
        $cmbStatus = validate($_POST['cmbStatus']);
        $cmbAccess = validate($_POST['cmbAccess']);
    
        $sql = "UPDATE tblUser 
                SET email='$txtEmail', modifiedBy='$sessionId',firstName='$txtUserFirstName',lastName='$txtUserLastName',
                    contactNumber='$txtUserContact',
                    accessId=$cmbAccess,
                    isActive=$cmbStatus, 
                    dateModified=CURRENT_TIMESTAMP() 
                WHERE id = '$id';";
        if(mysqli_query($conn, $sql)) {
            $message = "You successfully updated ticket. $id";
            echo json_encode($message);

        }
    }

    if(isset($_POST['addUser'])){
        $sessionId = $_SESSION['id'];
        $txtEmail = validate($_POST['txtEmail']);
        $txtUserFirstName = validate($_POST['txtUserFirstName']);
        $txtUserLastName = validate($_POST['txtUserLastName']);
        $txtUserContact = validate($_POST['txtUserContact']);
        $cmbStatus = validate($_POST['cmbStatus']);
        $cmbAccess = validate($_POST['cmbAccess']);
        $password = password_hash('Password@1234',PASSWORD_DEFAULT);

        $sql = "INSERT INTO tblUser 
                (email, password, createdBy,firstName, lastName, contactNumber, accessId, isActive) 
                VALUES ('$txtEmail','$password','$sessionId','$txtUserFirstName','$txtUserLastName','$txtUserContact','$cmbAccess','$cmbStatus');";
        if(mysqli_query($conn, $sql)) {
            $message = "You Successfully Created a new User";

            echo json_encode($message);

        }
    }
  

?>