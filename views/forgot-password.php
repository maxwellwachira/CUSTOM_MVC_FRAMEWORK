<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>RentLord</title>

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="assets/3rdparties/font-awesome/css/font-awesome.css" />
    <!-- Animate CSS -->
    <link rel="stylesheet" href="assets/3rdparties/animate/animate.css" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/3rdparties/bootstrap/css/bootstrap.css" />

    <style type="text/css">
        .form-control:focus {
            border-color: #000;
            box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.075) inset, 0px 0px 8px rgba(0, 0, 0, 0.5);
        }
        #form_div {
          border: 1px solid;
          padding: 10px;
          box-shadow: 5px 10px #888888;
        }
    </style>

    
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/about">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/contact">contact</a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/login">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/register">Register</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
   <h1>contact</h1>
<div class="container" >
   <div class = "row animated bounce" >
    <div class="col-lg-3 col-md-3 col-sm-12"></div>
    <div class="col-lg-6 col-md-6 col-sm-12 card" style="padding-left: 20px;" id="form_div">      
   <form method="POST" action="">
    <div id="op"></div>
    <div class="control-group">
        <div class="form-group">
          <label>Email</label>
            <input type="email" name="email" id="email" class="form-control">
            

        </div>
    </div>
   
    <br>
    <div id="success"></div>
    <div class="form-group">
     <button type="submit" class="btn btn-primary" id="send_button">Get New Password</button>
    </div>
   </form>
 </div>
</div>
</div>


<!-- JQUERY -->
<script src="assets/3rdparties/jquery/jquery.js"></script>
<!-- Bootstrap JS -->
<script src="assets/3rdparties/bootstrap/js/bootstrap.js"></script>

<script type="text/javascript">
  
  function clear_register_field() {
    $("#email").val('');
  }


function register_submit(){
  
  // pull in values/variables
  var email = $("#email").val();

  //check if any of the variable is empty
  if (!email || (email == "")) {
    $('#op').html('<div class="alert alert-danger animated bounce" role="alert"><i class="fa fa-warning animated swing infinite"></i> Please enter your Email</div>');
  } 
  else {

    $('#op').html('');

    $.ajax({  
        url:"/forgot-password",  
        method:"POST",  
        data:{
          email:email,
        },
        dataType: 'text', 
        success:function(data)  
        {  
            console.log(data);
            var response = JSON.parse(data);
            //console.log(response);
            if (response.message !== 'success') {
              
               $('#op').html('<div class="alert alert-danger animated bounce" role="alert"><i class="fa fa-warning animated swing infinite"></i> ' + response.message +'</div>');
              

            }else if(response.message === 'success'){
              // clear the fields
              $('#op').html('<div class="alert alert-success animated bounce" role="alert"><i class="fa fa-tick"></i>  Password reset link sent to email </div>');
              clear_register_field();
            }
            
        },
        error: function (jqXhr, textStatus, errorThrown) {
            
            $('#op').html('<div class="alert alert-danger animated bounce" role="alert"><i class="fa fa-warning animated swing infinite"></i> Contact system Admin. System error</div>');
            console.log(jqXhr + " || " + textStatus + " || " + errorThrown);
        } 
    });
  }
}


$(document).ready(function() {

   $('form').submit(function(event){
    event.preventDefault();
    register_submit();
    return false;
   });

});
</script>

</body>
</html>

