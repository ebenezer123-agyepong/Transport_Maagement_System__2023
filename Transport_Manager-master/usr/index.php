<!--Server Side Scripting Language to inject login code-->
<?php
session_start();
include('vendor/inc/config.php'); // Include configuration file

if (isset($_POST['Usr-login'])) {
    $u_email = $_POST['u_email'];
    $u_pwd = $_POST['u_pwd'];

    $stmt = $mysqli->prepare("SELECT u_id FROM tms_user WHERE u_email=? AND u_pwd=?");
    $stmt->bind_param('ss', $u_email, $u_pwd);
    $stmt->execute();
    $stmt->bind_result($u_id);
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->fetch();

        // Logging user details
        $uemail = $_SESSION['login'] = $u_email;
        $uip = $_SERVER['REMOTE_ADDR'];
        $ldate = date('d/m/Y h:i:s', time());



$geopluginURL = 'http://www.geoplugin.net/php.gp?ip=' . $uip;
$addrDetailsArr = unserialize(file_get_contents($geopluginURL));
$city = isset($addrDetailsArr['geoplugin_city']) ? $addrDetailsArr['geoplugin_city'] : 'Unknown'; // Set a default value if city is null
$country = isset($addrDetailsArr['geoplugin_countryName']) ? $addrDetailsArr['geoplugin_countryName'] : 'Unknown'; // Set a default value if country is null

// Corrected SQL INSERT statement with column names
$stmt = $mysqli->prepare("INSERT INTO userLog (u_id, u_email, u_ip, u_city, u_country) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param('issss', $u_id, $uemail, $uip, $city, $country);
$stmt->execute();

// ...

        header("location: user-dashboard.php");
    } else {
        // Access Denied
        $error = "Access Denied Please Check Your Credentials";
    }

    $stmt->close();
}

?>


<!--End Server Side Script Injection-->
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Transport Management System - Client Login</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template-->
  <link href="vendor/css/sb-admin.css" rel="stylesheet">

</head>

<body class="bg-dark">

  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Login</div>
      <div class="card-body">
        <!--INJECT SWEET ALERT-->
        <!--Trigger Sweet Alert-->
          <?php if(isset($error)) {?>
          <!--This code for injecting an alert-->
              <script>
                    setTimeout(function () 
                    { 
                      swal("Failed!","<?php echo $error;?>!","error");
                    },
                      100);
              </script>
                  
          <?php } ?>
        <form method ="POST">
          <div class="form-group">
            <div class="form-label-group">
              <input type="email" name="u_email" id="inputEmail" class="form-control"  required="required" autofocus="autofocus">
              <label for="inputEmail">Email address</label>
            </div>
          </div>
          <div class="form-group">
            <div class="form-label-group">
              <input type="password" name="u_pwd" id="inputPassword" class="form-control"  required="required">
              <label for="inputPassword">Password</label>
            </div>
          </div>
          <input type="submit" name="Usr-login" class="btn btn-primary btn-block" value="Login">
        </form>
        <div class="text-center">
          <a class="d-block small mt-3" href="usr-register.php">Register an Account</a>
          <a class="d-block small" href="usr-forgot-password.php">Forgot Password?</a>
          <a class="d-block small" href="../index.php">Home</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <!--INject Sweet alert js-->
 <script src="vendor/js/swal.js"></script>

</body>

</html>
