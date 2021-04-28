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
   <h1></h1>
<div class="container">
  <div id="form_div">
   <form method="POST" action="">
    <div id="op"></div>
    <?php echo $message ?>
    <div class="control-group">
        <div class="form-group mb-0 pb-2">
          <label>First Name</label>
            <input type="text" name="firstname" id="firstname" class="form-control" value = "<?php   ?>"/>
           
        </div>
    </div> 
     <div class="control-group">
        <div class="form-group mb-0 pb-2">
          <label>Second Name</label>
             <input type="text" name="secondname" id="secondname" class="form-control" value = "<?php   ?>"/>
        </div>
    </div>

    <div class="control-group">
        <div class="form-group">
          <label>Email</label>
             <input type="text" name="email" id="email" class="form-control" required value = "<?php   ?>"/>
        </div>
    </div>

    <div class="control-group">
        <div class="form-group">
          <label>Phone Number</label>
             <input type="text" name="phonenumber" id="phonenumber" class="form-control" value = "<?php   ?>"/>
        </div>
    </div>


     <div class="control-group">
        <div class="form-group">
          <label>Password</label>
             <input type="text" name="password" id="password" class="form-control" value = "<?php   ?>"/>

        </div>
    </div>

    <div class="control-group">
        <div class="form-group">
          <label>Confirm Password</label>
             <input type="text" name="confirmPassword" id="confirmPassword" class="form-control" value = "<?php   ?>"/>
           
        </div>
    </div>
    <br>
    <div id="success"></div>
    <div class="form-group">
     <button type="submit" class="btn btn-primary" id="send_button">Send</button>
    </div>
   </form>
 </div>
</div>

<!-- JQUERY -->
<script src="assets/3rdparties/jquery/jquery.js"></script>
<!-- Bootstrap JS -->
<script src="assets/3rdparties/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript">
  
  function clear_register_field() {
    $("#firstname").val('');
    $("#secondname").val('');
    $("#phonenumber").val('');
    $("#email").val('');
    $("#password").val('');
    $("#confirmPassword").val('');
}


function register_submit(){
  
  // pull in values/variables
  var firstname = $("#firstname").val();
  var csrf_token = $("#csrf").val();
  var secondname = $("#secondname").val();
  var phonenumber = $("#phonenumber").val();
  var email = $("#email").val();
  var password = $("#password").val();
  var confirmPassword = $("#confirmPassword").val();

  

  //check if any of the variable is empty
  if (!firstname || !secondname  || !phonenumber || !email || !password || !confirmPassword) {
    $('#op').html('<div class="alert alert-danger animated bounce" role="alert"><i class="fa fa-warning animated swing infinite"></i> Please fill out all sections</div>');
  } 
  else {

      if (password != confirmPassword) {

          $('#op').html('<div class="alert alert-danger animated bounce" role="alert"><i class="fa fa-warning animated swing infinite"></i> Passwords do not match</div>');

        } else {

          if (Number(password.length) < 8){

            $('#op').html('<div class="alert alert-danger animated bounce" role="alert"><i class="fa fa-warning animated swing infinite"></i> Password Must be atleast 8 characters</div>');

          }else {

            $('#op').html('');

            $.ajax({  
                url:"/register",  
                method:"POST",  
                data:{
                  csrf_token:csrf_token,
                  firstname:firstname,
                  secondname:secondname,
                  email:email,
                  phonenumber:phonenumber,
                  password:password,
                  confirmPassword:confirmPassword
                },
                dataType: 'text', 
                success:function(data)  
                {  
                 
            
                    //var response = '<?php echo $message ?>';
                    console.log(data);
                    var response = JSON.parse(data);
                    console.log(response);
                    if (response.message == 'success') {
                      
                      $('#op').html('<div class="alert alert-success animated bounce" role="alert"><i class="fa fa-check"></i> You have registered succesfully. Click here to log into your account <a class="btn btn-success" href="/login">Log in</a></div>');

                      // clear the fields
                      clear_register_field();

                    } else {
                      $('#op').html('<div class="alert alert-danger animated bounce" role="alert"><i class="fa fa-warning animated swing infinite"></i> '+response.message +'</div>');
                      
                    }
                    
                },
                error: function (jqXhr, textStatus, errorThrown) {
                    
                    $('#op').html('<div class="alert alert-danger animated bounce" role="alert"><i class="fa fa-warning animated swing infinite"></i> Contact system Admin. System error</div>');
                    console.log(jqXhr + " || " + textStatus + " || " + errorThrown);
                } 
            });

          }

        } 
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

