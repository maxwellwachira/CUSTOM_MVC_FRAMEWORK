<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>RentLord</title>

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="assets/3rdparties/font-awesome/css/font-awesome.css" />

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
  
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
 <section style="margin-top: 5%"></section>
<div class="container" id="no-display">
  <div id=" form_div">
   <form method="POST" action="">
    <div id="op"></div>
    <?php echo $message ?>
    <div class="control-group">
        <div class="form-group">
          <label>Select Co-operative Bank Service</label>
            <select id="service" class="form-control">
              <option value="account_balance">Account Balance</option>
              <option value="mini_statement">Mini Statement</option>
              <option value="full_statement">Full Statement</option>
              <option value="refund">Refund Tenant</option>
              <option value="IFTA2A">Transfer Money to another Co-operative Bank Account</option>
              <option value="EFTA2A">Transfer Money to another Bank Account (Not Co-operative Bank)</option>
              <option value="exchange_rate">Spot Currency Exchange Rate</option>
              <option value="account_valid">Bank account Validity</option>
              <option value="status">Transaction Status</option>
              <option value="account_2_phone">Account to Phone</option>
            </select>
            

        </div>
    </div>

     <!--<div class="control-group">
        <div class="form-group">
          <label>Password</label>
            <input type="Password" name="password" id="password" class="form-control">
        </div>
    </div>-->

   
    <br>
    <div id="success"></div>
    <div class="form-group">
     <button type="submit" class="btn btn-primary" id="send_button">Send</button>
    </div>
   </form>
 </div>
</div>

<div class="container text-center">
  <div id="row">
    <div id="table-tittle"></div><br>
  </div>
  <div class="row">
    <div id="data-table"></div>
  </div>
  <div class="row">
    <div id="button"></div>
  </div>
</div>


 


<!-- JQUERY -->
<script src="assets/3rdparties/jquery/jquery.js"></script>
<!-- Bootstrap JS -->
<script src="assets/3rdparties/bootstrap/js/bootstrap.js"></script>

<script type="text/javascript" src="assets/3rdparties/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="assets/3rdparties/datatables/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="assets/3rdparties/datatables/buttons.flash.min.js"></script>
<script type="text/javascript" src="assets/3rdparties/datatables/jszip.min.js"></script>
<!--<script type="text/javascript" src="assets/3rdparties/datatables/pdfmake.min.js"></script>-->
<script type="text/javascript" src="assets/3rdparties/datatables/vfs_fonts.js"></script>
<script type="text/javascript" src="assets/3rdparties/datatables/buttons.html5.min.js"></script>
<script type="text/javascript" src="assets/3rdparties/datatables/buttons.print.min.js"></script>

<script type="text/javascript">
  

function coop_submit(){
  
  // pull in values/variables
  var service = $("#service").val();
  var csrf = $("#csrf").val();

  //check if any of the variable is empty
  if (!service) {
    $('#op').html('<div class="alert alert-danger animated bounce" role="alert"><i class="fa fa-warning animated swing infinite"></i> Please Select a service</div>');
  } 
  else {

    $('#op').html('');

    $.ajax({  
        url:"/coop-connect",  
        method:"POST",  
        data:{
          csrf:csrf,
          service:service
        },
        dataType: 'text', 
        success:function(data)  
        {  
            console.log(data);
            console.log(service);
            var data = JSON.parse(data);
            
            $("#data-table").html('');
            $("#no-display").html('');
            switch(service){
              case 'account_balance':
                data = [data];
                $('#table-tittle').html('<h2>Account Balance</h2>');
                 $("#data-table").html(`<table id="results" class="display" style="width:100%">
                      <thead>
                        <tr>
                            <th>Account Number</th>
                            <th>Account Name</th>
                            <th>Currency</th>
                            <th>BranchName</th>
                            <th>Balance</th>
                            <th>Credit Limit</th>
                        </tr>
                    </thead>
                    </table>`
                  );
                  $('#results').DataTable( {
                    "data": data,
                    "searching": false,
                    "columns": [     
                        { data: 'AccountNumber' },
                        { data: 'AccountName' },
                        { data: 'Currency' },
                        { data: 'AverageBalance' },
                        { data: 'BranchName' },
                        { data: 'CreditLimit' }
                      ]
                    });
                break;

              case 'mini_statement':
                $('#table-tittle').html('<h2>Mini Statement</h2>');
                $("#data-table").html(`<table id="results" class="display" style="width:100%">
                      <thead>
                        <tr>
                            <th>TransactionID</th>
                            <th>Transaction Date</th>
                            <th>Narration</th>
                            <th>Service Point</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    </table>`
                  );
                  $('#results').DataTable( {
                    "data": data.Transactions,
      
                    "columns": [     
                        { data: 'TransactionID' },
                        { data: 'TransactionDate' },
                        { data: 'Narration' },
                        { data: 'ServicePoint' },
                        { data: 'RunningBookBalance' }
                      ]
                    });

                break;

              case 'full_statement':
                $('#table-tittle').html('<h2>Full Statement</h2>');
                $("#data-table").html(`<table id="results" class="display" style="width:100%">
                      <thead>
                        <tr>
                            <th>TransactionID</th>
                            <th>Transaction Date</th>
                            <th>Narration</th>
                            <th>Service Point</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    </table>`
                  );
                  $('#results').DataTable( {
                    "data": data.Transactions,

      
                    "columns": [     
                        { data: 'TransactionID' },
                        { data: 'TransactionDate' },
                        { data: 'Narration' },
                        { data: 'ServicePoint' },
                        { data: 'RunningBookBalance' }
                      ]
                    });
                break;

              case 'refund':
              break;

              case 'IFTA2A':
              break;

              case 'EFTA2A':
              break;

              case 'status':
              break;

              case 'account_2_phone':
              break;

              case 'exchange_rate':
                data = [data];
                $("#data-table").html(`<table id="results" class="display" style="width:100%">
                    <thead>
                      <tr>
                          <th>From Currency</th>
                          <th>To Currency</th>
                          <th>Rate Type</th>
                          <th>Rate</th>
                          <th>Tolerance date</th>
                          <th>Multiply Divide</th>
                      </tr>
                  </thead>
                  </table>`
                );
                $('#results').DataTable( {
                  "data": data,
                  "searching": false,
                  "columns": [     
                      { data: 'FromCurrencyCode' },
                      { data: 'ToCurrencyCode' },
                      { data: 'RateType' },
                      { data: 'Rate' },
                      { data: 'Tolerance' },
                      { data: 'MultiplyDivide' }
                    ]
                  });
                break;
                
            }

            $("#button").html('<br><br><a href = "/coop-connect" type = "button" class = "btn btn-primary">Choose Another Co-op Service</a>')
            
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
    coop_submit();
    return false;
   });

});
</script>

</body>
</html>

