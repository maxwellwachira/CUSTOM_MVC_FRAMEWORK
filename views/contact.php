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
    </div>
  </div>
</nav>
   <h1>contact</h1>
<div class="container">
   <form id="contactForm" novalidate="novalidate">

    <div class="control-group">
        <div class="form-group mb-0 pb-2">
          <label>Name</label>
            <input type="text" name="name" id="name" class="form-control" required="required" data-validation-required-message="Please enter your name." />
            <p class="text-danger help-block"></p>
        </div>
    </div>

    <div class="control-group">
        <div class="form-group">
          <label>Email</label>
            <input type="email" name="email" id="email" class="form-control" required="required" data-validation-required-message="Please enter your email address." />
            <p class="text-danger help-block"></p>

        </div>
    </div>

    <div class="control-group">
        <div class="form-group">
          <label>Phone Number</label>
            <input type="tel" name="phonenumber" id="phonenumber" class="form-control" required="required" data-validation-required-message="Please enter your phone number." pattern="^[0-9]{10}$" data-validation-pattern-message="10 digits needed" />
            <p class="text-danger help-block"></p>

        </div>
    </div>


     <div class="control-group">
        <div class="form-group">
          <label>Subject</label>
            <input type="text" name="subject" id="subject" class="form-control" required="required" data-validation-required-message="Please enter the subject." />
            <p class="text-danger help-block"></p>

        </div>
    </div>

    <div class="control-group">
        <div class="form-group">
          <label>Message</label>
            <textarea name="message" id="message" class="form-control" rows="5" required="required" data-validation-required-message="Please enter a message."></textarea>
            <p class="text-danger help-block"></p>
        </div>
    </div>
    <br>
    <div id="success"></div>
    <div class="form-group">
     <button type="submit" class="btn btn-primary" id="send_button">Send</button>
    </div>
   </form>
</div>

<!-- JQUERY -->
<script src="assets/3rdparties/jquery/jquery.js"></script>
<!-- Bootstrap JS -->
<script src="assets/3rdparties/bootstrap/js/bootstrap.js"></script>
<script src="assets/js/jqBootstrapValidation.js"></script>
<script src="assets/js/contact_me.js"></script>

</body>
</html>

