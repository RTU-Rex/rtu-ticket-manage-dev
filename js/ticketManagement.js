// for ticket management 

function viewTicket(id) {   
  let access = 0;
  let techid = 0;
  $('#divTitle').html("Ticket Journey");
  $.ajax({
      async: false,
      type: "POST",
      url: 'controllers/homeControllers.php',
      data: {ticketId: id, getTicketsJourney: 1},
      success: function(data) {
         
          data = JSON.parse(data);
          for (var i=0; i< data.length; i++ ) {
              $('#divMessage').html("<div class='card shadow mb-4'> "+
                         "<div class='card-header py-3'><h6 class='m-0 font-weight-bold text-primary'>RTU sysTicket</h6></div>" +
                         "<div class='card-body'> Ticket Number: "+ data[i].id +" <br> Incident: "+ data[i].IncidentName +" <br> Title: "+ data[i].title +" <br> Description: "+ data[i].description +" <br> Requestor: "+ data[i].name +" ("+ data[i].email +") <br> Department: "+ data[i].Office + 
                         "<hr> <p class='mb-1'>"+ data[i].DateCreated +"</p></div></div>");
              access = data[i].access;
          }
      }
  });

  $.ajax({
      async: false,
      type: "POST",
      url: 'controllers/homeControllers.php',
      data: {ticketId: id, getTicketsJourneyHistory: 1},
      success: function(data) {
         console.log(data);
          data = JSON.parse(data);
          for (var i=0; i< data.length; i++ ) {
              if (data[i].modifiedFrom == "User") {
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
              techid = data[i].technicianId
          }
      }
  });
  
  if (access == 2) {
      $('#divButtons').html("<button type='button' class='btn btn-warning' onclick='replyTicket("+ id +", "+ techid +", "+ access +" )' id='btnUpdate'>Techincal Report</button>" );
  } else {
      $('#divButtons').html("<button type='button' class='btn btn-primary' onclick='updateTicketview("+ id +")'>Update Ticket</button>"+
                        "<button type='button' class='btn btn-warning' onclick='replyTicket("+ id +", "+ techid +", "+ access +")' id='btnUpdate'>Techincal Report</button>" );
  }


       
  

}

function replyTicket(id,techId,access) {
$('#divTitle').html("UPDATE TICKET");
$('#divMessage').html("<div class='form-group'><textarea class='form-control' rows='5' id='txtdescription' placeholder='Description'></textarea></div>"+
                    "<div  class='form-group'><select class='form-control form-control-user' id='cmbStatus'></select></div>" +
                    "<div id='divTech' class='form-group'><select class='form-control form-control-user' id='cmbTech'></select></div>" );
  $.ajax({
      async: false,
      type: "POST",
      url: 'controllers/indexControllers.php',
      data: {getTicketStatus: 1},
      success: function(data) {
         
          data = JSON.parse(data);
          $("#cmbStatus").empty();
          var cmbStatus = document.getElementById("cmbStatus");
          var option = document.createElement("option");
              option.text = "SELECT STATUS";
              option.value = 0;
              cmbStatus.add(option);
          for (var i=0; i< data.length; i++ ) {
              var option = document.createElement("option");
              option.text = data[i].name;
              option.value = data[i].id;
              cmbStatus.add(option);
          }
        
      }, 
      error: function (e) {
          alert(e);
      }
  })

  $.ajax({
      async: false,
      type: "POST",
      url: 'controllers/homeControllers.php',
      data: {getTech: 1},
      success: function(data) {
          data = JSON.parse(data);
          $("#cmbTech").empty();
          var cmbInc = document.getElementById("cmbTech");
          var option = document.createElement("option");
              option.text = "SELECT TECNICIAN";
              option.value = 0;
              cmbInc.add(option);
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

if (techId != 0) {
    $('#cmbTech').val(techId)
    if ( access != 2 ) {
        var element = document.getElementById("divTech");
        element.style.visibility = "hidden";
    } else {
        techId = $('#cmbTech').val()
    }

} 
 


$('#divButtons').html("<button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>"+
                    "<button type='button' class='btn btn-warning' onclick='updateTech("+ id +","+ techId +")' id='btnUpdate'>Send</button>" );

                   
                }

function updateTech(id,techid) {
$('#divTitle').html("RTU Ticketing Message"); 

var editTech = 0;
if (techid == 0) {

    editTech = $('#cmbTech').val();
} else {

    editTech = techid;
}


  $.ajax({
      async: false,
      type: "POST",
      url: 'controllers/homeControllers.php',
      data: {ticketId: id,
             cmbStatus: $('#cmbStatus').val(),
             txtdescription: $('#txtdescription').val(),
             cmbTech: editTech,
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
  });
  location.reload();
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
          var option = document.createElement("option");
              option.text = "SELECT OFFICE";
              option.value = 0;
              cmbOffice.add(option);
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

function updateTicketview(id) {
$('#divTitle').html("Updating Ticket"); 

$('#divMessage').html( "<p class='mb-4'><b>Ticket Information</b></p><div class='form-group'> <input type='email' class='form-control form-control-user' id='txtEmail' placeholder='Email Address'></div>" +
                      "<div class='form-group'>" +
                      "<div class='form-group'><input type='text' class='form-control form-control-user' id='txtEmpName' placeholder='Complete Name'></div>" +
                      "<div class='form-group'><select class='form-control form-control-user' id='cmbPrio'></select></div>" +
                      "<div class='form-group'><select class='form-control form-control-user' id='cmbIncident'></select></div>" +
                      "<div class='form-group'><select class='form-control form-control-user' onchange='getOffice()' id='cmbDepartment'></select></div>" +
                      "<div class='form-group'><select class='form-control form-control-user' id='cmbOffice'></select></div>" +
                      "<div class='form-group'><input type='text' class='form-control form-control-user' id='txtTitle' placeholder='Title'><textarea class='form-control' rows='5' id='txtdescription' placeholder='Description'></textarea></div>");
$('#divButtons').html(" <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button><button type='button' class='btn btn-warning'  onclick='updateTicket("+ id +")' data-dismiss='modal'>Update</button>");


$.ajax({
      async: false,
      type: "POST",
      url: 'controllers/homeControllers.php',
      data: {getPrio: 1},
      success: function(data) {
          data = JSON.parse(data);
          $("#cmbPrio").empty();
          var cmbInc = document.getElementById("cmbPrio");
          var option = document.createElement("option");
              option.text = 'SELECT PRIORITY';
              option.value = 0;
              cmbInc.add(option);
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
      url: 'controllers/indexControllers.php',
      data: {getIncident: 1},
      success: function(data) {
          data = JSON.parse(data);
          $("#cmbIncident").empty();
          var cmbInc = document.getElementById("cmbIncident");
          var option = document.createElement("option");
              option.text = "SELECT INCIDENT";
              option.value = 0;
              cmbInc.add(option);
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
          var option = document.createElement("option");
              option.text = "SELECT DEPARTMENT";
              option.value = "0";
          cmbDept.add(option);
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


//Retrived ticket deatils

$.ajax({
      async: false,
      type: "POST",
      url: 'controllers/homeControllers.php',
      data: {getTicketDetails: 1, ticketId: id},
      success: function(data) {
          data = JSON.parse(data);
          for (var i=0; i< data.length; i++ ) {
              $('#txtEmail').val(data[i].email) 
              $('#txtEmpName').val(data[i].name) 
              $('#cmbPrio').val(data[i].priority) 
              $('#cmbIncident').val(data[i].incident)
              $('#cmbDepartment').val(data[i].Department) 
              getOffice();
              $('#cmbOffice').val(data[i].Offices) 
              $('#txtTitle').val(data[i].title) 
              $('#txtdescription').val(data[i].description) 
            
          }
        
      }, 
      error: function (e) {
          alert(e);
      }
});
}


function updateTicket(id) {
$('#divTitle').html("RTU Ticketing Message"); 
  $.ajax({
      async: false,
      type: "POST",
      url: 'controllers/homeControllers.php',
      data: {txtEmail: $('#txtEmail').val(), 
             txtEmpName: $('#txtEmpName').val(),
             cmbIncident: $('#cmbIncident').val(),
             cmbDepartment: $('#cmbOffice').val(),
             txtTitle: $('#txtTitle').val(),
             txtdescription: $('#txtdescription').val(),
             cmbPrio: $('#cmbPrio').val(),
             ticketId: id,
             updateTicket: 1
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
