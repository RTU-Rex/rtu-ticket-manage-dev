<?php
include "dbConnect.php";

    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    if(isset($_POST['getTicketTable'])){
        $sql = "SELECT CASE WHEN Isnull(b.technicianId) then 'Unassign' ELSE c.statusName END Stas,
                        a.title, e.IncidentName, a.Id, f.priorityName, g.Office,
                        IFNULL(CONCAT(d.lastName,', ',d.firstName),'---') Assigned,
                        a.name,
                        CASE WHEN ISNULL(b.datemodified) then a.DateCreated else b.datemodified end lastUpdate
                FROM tblTicket a 
                LEFT JOIN  (SELECT *, ROW_NUMBER() OVER(PARTITION BY ticketId ORDER by dateModified DESC) AS row_num 
                            FROM `tblTicketHistory`) b on a.Id = b.ticketId and row_num = 1
                LEFT JOIN tblStatus c on c.id = b.ticketStatus
                LEFT JOIN tblUser d on d.id = b.technicianId
                LEFT JOIN tblIncident e on e.id = a.incident
                LEFT JOIN tblPriority f on f.id = a.priority
                LEFT JOIN tblDepartment g on g.id = a.department
                WHERE YEAR(a.DateCreated) = Year(now());";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) >= 1) {
                $value = array();
                $int = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $value[] =  array(  "Id" => $row['Id'],
                                            "Stas" => $row['Stas'],
                                            "title" => $row['title'],
                                            "priorityName" => $row['priorityName'],
                                            "IncidentName" => $row['IncidentName'],
                                            "Office" => $row['Office'],
                                            "Assigned" => $row['Assigned'],
                                            "name" => $row['name'],
                                            "lastUpdate" => $row['lastUpdate']);
                    $int = $int + 1;
                }           
                echo json_encode($value);
            
            }
    }

   
  

?>