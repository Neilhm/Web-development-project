<!-- 
****
 this file handles all the server-side handling (log-in, register, Remember me etc..)
****
 -->
<?php 
include_once ("db.php");
?>
<?php
 
 // Starting the session, necessary
 // for using session variables

   
 // Declaring and hoisting the variables

  
 // Registration code
 if (isset($_POST['reg_user'])) {
   
     // Receiving the values entered and storing
     // in the variables
     // Data sanitization is done to prevent
     // SQL injections
     $username = mysqli_real_escape_string($db, $_POST['username']);
     $email = mysqli_real_escape_string($db, $_POST['email']);
     
     $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
     $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);
   
     // Ensuring that the user has not left any input field blank
     // error messages will be displayed for every blank input
    
     if (empty($email)) { array_push($errors, "Email is required"); }
     if (empty($username)) { array_push($errors, "Username is required"); }
     if (empty($password_1)) { array_push($errors, "Password is required"); }
   
     if ($password_1 != $password_2) {
         array_push($errors, "The two passwords do not match");
         // Checking if the passwords match
     }
   
     // If the form is error free, then register the user
     if (count($errors) == 0) {
          
         // Password encryption to increase data security
         $password = md5($password_1);
          
         // Inserting data into table
         $query = " INSERT INTO users ( username, email, password) VALUES( '$username', '$email', '$password')" ;
         if ($db->query($sql)){
            echo "account added";
        }
          
         mysqli_query($db, $query);
   
         // Storing username of the logged in user,
         // in the session variable
         $_SESSION['username'] = $username;
          
         // Welcome message
         $_SESSION['success'] = "You have logged in";
         
          
         // Page on which the user will be
         // redirected after logging in
         header('location: index.php');
     }
 }
   
 // User login
 if (isset($_POST['login_user'])) {
        
    //USED TO USE EXAMPLE USERNAME AND PASSWORD THE OTHER CODE (AFTER EXIT) IS WORKING FINE USING DATABASE
    require_once("users.php");
    if (isset($users[$_POST['username']])){
      if ($users[$_POST['username']] == $_POST['password'] ) {
          // Storing username in session variable
        $_SESSION['username'] = $_POST['username'];
              
        // Welcome message
       
            echo "<script type='text/javascript'>
            alert('You logged-in succecfuly')
            window.location.replace('index.php');
            </script>"; 
            
      
        
       
        exit;
      } }
      
     // Data sanitization to prevent SQL injection
     $username = mysqli_real_escape_string($db, $_POST['username']);
     $password = mysqli_real_escape_string($db, $_POST['password']);
   
     // Error message if the input field is left blank
     if (empty($username)) {
         array_push($errors, "Username is required");
     }
     if (empty($password)) {
         array_push($errors, "Password is required");
     }
   
     // Checking for the errors
     if (count($errors) == 0) {
          
         // Password matching
         $password = md5($password);
          
         $query = "SELECT * FROM users WHERE username=
                 '$username' AND password='$password'";
         $results = mysqli_query($db, $query);

         
         // $results = 1 means that one user with the
         // entered username exists
         if (mysqli_num_rows($results) == 1) {
              
             // Storing username in session variable
             $_SESSION['username'] = $username;
              
             // Welcome message
             echo "<script type='text/javascript'>
             alert('You logged-in succecfuly')
             window.location.replace('index.php');
             </script>"; 
              
             // Page on which the user is sent
             // to after logging in
           
         }
         else {
              
             // If the username and password doesn't match
             array_push($errors, "Username or password incorrect");
         }
     }
    
     
     //REMEBER ME!!

if(!empty($_POST["login_user"])) {
	
	$sql = "Select * from users where username = '" . $_POST["username"] . "'";
        if(!isset($_COOKIE["member_login"])) {
            $sql .= " AND password = '" . md5($_POST["password"]) . "'";
	}
        $result = mysqli_query($db,$sql);
	$user = mysqli_fetch_array($result);
	if($user) {
			$_SESSION["member_id"] = $user["member_id"];
			
			if(!empty($_POST["remember"])) {
				setcookie ("member_login",$_POST["username"],time()+ (7 * 24 * 60 * 60));
			} else {
				if(isset($_COOKIE["member_login"])) {
					setcookie ("member_login","");
				}
			}
	} 
}

 }

   
 ?>