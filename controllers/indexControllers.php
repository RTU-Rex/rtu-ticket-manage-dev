<?php
include "dbConnect.php";

    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if(isset($_POST['createTicket'])){
        $email = validate($_POST['txtEmail']);
        $empId = validate($_POST['txtEmp']);
        $name = validate($_POST['txtEmpName']);
        $incident = validate($_POST['cmbIncident']);
        $department = validate($_POST['cmbDepartment']);
        $title = validate($_POST['txtTitle']);
        $description = validate($_POST['txtdescription']);

    
        $sql = "INSERT INTO tblTicket ( email, name, empId, department, description, incident, title) VALUES ('$email','$name','$empId','$department','$description','$incident','$title');";
        if(mysqli_query($conn, $sql)) {
            $id =  mysqli_insert_id($conn);
            $message = "You successfully created ticket. Please take note of this reference number. $id";

            echo json_encode($message);

        }
    }

    if(isset($_POST['createFeedBack'])){
        $name = validate($_POST['txtEmpName']);
        $description = validate($_POST['txtdescription']);
        $sql = "INSERT INTO tbFeedback ( name, feedback) VALUES ('$name','$description');";
        if(mysqli_query($conn, $sql)) {
            $message = "Thank you and we appreciate your feedback";

            echo json_encode($message);

        }
    }

    if(isset($_POST['getDept'])){
        $sql = "SELECT DISTINCT Department FROM tblDepartment WHERE 1;";
		$result = mysqli_query($conn, $sql);
    	if (mysqli_num_rows($result) >= 1) {
            $value = array();
            $int = 0;
            while ($row = mysqli_fetch_assoc($result)) {

                $value[$int] =  $row['Department'];
                $int = $int + 1;
            }           
            echo json_encode($value);
          
		}
    }

    if(isset($_POST['getOffice'])){
        $department = validate($_POST['department']);
        $sql = "SELECT Id, Office FROM tblDepartment WHERE department = '$department'";
		$result = mysqli_query($conn, $sql);
    	if (mysqli_num_rows($result) >= 1) {
            $value = array();
            $int = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $value[$int] =  array("id" => $row['Id'],"name" => $row['Office'] );
                $int = $int + 1;
            }           
            echo json_encode($value);
          
		}
    }

    if(isset($_POST['getIncident'])){
        $sql = "SELECT * FROM tblIncident WHERE 1;";
		$result = mysqli_query($conn, $sql);
    	if (mysqli_num_rows($result) >= 1) {
            $value = array();
            $int = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $value[$int] =  array("id" => $row['id'],"name" => $row['IncidentName'] );
                $int = $int + 1;
            }           
            echo json_encode($value);
          
		}
    }

    if(isset($_POST['getTicketsJourney'])){
        $ticketId = validate($_POST['ticketId']);
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
                                        "DateCreated" => $row['DateCreated'] );
                $int = $int + 1;
            }           
            echo json_encode($value);
          
		}
    }

    if(isset($_POST['getTicketStatus'])){
        $sql = "SELECT id, statusName FROM tblStatus where isActive = 1;";
		$result = mysqli_query($conn, $sql);
    	if (mysqli_num_rows($result) >= 1) {
            $value = array();
            $int = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $value[$int] =  array("id" => $row['id'],"name" => $row['statusName'] );
                $int = $int + 1;
            }           
            echo json_encode($value);
          
		}
    }

    if(isset($_POST['createJourney'])){
        $ticketId = validate($_POST['ticketId']);
        $txtdescription = validate($_POST['txtdescription']);
        $modified = validate($_POST['modified']);
        $tech = validate($_POST['tech']);
        $status = validate($_POST['status']);


        $sql = "INSERT INTO tblTicketHistory ( ticketId, ticketStatus ,ticketMessage, modifiedFrom, technicianId ,fileAttach) VALUES ('$ticketId', CASE WHEN $status = 0 THEN NULL ELSE $status END ,'$txtdescription','$modified', CASE WHEN $tech = 0 THEN NULL ELSE $tech END ,'1');";
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

        $sql = "SELECT a.ticketMessage, IFNULL(a.ticketStatus,0) Statusid , b.statusName, c.name, a.dateModified, IFNULL(a.technicianId,0) as TechId ,CONCAT(e.accessName,'(', d.firstName,' ', d.lastName, ')') AS Tech, a.modifiedFrom 
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
                                       "TechId" => $row['TechId'],
                                       "statusId" => $row['Statusid'],
                                       "Tech" => $row['Tech'] );
               $int = $int + 1;
           }           
           echo json_encode($value);
         
       }
    }

?>