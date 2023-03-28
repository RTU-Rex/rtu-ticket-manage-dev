<?php
session_start(); 
include "dbConnect.php";

    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    if(isset($_POST['getLoginDetails'])){
        $email = validate($_POST['txtEmail']);
        $password = validate($_POST['txtpassword']);

        $sql = "SELECT id, email, firstName, lastName, accessId, password FROM tblUser WHERE email = '$email';";
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) === 1) {
			$row = mysqli_fetch_assoc($result);
            $hashcheck = password_verify($password, $row['password']);
                if ($hashcheck) {

                    $_SESSION['id'] = $row['id'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['firstName'] = $row['firstName'];
                    $_SESSION['lastName'] = $row['lastName'];
                    $_SESSION['accessId'] = $row['accessId'];
    
                    $message = "1";
                    echo json_encode($message);

                } else {
                    $message = "Please make sure you input correct username and password.";
                    echo json_encode($message);

                }
              
		}else{
            $message = "Please make sure you input correct username and password.";
            echo json_encode($message);
		}
    }

    if(isset($_POST['getLogout'])){
        session_start();

        session_unset();
        session_destroy();

        $message = "1";
        echo json_encode($message);
    }

  

?>