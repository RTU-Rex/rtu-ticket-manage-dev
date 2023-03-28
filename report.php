<?php 
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['email'])) {
    include 'header.php';  
 ?>




                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                                    <div class="col-xl-3 col-md-3 mb-4">
                                        <div class="card border-left-primary shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                            Critical Priority</div>
                                                        <div id="divCritical" class="h5 mb-0 font-weight-bold text-gray-800">1</div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Earnings (Monthly) Card Example -->
                                    <div class="col-xl-3 col-md-3 mb-4">
                                        <div class="card border-left-success shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                            High Priority</div>
                                                        <div id="divHigh" class="h5 mb-0 font-weight-bold text-gray-800">3</div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                      <!-- Earnings (Monthly) Card Example -->
                                      <div class="col-xl-3 col-md-3 mb-4">
                                        <div class="card border-left-info shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Moderate
                                                        </div>
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col-auto">
                                                                <div id="divModerate" class="h5 mb-0 mr-3 font-weight-bold text-gray-800">0</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pending Requests Card Example -->
                                    <div class="col-xl-3 col-md-3 mb-4">
                                        <div class="card border-left-warning shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                            Low</div>
                                                        <div id="divLow" class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                      
                      
                    </div>

                    <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Ticket Summary</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-bar">
                                        <canvas id="myBarChart"></canvas>
                                    </div>
                        
                                </div>
                            </div>


                    <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Ticket Status</h6>
                                </div>
                                <div id="divStatusReport" class="card-body">
                                   
                                </div>
                            </div>

                 

                </div>
                <!-- /.container-fluid -->

          

   

    <?php   include 'footer.php';      ?>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
    <script src="js/demo/chart-bar-demo.js"></script>

    <script> 
  
    $(document).ready(function() {
        $.ajax({
               async: false,
               type: "POST",
               url: 'controllers/reportControllers.php',
               data: {getReport: 1},
               success: function(data) {
                data = JSON.parse(data);
                   for (var i=0; i< data.length; i++ ) {
                    var colorbar = "";
                    if (data[i].stas == 'OPEN - Unassign') {
                        colorbar = "bg-danger";
                    } else if (data[i].stas == 'Closed') {
                        colorbar = "bg-success";
                    } else if (data[i].stas == 'In Progress') {
                            colorbar = "bg-info";
                    } else if (data[i].stas == 'Resolved') {
                            colorbar = "bg-warning";
                    } else {
                            colorbar = "";
                    }

                     document.getElementById("divStatusReport").innerHTML += " <h4 class='small font-weight-bold'>"+ data[i].stas +" <span class='float-right'>"+ data[i].numbers +"%</span></h4>" +
                                    "<div class='progress mb-4'><div class='progress-bar "+ colorbar +"' role='progressbar' style='width: "+ data[i].numbers +"%' aria-valuenow='"+ data[i].numbers +"' aria-valuemin='0' aria-valuemax='100'></div>" +
                                    "</div>"; 
                    }
                
               }
           });

           $('#divLow').html("0")
           $('#divModerate').html("0")
           $('#divHigh').html("0")
           $('#divCritical').html("0")

           $.ajax({
               async: false,
               type: "POST",
               url: 'controllers/reportControllers.php',
               data: {getReportPrio: 1},
               success: function(data) {
                data = JSON.parse(data);
                   for (var i=0; i< data.length; i++ ) {
                        if (data[i].stas == 'Critical') {
                            $('#divCritical').html(data[i].numbers)
                        } else if (data[i].stas == 'High') {
                            $('#divHigh').html(data[i].numbers)
                        } else if (data[i].stas == 'Moderate') {
                            $('#divModerate').html(data[i].numbers)
                        } else {
                            $('#divLow').html(data[i].numbers)
                        }
                    }
                
               }
           });

   })

    </script>

</body>

</html>

<?php 
}else{
     header("Location: login.php");
     exit();
}
 ?>