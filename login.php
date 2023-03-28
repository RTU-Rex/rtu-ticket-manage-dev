<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>RTU Ticketing System</title>

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
<body >

<div class="modal fade" id="TicketModal" tabindex="-1" role="dialog" aria-labelledby="ticketModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" >
        <h5 class="modal-title font-weight-bold text-primary" id="ticketModalLabel"><div id="divTitle"></div></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="divMessage" class="modal-body">
      </div>
      <div id = "divButtons" class="modal-footer">
       
      </div>
    </div>
  </div>
</div>


    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-12 col-lg-12 col-md-12">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 bg-gradient-primary justify-content-center">

                            <div class="d-flex align-items-center justify-content-center">
               
                                <img src="../RTU_ticket_draft/img/rtulogo.png" >
              
                            </div>
                            <h1 class="h1 mb-0 text-white">
                            Rizal Technological University
                            </h1>
                            <h3 class="h6 mb-0 text-white">
                            Ticketing Management System
                            </h3>
                           
                            </div>
                            <div class="col-lg-6">


                            <form  >
                                    <div class="mb-3 mt-3">
                                    <label for="uname" class="form-label">USERNAME:</label>
                                    <input type="email" class="form-control" id="txtEmail" placeholder="Enter email address" name="uname" required="true">
                                    <div class="invalid-feedback">Please fill out this field.</div>
                                    </div>
                                    <div class="mb-3">
                                    <label for="pwd" class="form-label">Password:</label>
                                    <input type="password" class="form-control" id="txtpassword" placeholder="Enter password" name="pswd" required>
                                  
                                    <div class="invalid-feedback">Please fill out this field.</div>
                                    <a class="small" onclick="getUserDetails()"  href="forgot-password.html">Forgot Password?</a>
                                    </div>
                                   
                                <button onclick="getUserDetails()" type="button" data-toggle="modal" data-target="#TicketModal" class="btn btn-warning btn-user btn-block">Submit</button>
                            </form>
                             
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
  
    function getUserDetails() {
        $('#divTitle').html("RTU Ticketing Message"); 
            $.ajax({
                async: false,
                type: "POST",
                url: 'controllers/loginControllers.php',
                data: { txtEmail: $('#txtEmail').val(), 
                        txtpassword: $('#txtpassword').val(),
                        getLoginDetails: 1
                    },
                success: function(data) {
                    console.log(data)
                    data = JSON.parse(data);
                    if (data == "1") {
                        window.location.href = "home.php";
                        $('#divButtons').html(" <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>");
                    } else { 
                        $('#divMessage').html(data);
                        $('#divButtons').html(" <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>");
                    }
                  
                }
            })
    }

    


    </script>

</body>

</html>