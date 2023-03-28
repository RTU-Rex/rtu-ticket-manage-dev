<?php 
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['email'])) {
    include 'header.php';  
 ?>

<?php include 'message.php' ?>
                <!-- Begin Page Content -->
                <div class="container-fluid">
                <div class="row">
                    <div class="col"><h1 class="h3 mb-2 text-gray-800">Office and Department Management</h1> </div>
                    <div  class="col"> <button style="float:right;" class="btn btn-warning" data-toggle="modal" data-target="#TicketModal" onClick="NewOffice()"> ADD NEW OFFICE</button></div>


                </div>
              <br>

               

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Department List</h6>
                        </div>
                        <div class="card-body">
                            <div id="divTable" class="table-responsive">
                                <table class="table table-bordered" id="dataTable1" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>DEPARTMENT</th>
                                            <th>OFFICE</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                        <th>ID</th>
                                            <th>DEPARTMENT</th>
                                            <th>OFFICE</th>
                                         
                                        </tr>
                                    </tfoot>
                                    <tbody>

                                    <?php   
                                    
                                    include "./controllers/dbConnect.php";   
                                     $sql = "SELECT Id, Department, Office FROM tblDepartment";
                     
                         $result = mysqli_query($conn, $sql);
                        
                         if (mysqli_num_rows($result) >= 1) {
                          
                             while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr><td><button class="btn btn-link" data-toggle="modal" data-target="#TicketModal" onClick="editOffice('.$row['Id'].')">'.$row['Id'].'</button></td>';
                                echo '<td>'.$row['Department'].'</td>';
                                echo '<td>'.$row['Office'].'</td></tr>';                                
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

    function editOffice(id) {
        $('#divTitle').html("UPDATE OFFICE AND DEPARTMENT");
        $('#divMessage').html("<div class='form-group'><input type='text' class='form-control form-control-user' id='txtOffice' placeholder='Email'></div>"+
                              "<div class='form-group'><select class='form-control form-control-user' id='cmbDept'></select></div>" );
        $('#divButtons').html(" <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button><button type='button' class='btn btn-warning'  onclick='updateDept("+ id +")' data-dismiss='modal'>Update</button>");
            

        $.ajax({
            async: false,
            type: "POST",
            url: 'controllers/DeptControllers.php',
            data: {getDept: 1},
            success: function(data) {
                data = JSON.parse(data);
                $("#cmbDept").empty();
                var cmbInc = document.getElementById("cmbDept");
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

        $.ajax({
            async: false,
            type: "POST",
            url: 'controllers/DeptControllers.php',
            data: {getOfficeDetails: 1, officeId: id},
            success: function(data) {
                data = JSON.parse(data);
                for (var i=0; i< data.length; i++ ) {
                    $('#txtOffice').val(data[i].Office) 
                    $('#cmbDept').val(data[i].Department) 

                }
                
            }, 
            error: function (e) {
                alert(e);
            }
        });
    
    }

    function CreateDept() {
        $('#divTitle').html("RTU Ticketing Message"); 
        var department = $('#cmbDept').val();
        if ($('#cmbDept').val() == -1) {
            department = $('#txtDepartment').val()
        }

        $.ajax({
            async: false,
            type: "POST",
            url: 'controllers/DeptControllers.php',
            data: { txtOffice: $('#txtOffice').val(), 
                    cmbDept: department,
                    addDept: 1
                },
            success: function(data) {
                data = JSON.parse(data);
                $('#divMessage').html(data);
                $('#divButtons').html(" <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>");
            }, 
            error: function (e) {
                alert(e);
            }
        });
        location.reload();

    }

    function NewOffice() {
        $('#divTitle').html("UPDATE OFFICE AND DEPARTMENT");
        $('#divMessage').html("<div class='form-group'><input type='text' class='form-control form-control-user' id='txtOffice' placeholder='Office Name'></div>"+
        "<div class='form-group'><select class='form-control form-control-user' onChange='hideText()' id='cmbDept'></select></div>"+
                              "<div class='form-group' id='newdept'><input type='text' class='form-control form-control-user' id='txtDepartment' placeholder='Department Name'></div>" );
        $('#divButtons').html(" <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button><button type='button' class='btn btn-warning'  onclick='CreateDept()' data-dismiss='modal'>Save</button>");
            

        var element = document.getElementById("newdept");
        element.style.visibility = "hidden";


        $.ajax({
            async: false,
            type: "POST",
            url: 'controllers/DeptControllers.php',
            data: {getDept: 1},
            success: function(data) {
                data = JSON.parse(data);
                $("#cmbDept").empty();
                var cmbInc = document.getElementById("cmbDept");
                var option = document.createElement("option");
                    option.text = 'SELECT DEPARTMENT';
                    option.value = 0;
                    cmbInc.add(option);
                for (var i=0; i< data.length; i++ ) {
                    var option = document.createElement("option");
                    option.text = data[i].name;
                    option.value = data[i].id;
                    cmbInc.add(option);
                }
                var option = document.createElement("option");
                    option.text = 'NEW DEPARTMENT';
                    option.value = -1;
                    cmbInc.add(option);
            }, 
            error: function (e) {
                alert(e);
            }
        });

    
    }

    function hideText() {
        if ($('#cmbDept').val() == -1) {
            var element = document.getElementById("newdept");
            element.style.visibility = "visible";
        } else {
            var element = document.getElementById("newdept");
            element.style.visibility = "hidden";

        }

    }

    function updateDept(id) {
        $('#divTitle').html("RTU Ticketing Message"); 
        
        $.ajax({
            async: false,
            type: "POST",
            url: 'controllers/DeptControllers.php',
            data: { txtOffice: $('#txtOffice').val(), 
                    cmbDept: $('#cmbDept').val(),
                    DeptId: id,
                    updateDept: 1
                },
            success: function(data) {
                data = JSON.parse(data);
                $('#divMessage').html(data);
                $('#divButtons').html(" <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>");
            }, 
            error: function (e) {
                alert(e);
            }
        });
        location.reload();
    }
    

    </script>

</body>

</html>

<?php 
}else{
     header("Location: login.php");
     exit();
}
 ?>