<?php
session_start(); 
include "dbConnect.php";

    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    if(isset($_POST['gettickets'])){
        $accessid = $_SESSION['accessId'];
        $user = "1";
        if ($accessid == 2) {
            $user = $_SESSION['email'];
        }
        

        $sql = "SELECT CASE WHEN Isnull(b.technicianId) then 'Unassign' ELSE c.statusName END Stas,
                        a.title, a.description, e.IncidentName, a.Id, f.priorityName, g.Office,
                        CASE WHEN ISNULL(b.datemodified) then a.DateCreated else b.datemodified end lastUpdate
                FROM tblTicket a 
                LEFT JOIN  (SELECT *, ROW_NUMBER() OVER(PARTITION BY ticketId ORDER by dateModified DESC) AS row_num 
                            FROM `tblTicketHistory`) b on a.Id = b.ticketId and row_num = 1
                LEFT JOIN tblStatus c on c.id = b.ticketStatus
                LEFT JOIN tblUser d on d.id = b.technicianId
                LEFT JOIN tblIncident e on e.id = a.incident
                LEFT JOIN tblPriority f on f.id = a.priority
                LEFT JOIN tblDepartment g on g.id = a.department
                WHERE NOT(CASE WHEN ISNULL(c.id) THEN 5 ELSE c.id END = 4) AND (CASE WHEN c.id = 1 THEN d.email ELSE 1 END) = '$user';";
       $result = mysqli_query($conn, $sql);
       if (mysqli_num_rows($result) >= 1) {
           $value = array();
           $int = 0;
           while ($row = mysqli_fetch_assoc($result)) {
               $value[$int] =  array(  "Id" => $row['Id'],
                                       "Stas" => $row['Stas'],
                                       "title" => $row['title'],
                                       "description" => $row['description'],
                                       "IncidentName" => $row['IncidentName'],
                                       "Office" => $row['Office'],
                                       "lastUpdate" => $row['lastUpdate']);
               $int = $int + 1;
           }           
           echo json_encode($value);
         
       }
    }


    if(isset($_POST['getPrio'])){
        $sql = "SELECT * FROM tblPriority";
		$result = mysqli_query($conn, $sql);
    	if (mysqli_num_rows($result) >= 1) {
            $value = array();
            $int = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $value[$int] =  array("id" => $row['id'],"name" => $row['priorityName'] );
                $int = $int + 1;
            }           
            echo json_encode($value);
          
		}
    }

    if(isset($_POST['getTech'])){
        $sql = "SELECT id, CONCAT(lastName,', ',firstName) as name FROM tblUser WHERE not(accessId = 1);";
		$result = mysqli_query($conn, $sql);
    	if (mysqli_num_rows($result) >= 1) {
            $value = array();
            $int = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $value[$int] =  array("id" => $row['id'],"name" => $row['name'] );
                $int = $int + 1;
            }           
            echo json_encode($value);
          
		}
    }

    if(isset($_POST['getTicketDetails'])){
        $ticketId = validate($_POST['ticketId']);
 
        $sql = "SELECT 	email,
                        name,
                        b.Department,
                        a.department as Offices,
                        description,
                        incident,
                        IFNULL(priority,0) priority,
                        IFNULL(contactType,0) contactType,
                        title 
                FROM tblTicket a
                LEFT JOIN tblDepartment b 
                ON a.department = b.id
                WHERE a.id = $ticketId;";
       $result = mysqli_query($conn, $sql);
       if (mysqli_num_rows($result) >= 1) {
           $value = array();
           $int = 0;
           while ($row = mysqli_fetch_assoc($result)) {
               $value[$int] =  array(  "email" => $row['email'],
                                       "name" => $row['name'],
                                       "Department" => $row['Department'],
                                       "Offices" => $row['Offices'],
                                       "description" => $row['description'],
                                       "incident" => $row['incident'],
                                       "priority" => $row['priority'],
                                       "contactType" => $row['contactType'],
                                       "title" => $row['title']);
               $int = $int + 1;
           }           
           echo json_encode($value);
         
       }
    }

    if(isset($_POST['updateTicket'])){

        $sessionId = $_SESSION['id'];
        $id = validate($_POST['ticketId']);
        $email = validate($_POST['txtEmail']);
        $name = validate($_POST['txtEmpName']);
        $incident = validate($_POST['cmbIncident']);
        $department = validate($_POST['cmbDepartment']);
        $title = validate($_POST['txtTitle']);
        $description = validate($_POST['txtdescription']);
        $prio = validate($_POST['cmbPrio']);

    
        $sql = "UPDATE tblTicket 
                SET email='$email', name='$name', 
                    department=$department,
                    description='$description',incident=$incident,
                    modifiedBy=$sessionId,dateModified=CURRENT_TIMESTAMP(),
                    priority=$prio,
                    title='$title' 
                WHERE id=$id;";
        if(mysqli_query($conn, $sql)) {
            $message = "You successfully updated ticket. $id";
            echo json_encode($message);

        }
    }

    if(isset($_POST['createJourney'])){
        $ticketId = validate($_POST['ticketId']);
        $cmbStatus = validate($_POST['cmbStatus']);
        $txtdescription = validate($_POST['txtdescription']);
        $tech = validate($_POST['cmbTech']);
        $sessionId = $_SESSION['id'];
       

        $sql = "INSERT INTO tblTicketHistory 
                            ( ticketId, 
                              ticketStatus, 
                              ticketMessage,
                              technicianId, 
                              modifiedBy,
                              fileAttach) 
                VALUES ($ticketId,$cmbStatus,'$txtdescription',$tech,$sessionId,'1');";
        if(mysqli_query($conn, $sql)) {
            $message = "You successfully updated ticket";
            echo json_encode($message);
        } else {
            $message = "Something went wrong.";
            echo json_encode($message);
        }
    }

    if(isset($_POST['getTicketsJourneyHistory'])){
        $ticketId = validate($_POST['ticketId']);
        $sessionId = $_SESSION['id'];

        $sql = "SELECT a.ticketMessage, b.statusName, c.name, a.dateModified, IFNULL(a.technicianId,0) technicianId  , IFNULL(CONCAT(e.accessName,'(', d.firstName,' ', d.lastName, ')'), 'REQUESTOR') AS Tech,  IFNULL(a.modifiedFrom, CASE WHEN a.modifiedBy = $sessionId then 'User' else 'admin' end ) modifiedFrom
                FROM tblTicketHistory a 
                LEFT JOIN tblStatus b on a.ticketStatus = b.id 
                LEFT JOIN tblTicket c on c.Id = a.ticketId 
                LEFT JOIN tblUser d on d.id = a.modifiedBy
                LEFT JOIN tblAccess e on e.id = d.accessId
                WHERE a.ticketId = $ticketId;";
       $result = mysqli_query($conn, $sql);
       if (mysqli_num_rows($result) >= 1) {
           $value = array();
           $int = 0;
           while ($row = mysqli_fetch_assoc($result)) {
               $value[$int] =  array(  "ticketMessage" => $row['ticketMessage'],
                                       "statusName" => $row['statusName'],
                                       "name" => $row['name'],
                                       "dateModified" => $row['dateModified'],
                                       "modifiedFrom" => $row['modifiedFrom'],
                                       "technicianId" => $row['technicianId'],
                                       "Tech" => $row['Tech'] );
                                       
               $int = $int + 1;
           }           
           echo json_encode($value);
         
       }
    }

    if(isset($_POST['getTicketsJourney'])){
        $ticketId = validate($_POST['ticketId']);
        $accessId = $_SESSION['accessId']; 

        $sql = "SELECT a.Id, email, name, title, description, b.IncidentName, c.Office, a.DateCreated 
                FROM tblTicket a left join tblIncident b on a.incident = b.id 
                left join tblDepartment c on a.department = c.id  WHERE a.Id = $ticketId ";
		$result = mysqli_query($conn, $sql);
    	if (mysqli_num_rows($result) >= 1) {
            $value = array();
            $int = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $value[$int] =  array(  "id" => $row['Id'],
                                        "email" => $row['email'],
                                        "name" => $row['name'],
                                        "title" => $row['title'],
                                        "description" => $row['description'],
                                        "IncidentName" => $row['IncidentName'],
                                        "Office" => $row['Office'],
                                        "access" => $accessId,
                                        "DateCreated" => $row['DateCreated'] );
                $int = $int + 1;
            }           
            echo json_encode($value);
          
		}
    }

?>