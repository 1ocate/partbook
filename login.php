<?php 
session_start();
require_once 'config.php';
require_once 'dbh.php';
require_once 'functions.php';

if (isset($_POST['login'])){

    $email=$conn->real_escape_string(clean_input($_POST['email']));
    $password=$conn->real_escape_string(clean_input($_POST['password']));
    $loginsql="SELECT * FROM users WHERE email='$email'";
    $loginquery=$conn->query($loginsql);
    $result=$loginquery->fetch_assoc();
    $pass=$result['password'];
    $userid=$result['id'];

    if (check() > 0){
        if(password_verify($password,$pass)){
            $_SESSION['id']=$userid;
            header('location:index.php');

            }
            else{
                $error="Incorrect password";
            }
    }
    else{
        $error='User does not exist';
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <!-- favicon -->
    <link rel="apple-touch-icon" sizes="57x57" href="/assets/img/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/assets/img/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/assets/img/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/assets/img/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/assets/img/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/assets/img/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/assets/img/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/assets/img/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/assets/img/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/assets/img/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="/assets/img/favicon/manifest.json">

    <meta charset="utf-8" />
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">

    <script type="text/javascript" src="js/jquery.min.js"></script>               
    <script type="text/javascript" src="js/jquery.validate.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $( "#login" ).validate( {
                rules: {
                    
                    email:{
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 5
                    }

                },
                messages: {
                    password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 5 characters long"
                    },
                    email: "Please enter a valid email address"
                },
                errorElement: "em",
                errorPlacement: function ( error, element ) {
                    error.addClass( "invalid-feedback" );
                    if ( element.prop( "type" ) === "checkbox" ) {
                        error.insertAfter( element.next( "label" ) );
                    } else {
                        error.insertAfter( element );
                    }
                },
                highlight: function ( element, errorClass, validClass ) {
                    $( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
                },
                unhighlight: function (element, errorClass, validClass) {
                    $( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
                }    


             })       

        });
    </script>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                    <div class="card-body">
                        <?php if( isset($error)): ?>
                            <div class='alert alert-danger text-center'><?php echo $error; ?></div>
                        <?php  endif;  ?>
                        <form id="login" action="login.php" method="POST">
                            <div class="form-group"><label class="small mb-1" for="email">Email</label><input class="form-control py-4" id="email" type="email" name="email"placeholder="Enter email address" /></div>
                            <div class="form-group"><label class="small mb-1" for="password">Password</label><input class="form-control py-4" id="password" type="password" name="password" placeholder="Enter password" /></div>
                            <div class="form-group mt-4 mb-0 text-center">
                                <button class="btn btn-primary" name="login">Login</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <div class="small"><a href="register.php">Need an account? Sign up!</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>