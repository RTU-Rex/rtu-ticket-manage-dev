

<!-- Divider -->
<hr class="sidebar-divider my-1">
<div id="divmenu">

</div>


<script> 
   $(document).ready(function() {
      
            $.ajax({
                async: false,
                type: "POST",
                url: 'controllers/menuControllers.php',
                data: {getMainMenu: 1},
                success: function(data) {
                    data = JSON.parse(data);
                    for (var i=0; i< data.length; i++ ) {
                        document.getElementById("divmenu").innerHTML += "<div class='sidebar-heading'>"+ data[i] +"</div><div id='div"+ data[i] +"'></div>"
                        MenuItem(data[i]);
                    }
                }, 
                error: function (e) {
                    alert(e);
                }
            });

        function MenuItem(Child) {
            $.ajax({
                async: false,
                type: "POST",
                url: 'controllers/menuControllers.php',
                data: {getMenu: 1, main: Child},
                success: function(data) {
                    data = JSON.parse(data);
                    for (var i=0; i< data.length; i++ ) {
                        document.getElementById("div"+Child).innerHTML += "<li class='nav-item active'><a class='nav-link' href='"+ data[i].menuURL +"'><i class='"+ data[i].icons +"'></i><span>"+ data[i].menuName +"</span></a></li>"
                    }
                    document.getElementById("div"+Child).innerHTML += "<hr class='sidebar-divider'>"
                }, 
                error: function (e) {
                    alert(e);
                }
            })
    }
   })
 


  </script>


