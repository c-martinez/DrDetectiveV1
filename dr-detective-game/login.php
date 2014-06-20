<html>

<head>
<meta charset="utf-8"> 
<title>Dr. Detective Game</title> 
<link href="bootstrap/css/bootstrap.css" rel="stylesheet"> 
</head>

<body>

 <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="#">Dr.Detective</a>
         
         <div class="nav-collapse collapse">
            <ul class="nav">
              <li><a href="about.php">About</a></li>
            </ul>
          </div><!--/.nav-collapse -->

        </div>
      </div>
   </div>
   

<div class="container">  
<div class="row">  
<div class="span6" style="float: none; margin: 0 auto;"><br/><br/><br/>

<div class="container-fluid" style=" vertical-align: middle;">  
     <div class="accordion" id="accordion2">  
            <div class="accordion-group">  
              <div class="accordion-heading">  
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">  
                  Login  
                </a>  
              </div>  
              <div id="collapseOne" class="accordion-body collapse" style="height: 0px; ">  
                <div class="accordion-inner">  
                  <form action="login_check.php" method="POST">
										<fieldset>
											
											<label>Username</label>
											<input type="text" name="user_log">
											
											<label>Password</label>
											<input type="password" name="pass">
											
											<button type="login" class="btn">Login</button>
										</fieldset>
									</form>
                </div>  
              </div>  
            </div>  
            <div class="accordion-group">  
              <div class="accordion-heading">  
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">  
                 Sign up
                </a>  
              </div>  
              <div id="collapseTwo" class="accordion-body collapse">  
                <div class="accordion-inner">  
                  <form action="login_check.php" method="POST">
										<fieldset>
											
											<label>Username</label>
											<input type="text" name="user_sign">
											
											<label>Password</label>
											<input type="password" name="pass">
											
											<label>Re-type password</label>
											<input type="password" name="pass2">
											
											<button type="login" class="btn">Sign up</button>
										</fieldset>
									</form>
                </div>  
              </div>  
            </div>  
           
          </div>  
    </div>  

</div>
</div>
</div>
    
    	
	<script src="jquery-2.0.2.min.js"></script>
	<script src="bootstrap/js/bootstrap.js"></script>
	<script>
		$(document).ready(function() {
				$('#collapseOne').collapse('show');
		});
	</script>
 
</body>

</html>
