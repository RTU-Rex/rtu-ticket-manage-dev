<?php 
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['email'])) {
    include 'header.php';  
 ?>

<?php include 'message.php' ?>
                <!-- Begin Page Content -->
                <div class="container-fluid">
                <div class="row">
                    <div class="col"><h1 class="h3 mb-2 text-gray-800">Ticket Management</h1> </div>
                    <div  class="col"> <button style="float:right;" class="btn btn-warning" data-toggle="modal" data-target="#TicketModal" onClick="ViewCreateTicket()"> ADD NEW TICKET</button></div>


                </div>
<br/>
                   

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Ticket List</h6>
                        </div>
                        <div class="card-body">
                            <div id="divTable" class="table-responsive">
                                <table class="table table-bordered" id="dataTable1" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>STATUS</th>
                                            <th>TITLE</th>
                                            <th>PRIORITY</th>
                                            <th>CATEGORY</th>
                                            <th>TECHNICIAN</th>
                                            <th>REQUESTOR</th>
                                            <th>LAST UPDATE</th>

                                       
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                        <th>ID</th>
                                            <th>STATUS</th>
                                            <th>TITLE</th>
                                            <th>PRIORITY</th>
                                            <th>CATEGORY</th>
                                            <th>TECHNICIAN</th>
                                            <th>REQUESTOR</th>
                                            <th>LAST UPDATE</th>
                                         
                                        </tr>
                                    </tfoot>
                                    <tbody>

                                    <?php   
                                    
                                    include "./controllers/dbConnect.php";   
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
                          
                             while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr><td><button class="btn btn-link" data-toggle="modal" data-target="#TicketModal" onClick="viewTicket('.$row['Id'].')">'.$row['Id'].'</button></td>';
                                echo '<td>'.$row['Stas'].'</td>';
                                echo '<td>'.$row['title'].'</td>';
                                echo '<td>'.$row['priorityName'].'</td>';
                                echo '<td>'.$row['Office'].'</td>';
                                echo '<td>'.$row['Assigned'].'</td>';
                                echo '<td>'.$row['name'].'</td>';
                                echo '<td>'.$row['lastUpdate'].'</td></tr>';                                
                             }           
                          
                         
                         }
                                    
                                    
                                    
                                    ?>
                                

                                
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                  
                 

                </div>
                <!-- /.container-fluid -->

          

   

    <?php   include 'footer.php';      ?>
  <!-- Page level plugins -->
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
      <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>



    <script> 
  
    $(document).ready(function() {
        $('#dataTable1').DataTable(); 
    });

    function ViewCreateTicket() {

        $('#divTitle').html("SUMBIT TICKET");
        $('#divMessage').html("<p class='mb-4'>Create Incident to assists you in your issue</p> <div class='form-group'> " +
                                "<input type='email' class='form-control form-control-user' id='txtEmail' placeholder='Email Address'></div>" +
                                "<div class='form-group'>" +
                                "<input type='text' class='form-control form-control-user' id='txtEmp' placeholder='Employee Number'></div>" +
                                "<div class='form-group'><input type='text' class='form-control form-control-user' id='txtEmpName' placeholder='Complete Name'></div>" +
                                "<div class='form-group'><select class='form-control form-control-user' id='cmbIncident'></select></div>" +
                                "<div class='form-group'><select class='form-control form-control-user' onchange='getOffice()' id='cmbDepartment'></select></div>" +
                                "<div class='form-group'><select class='form-control form-control-user' id='cmbOffice'></select></div>" +
                                "<div class='form-group'><input type='text' class='form-control form-control-user' id='txtTitle' placeholder='Title'><textarea class='form-control' rows='5' id='txtdescription' placeholder='Description'></textarea></div>");
        $('#divButtons').html(" <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>" +
                                "  <button type='button' class='btn btn-warning' onclick='createTicket()' id='btnSubmit'>Submit</button>");
    
       //Retrived incidents
       $.ajax({
                async: false,
                type: "POST",
                url: 'controllers/indexControllers.php',
                data: {getIncident: 1},
                success: function(data) {
                    data = JSON.parse(data);
                    $("#cmbIncident").empty();
                    var cmbInc = document.getElementById("cmbIncident");
                    for (var i=0; i< data.length; i++ ) {
                        var option = document.createElement("option");
                        option.text = data[i].name;
                        option.value = data[i].id;
                        cmbInc.add(option);
                    }
                }, 
                error: function (e) {
                    alert(e);
                }
            });
          
          //Retrived Departments
          $.ajax({
                async: false,
                type: "POST",
                url: 'controllers/indexControllers.php',
                data: {getDept: 1},
                success: function(data) {
                    data = JSON.parse(data);
                    $("#cmbDepartment").empty();
                    var cmbDept = document.getElementById("cmbDepartment");
                    for (var i=0; i< data.length; i++ ) {
                        var option = document.createElement("option");
                        option.text = data[i];
                        option.value = data[i];
                        cmbDept.add(option);
                    }
                }, 
                error: function (e) {
                    alert(e);
                }
            });

            //Retrived Officess 
            $.ajax({
                async: false,
                type: "POST",
                url: 'controllers/indexControllers.php',
                data: {getOffice: 1,department: $('#cmbDepartment').val()},
                success: function(data) {
                   
                    data = JSON.parse(data);
                    $("#cmbOffice").empty();
                    var cmbOffice = document.getElementById("cmbOffice");
                    for (var i=0; i< data.length; i++ ) {
                        var option = document.createElement("option");
                        option.text = data[i].name;
                        option.value = data[i].id;
                        cmbOffice.add(option);
                    }
                  
                }, 
                error: function (e) {
                    alert(e);
                }
            });
    }

    function createTicket() {
        $('#divTitle').html("RTU Ticketing Message"); 
            $.ajax({
                async: false,
                type: "POST",
                url: 'controllers/indexControllers.php',
                data: {txtEmail: $('#txtEmail').val(), 
                       txtEmp: $('#txtEmp').val(),
                       txtEmpName: $('#txtEmpName').val(),
                       cmbIncident: $('#cmbIncident').val(),
                       cmbDepartment: $('#cmbOffice').val(),
                       txtTitle: $('#txtTitle').val(),
                       txtdescription: $('#txtdescription').val(),
                       createTicket: 1
                    },
                success: function(data) {
                    data = JSON.parse(data);
                    $('#divMessage').html(data);
                    $('#divButtons').html(" <button type='button' class='btn btn-secondary' onclick='refresh()' data-dismiss='modal'>Close</button>");
                }, 
                error: function (e) {
                    alert(e);
                }
            })
            location.reload();
    }

   

    </script>

    <script src="./js/ticketManagement.js"></script>



</body>

</html>

<?php 
}else{
     header("Location: login.php");
     exit();
}
 ?>