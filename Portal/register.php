<?php
// Include config file
require_once "config.php";
// Define variables and initialize with empty values
$username = $password = $confirm_password = $image = $img = $name = "";
$username_err = $password_err = $confirm_password_err = $name_err = $img_err ="";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	$image = $_FILES["image"]["tmp_name"];
	if(empty(trim($image))){
		$img_err = "No image selected";
		echo "<script>alert('$img_err');</script>";
	}
	else{
	$img = addslashes(file_get_contents($image));
	// $base64 =  base64_encode($contents);
}

	if(empty(trim($_POST["name"]))){
        $name_err = "Please enter a name.";
    } else{
		$name = trim($_POST["name"]);
	}
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT empid FROM records WHERE empid = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                    echo "<script>alert('$username_err');</script>";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong in username. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
        echo "<script>alert('$password_err');</script>";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
            echo "<script>alert('$confirm_password_err');</script>";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($img_err)&&empty($password_err) && empty($confirm_password_err)){
    		$param_username = $username;
            $param_name = $name;
            $param_img = $img;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Prepare an insert statement
        $sql = "INSERT INTO records (empid,name, password,image) VALUES ('$param_username','$param_name', '$param_password','$param_img')";
         
        // if($stmt = mysqli_prepare($link, $sql)){
        //     // Bind variables to the prepared statement as parameters
        //     mysqli_stmt_bind_param($stmt, "ssss", $param_username,$param_name, $param_password,$param_img);
            
        //     // Set parameters
        //      // Creates a password hash
            
        //     // Attempt to execute the prepared statement
            if(mysqli_query($link, $sql)){
                // Redirect to login page
                header("location: index.php");
            } else{
                echo "Oops! Something went wrong.Please try again later.";
            }

            // Close statement
            // mysqli_stmt_close($stmt);
        
    }
    
    // Close connection
    mysqli_close($link);
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
	<title>Incentivised Tech Bin</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
	<link rel="stylesheet" href="css/style.css">
	

<!-- ============================================================================================= -->
</head>
<body>
	<div class="container-login100" style="background-image: url('images/bg-01.jpg');">
		<div class="wrap-login100 p-l-55 p-r-55 p-t-40 p-b-30">
			<form class="login100-form validate-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
				<span class="login100-form-title p-b-37">
					Register
				</span>

					<div class="profile-pic-div">
 						<img src="image.jpg" id="photo" >
  						<input type="file" id="file" name="image" accept="image/jpeg">	
  						<label for="file" id="uploadBtn">Choose Photo</label>
					</div>

				<div class="wrap-input100 validate-input m-b-20" data-validate="Enter Employee ID">
					<input class="input100" type="text" name="username" placeholder="Employee ID">
					<span class="focus-input100"></span>
				</div>

				<div class="wrap-input100 validate-input m-b-20" data-validate="Enter Name">
					<input class="input100" type="text" name="name" placeholder="Name">
					<span class="focus-input100"></span>
				</div>

				<div class="wrap-input100 validate-input m-b-25" data-validate = "Enter password">
					<input class="input100" type="password" name="password" placeholder="Password">
					<span class="focus-input100"></span>
				</div>

				<div class="wrap-input100 validate-input m-b-25" data-validate = "Enter password">
					<input class="input100" type="password" name="confirm_password" placeholder="Confirm Password">
					<span class="focus-input100"></span>
				</div>

				

				<div class="container-login100-form-btn">
					<button class="login100-form-btn">
						Register Me
					</button>
				</div>

				

				<div class="text-center">
					<a href="./index.php" class="txt2 hov1">
						Sign In Instead
					</a>
				</div>
			</form>

			
		</div>
	</div>
	
	

	<div id="dropDownSelect1"></div>
	
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>
	<script src="app.js"></script>

</body>
</html>