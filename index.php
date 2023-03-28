<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>RTU Ticketing Management System</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="shortcut icon" type="x-icon" href="../RTU_ticket_draft/img/rtulogo.png">

</head>
<script src="js/jquery-3.6.3.min.js"></script>
<body>

    <div class="container">
  
  <?php include 'message.php' ?>


        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-12 col-lg-12 col-md-12">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                           
                            <div class="col-lg-8">
                                <div class="p-5">
                                    <div class="text-left">
                                        <h1 class="font-weight-bold text-primary">Welcome to Rizal Technological University Ticketing Management System</h1>
                                        <p class="mb-4">You can now request techincal services and issue virtully</p>
                                    </div>
                                    <form class="user">
                                        <a href="login.html" id="btnNewTicket" class="btn btn-warning btn-user btn-block" data-toggle="modal" data-target="#TicketModal">
                                            Create Ticket
                                        </a>
                                        <br>
                                        <div class="form-group">
                                        <div class="row">
                                            <div class="col-lg-7"><input type="text" class="form-control form-control-user" id="txtTickNumber" placeholder="Enter Ticket Number"></div>
                                            <div class="col-lg-5"> <a href="login.html" id="btnView" data-toggle="modal" data-target="#TicketModal" class="btn btn-warning btn-user btn-block">View Ticket History</a></div>
                                        </div>
                                        </div>
                                        <hr>
                                        <p class="mb-4">Help us to be better in serving you.
                                        <button type="button" id="btnFeedback" class="btn btn-warning btn-user btn-block" data-toggle="modal" data-target="#TicketModal">Give us feedback</button>
                                        </p>
                                    </form>
                                  
                                </div>
                           
                            </div>
                            <div class="col-lg-4 d-none d-lg-block bg-password1-image">

                            <div class="d-flex align-items-center justify-content-center">
               
               <img src="../RTU_ticket_draft/img/RTUTicketHub.png" >

           </div>
         
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script> 
    $(document).ready(function() {
       
       
        $('#btnNewTicket').click(function(e) {   

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
          

           
        })

        $('#btnFeedback').click(function(e) {   
  

            $('#divTitle').html("SUMBIT TICKET");
            $('#divMessage').html("<p class='mb-4'>Help us to be better in serving you.</p> " +
                                "<div class='form-group'><input type='text' class='form-control form-control-user' id='txtEmpName' placeholder='Complete Name'></div>" +
                                "<div class='form-group'><textarea class='form-control' rows='5' id='txtdescription' placeholder='Feedback'></textarea></div>");

            $('#divButtons').html(" <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>" +
                                "  <button type='button' onclick='createFeedback()' class='btn btn-warning' id='btnSubmitFeed'>Submit</button>");
        })

        $('#btnView').click(function(e) {   
            var error = true;
            $('#divTitle').html("Ticket Journey");
            $.ajax({
                async: false,
                type: "POST",
                url: 'controllers/indexControllers.php',
                data: {ticketId: $('#txtTickNumber').val(), getTicketsJourney: 1},
                success: function(data) {
                   
                    data = JSON.parse(data);
                    for (var i=0; i< data.length; i++ ) {
                        $('#divMessage').html("<div class='card shadow mb-4'> "+
                                   "<div class='card-header py-3'><h6 class='m-0 font-weight-bold text-primary'>RTU sysTicket</h6></div>" +
                                   "<div class='card-body'> Ticket Number: "+ data[i].id +" <br> Incident: "+ data[i].IncidentName +" <br> Title: "+ data[i].title +" <br> Description: "+ data[i].description +" <br> Requestor: "+ data[i].name +" ("+ data[i].email +") <br> Department: "+ data[i].Office + 
                                   "<hr> <p class='mb-1'>"+ data[i].DateCreated +"</p></div></div>");
                    error = false;
                    }
                 
                }
            });

            if (error) {
                $('#divMessage').html("Please enter valid ticket number.");
                $('#divButtons').html("<button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>");

            } else {
                let techid = 0;
                let status = 0;
                $.ajax({
                async: false,
                type: "POST",
                url: 'controllers/indexControllers.php',
                data: {ticketId: $('#txtTickNumber').val(), getTicketsJourneyHistory: 1},
                success: function(data) {
                   
                    data = JSON.parse(data);
                    for (var i=0; i< data.length; i++ ) {
                        if (data[i].modifiedFrom == "requestor") {
                            document.getElementById("divMessage").innerHTML += "<div class='card shadow mb-4'> "+
                                   "<div class='card-header py-3'><h6 class='m-0 font-weight-bold text-right text-warning'>"+ data[i].name +"</h6></div>" +
                                   "<div class='card-body'>"+ data[i].ticketMessage +
                                   "<hr> <p class='mb-1 text-right'>"+ data[i].dateModified +" - "+ data[i].statusName +"</p></div></div>";
                        } else {
                            document.getElementById("divMessage").innerHTML += "<div class='card shadow mb-4'> "+
                                   "<div class='card-header py-3'><h6 class='m-0 font-weight-bold text-left text-primary'>"+ data[i].Tech +"</h6></div>" +
                                   "<div class='card-body'>"+ data[i].ticketMessage +
                                   "<hr> <p class='mb-1 text-left'>"+ data[i].dateModified +" - "+ data[i].statusName +"</p></div></div>";
                        }
                      techid = data[i].TechId;
                      statusId = data[i].statusId;
                    }
                
                }
            });
            
            $('#divButtons').html("<button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>"+
                                  "<button type='button' class='btn btn-warning' onclick='replyTicket("+ techid +","+ statusId +")' id='btnUpdate'>Reply</button>" );

                 
            }
          
        })
        
    })
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
                    $('#divButtons').html(" <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>");
                }, 
                error: function (e) {
                    alert(e);
                }
            })
    }

    function createFeedback() {
        $('#divTitle').html("RTU Ticketing Message"); 
            $.ajax({
                async: false,
                type: "POST",
                url: 'controllers/indexControllers.php',
                data: {txtEmpName: $('#txtEmpName').val(),
                       txtdescription: $('#txtdescription').val(),
                       createFeedBack: 1
                    },
                success: function(data) {
                    data = JSON.parse(data);
                    $('#divMessage').html(data)
                    $('#divButtons').html(" <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>");
                   
                }, 
                error: function (e) {
                    alert(e);
                }
            })
    }
   
    function getOffice() {
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
            })
    }

    function replyTicket(tickedId,StatusId) {
        $('#divTitle').html("UPDATE TICKET");
        $('#divMessage').html("<div class='form-group'><textarea class='form-control' rows='5' id='txtdescription' placeholder='Description'></textarea></div>" );
        $('#divButtons').html("<button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>"+
                              "<button type='button' class='btn btn-warning' onclick='updateTicket("+tickedId+","+ StatusId +")' id='btnUpdate'>Send</button>" );
    }

    function updateTicket(tickedId,StatusId) {
        $('#divTitle').html("RTU Ticketing Message"); 
            $.ajax({
                async: false,
                type: "POST",
                url: 'controllers/indexControllers.php',
                data: {ticketId: $('#txtTickNumber').val(),
                       cmbStatus: $('#cmbStatus').val(),
                       txtdescription: $('#txtdescription').val(),
                       modified: "requestor",
                       tech: tickedId,
                       status: StatusId,
                       createJourney: 1
                    },
                success: function(data) {
                    data = JSON.parse(data);
                    $('#divMessage').html(data);
                    $('#divButtons').html(" <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>");
                }, 
                error: function (e) {
                    alert(e);
                }
            })
    }
    </script>
</body>

</html>