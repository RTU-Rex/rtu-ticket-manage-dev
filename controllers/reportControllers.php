<?php
include "dbConnect.php";

    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    if(isset($_POST['getReport'])){
        $sql = "SELECT Stas, numbers, ROUND((numbers/totals) * 100, 2) as percents FROM
                (SELECT Stas, COUNT(*) numbers 
                    FROM (SELECT CASE WHEN Isnull(b.technicianId) then 'OPEN - Unassign' ELSE c.statusName END Stas 
                        FROM tblTicket a 
                        LEFT JOIN  (SELECT *, ROW_NUMBER() OVER(PARTITION BY ticketId ORDER by dateModified DESC) AS row_num 
                                    FROM `tblTicketHistory`) b on a.Id = b.ticketId and row_num = 1
                        LEFT JOIN tblStatus c on c.id = b.ticketStatus
                        WHERE Year(a.DateCreated) = year(now())) a
                GROUP by Stas) a LEFT JOIN (SELECT COUNT(*) as Totals From tblTicket WHERE Year(DateCreated) = year(now())) b on 1=1;";

		$result = mysqli_query($conn, $sql);
    	if (mysqli_num_rows($result) >= 1) {
            $value = array();
            $int = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $value[$int] =  array("stas" => $row['Stas'],"numbers" => $row['percents'] );
                $int = $int + 1;
            }           
            echo json_encode($value);
          
		}
    }

    if(isset($_POST['getReportPrio'])){
        $sql = "SELECT b.priorityName, COUNT(*) as numbers FROM tblTicket a 
        left join tblPriority b on a.priority = b.id 
        WHERE Year(DateCreated) = year(now())
        GROUP BY b.priorityName;";

		$result = mysqli_query($conn, $sql);
    	if (mysqli_num_rows($result) >= 1) {
            $value = array();
            $int = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $value[$int] =  array("stas" => $row['priorityName'],"numbers" => $row['numbers'] );
                $int = $int + 1;
            }           
            echo json_encode($value);
          
		}
    }

    if(isset($_POST['getReportSummary'])){
        $sql = "SELECT MONTHNAME(DateCreated) as Months, COUNT(*) numbers
                FROM tblTicket 
                WHERE Year(DateCreated) = YEAR(NOW())
                GROUP BY MONTHNAME(DateCreated);";

		$result = mysqli_query($conn, $sql);
    	if (mysqli_num_rows($result) >= 1) {
            $value = array();
            $int = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $value[$int] =  array("stas" => $row['Months'],"numbers" => $row['numbers'] );
                $int = $int + 1;
            }           
            echo json_encode($value);
          
		}
    }
  

?>