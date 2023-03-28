<?php
session_start(); 
include "dbConnect.php";

    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if(isset($_POST['getDept'])){
        $sql = "SELECT DISTINCT Department FROM tblDepartment;";
		$result = mysqli_query($conn, $sql);
    	if (mysqli_num_rows($result) >= 1) {
            $value = array();
            $int = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $value[$int] =  array("id" => $row['Department'],"name" => $row['Department'] );
                $int = $int + 1;
            }           
            echo json_encode($value);
          
		}
    }
   
    if(isset($_POST['getOfficeDetails'])){
        $officeId = validate($_POST['officeId']);
 
        $sql = "SELECT  Department, Office FROM tblDepartment WHERE id = $officeId;";

       $result = mysqli_query($conn, $sql);
       if (mysqli_num_rows($result) >= 1) {
           $value = array();
           $int = 0;
           while ($row = mysqli_fetch_assoc($result)) {
               $value[$int] =  array(  "Department" => $row['Department'],
                                       "Office" => $row['Office']);
               $int = $int + 1;
           }           
           echo json_encode($value);
         
       }
    }

    if(isset($_POST['updateDept'])){

        $sessionId = $_SESSION['id'];
        $DeptId = validate($_POST['DeptId']);
        $txtOffice = validate($_POST['txtOffice']);
        $cmbDept = validate($_POST['cmbDept']);
       
        $sql = "UPDATE tblDepartment 
                SET Department='$cmbDept',Office='$txtOffice',modifiedBy=$sessionId,dateModified=CURRENT_TIMESTAMP() 
                WHERE id = $DeptId;";
        if(mysqli_query($conn, $sql)) {
            $message = "You successfully updated ticket. $id";
            echo json_encode($message);

        }
    }

    if(isset($_POST['addDept'])){
        $sessionId = $_SESSION['id'];
        $txtOffice = validate($_POST['txtOffice']);
        $cmbDept = validate($_POST['cmbDept']);

        $sql = "INSERT INTO tblDepartment 
                (Department, Office, createdBy) 
                VALUES ('$cmbDept','$txtOffice',$sessionId);";
        if(mysqli_query($conn, $sql)) {
            $message = "You Successfully Created New Office";

            echo json_encode($message);

        }
    }
  

?>