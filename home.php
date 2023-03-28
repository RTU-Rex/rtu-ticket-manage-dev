<?php 
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['email'])) {
    include 'header.php';  
 ?>



<?php include 'message.php' ?>
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Home</h1>
                    </div>

                    <!-- Content Row -->
                     
                    <div id="divActiveTicket" class="row">
        
                       
                       
                    </div>

                     
                      
                </div>

                <!-- /.container-fluid -->             

    <?php   include 'footer.php';      ?>

    <script> 
  
    $(document).ready(function() {
        ticketAll();
   });

   function ticketAll() {
    document.getElementById("divActiveTicket").innerHTML = "";
    
    $.ajax({
               async: false,
               type: "POST",
               url: 'controllers/homeControllers.php',
               data: {gettickets: 1},
               success: function(data) {
                data = JSON.parse(data);
                if (data.length > 0) {
                    for (var i=0; i< data.length; i++ ) {
                     document.getElementById("divActiveTicket").innerHTML += "<div data-toggle='modal' data-target='#TicketModal' onClick='viewTicket("+ data[i].Id +")' class='col-4'>"  +
                                                    "<div class='card shadow mb-4'>" +
                                                    "<div class='card-header py-3'>" +
                                                    "<h6 class='m-0 font-weight-bold text-warning'>"+ data[i].Stas +" - "+ data[i].Id +"</h6>" +
                                                    "</div>" +
                                                    "<div class='card-body'>" +
                                                    "Incident: "+ data[i].IncidentName +"" +
                                                    "<br> Title: "+ data[i].title +" <br> Description: "+ data[i].description +" <br> Department: "+ data[i].Office +"" +
                                                    "<hr> <p class='mb-1'>"+ data[i].lastUpdate +"</p>"+
                                                    "</div>"+
                                                    "</div>"+
                                                    "</div>";
                    } 
                }
                   
               }, 
               fail: function (e) {
                    document.getElementById("divActiveTicket").innerHTML = "<div class='col-12'> NO PENDING TICKET </div>";
                },
           });

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