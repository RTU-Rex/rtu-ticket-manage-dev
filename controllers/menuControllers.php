<?php
session_start(); 
include "dbConnect.php";

    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    if(isset($_POST['getMainMenu'])){
        $accessid = $_SESSION['accessId'];
        $sql = "SELECT DISTINCT b.Child FROM tblAccessMenu a 
                left join tblMenu b on a.menuId = b.id 
                left join tblAccess c on c.id = a.accessId 
                where a.accessId = $accessid;";

		$result = mysqli_query($conn, $sql);
    	if (mysqli_num_rows($result) >= 1) {
            $value = array();
            $int = 0;
            while ($row = mysqli_fetch_assoc($result)) {

                $value[$int] =  $row['Child'];
                $int = $int + 1;
            }           
            echo json_encode($value);
          
		}
    }

    if(isset($_POST['getMenu'])){
        $main = validate($_POST['main']);
        $accessid = $_SESSION['accessId'];
        $sql = "SELECT b.menuName, b.URL, icon FROM tblAccessMenu a 
                left join tblMenu b on a.menuId = b.id 
                left join tblAccess c on c.id = a.accessId 
                where a.accessId = $accessid and b.Child = '$main';";
		$result = mysqli_query($conn, $sql);
    	if (mysqli_num_rows($result) >= 1) {
            $value = array();
            $int = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $value[$int] =  array("menuName" => $row['menuName'],"menuURL" => $row['URL'], "icons" => $row['icon'] );
                $int = $int + 1;
            }           
            echo json_encode($value);
          
		}
    }

  

  

?>