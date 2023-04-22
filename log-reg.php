<!-- Login & Register Page (HTML Form Input and PHP Validation & Database Writing & Reading) -->

<!-- Login Session Validation -->

<?php
    session_start();
?>

<!-- Header - Title, Favicon, Stylesheet (Style Sheet is Auto Updated) -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arkanine Login</title>
    <link rel="shortcut icon" type="image/png" href="img/favicon.png">
    <link rel="stylesheet" href="assets/css/style.css?<?php echo date('his')?>">
    <!-- <script defer src="assets/js/text_script.js"></script> -->

    <!-- php login and register functionality -->

    <?php

    $error = null;
    if (isset($_GET['submit'])) {

        //Checking if passwords match

        if ($_GET['password1'] !== $_GET['password2']) {
            $error = "Passwords don't match";
        }

            //Checking if the email is valid

            if(empty($error)){
                if(filter_input(INPUT_GET, 'email', FILTER_VALIDATE_EMAIL) === FALSE){
                    $error = "Not a valid email address";
                }
            }

            //Check if user already exists

            if(empty($error)){
                $user_file = new SplFileObject('data/users.csv','r');
                while(!$user_file->eof()){

                $user = $user_file->fgetcsv();
                if($user[0] === $_GET["email"]){
                    $error = "Account already exists.";
                break;

                }
            }
        }

            //All correct information and create account

            if(empty($error)){
                            
                //Write to file, saves data for future logins and storage

                $user_file = new SplFileObject('data/users.csv','a');

                $user = [];
                $user[] = $_GET["email"];
                $user[] = filter_input(INPUT_GET, 'firstname', FILTER_SANITIZE_STRING);
                $user[] = filter_input(INPUT_GET, 'lastname', FILTER_SANITIZE_STRING);
                $user[] = password_hash($_GET["password1"], PASSWORD_DEFAULT);

                $user_file->fputcsv($user);
                $user_file = null;

            }
    }

    //Login form, accessing data for verification

    $error_login = null;

    if(isset($_GET["submit_login"])){

        $user_file = new SplFileObject('data/users.csv','r');

        //Finding account by email then verifying password, password is verified while hashed

        while(!$user_file->eof()){

            $user = $user_file->fgetcsv();
            if($user[0] === $_GET["email_login"]){
                if(password_verify($_GET["password_login"], $user[3])){

                    $_SESSION['user'] = $user;

                    //Directing the user to the index/homepage

                    header("Location: index.php");

                }else{
                    $error_login = "Email or Password incorrect.";
                }
            }else{
                $error_login = "Email or Password incorrect.";
            }
        }
    }
    
    ?>

</head>
<body>

    <div class="headerContainer">
        <div class="headerText">
            <h1 data-value="Arkanine">Arkanine</h1>
        </div>
        <div class="headerImage">
                    <svg style="display: none;">
                <defs>
                <filter id="noise">
                    <feTurbulence
                    baseFrequency="0.7,0.8"
                    seed="0"
                    type="fractalNoise"
                    result="static"
                    >
                    <animate
                        attributeName="seed"
                        values="0;100"
                        dur="800ms"
                        repeatCount="1" 
                        begin="card.mouseenter"                           
                    />
                    </feTurbulence>
                    <feDisplacementMap in="SourceGraphic" in2="static">
                    <animate
                        attributeName="scale"
                        values="0;40;0"
                        dur="800ms"
                        repeatCount="1" 
                        begin="card.mouseenter"                           
                    />
                    </feDisplacementMap>
                </filter>  
                </defs>
            </svg>
            
            <div id="card">
                <img 
                src="img/skull.png" 
                alt="Skull" 
                style="width:200px;height:200px;"
                />
      </div>
        </div>
    </div>

    <!-- script -->

    <script>

    const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

    let interval = null;

    document.querySelector("h1").onmouseover = event => {  
    let iteration = 0;
    
    clearInterval(interval);
    
    interval = setInterval(() => {
        event.target.innerText = event.target.innerText
        .split("")
        .map((letter, index) => {
            if(index < iteration) {
            return event.target.dataset.value[index];
            }
        
            return letters[Math.floor(Math.random() * 26)]
        })
        .join("");
        
        if(iteration >= event.target.dataset.value.length){ 
        clearInterval(interval);
        }
        
        iteration += 1 / 4;
    }, 30);
    }

    </script>

        <main>

        <!-- register form for user to sign up -->
        <!-- register heading -->

            <section>

                <h2>Register</h2>
                <p class="error"><?php echo $error;?></p>

                <!-- registration form, collects first name, last name, email, password and confirms password -->

                <form>

                    <label for="firstname">First name</label>
                    <input type="text" id="firstname" name="firstname" placeholder="Enter first name" required>
                    <label for="lastname">Last name</label>
                    <input type="text" id="lastname" name="lastname" placeholder="Enter last name" required>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter email" required>
                    <label for="password1">Password</label>
                    <input type="password" id="password1" name="password1" placeholder="Password (6-20 characters)" maxlength="20" minlength="6" required>
                    <label for="password2">Confirm password</label>
                    <input type="password" id="password2" name="password2" placeholder="Confirm password" maxlength="20" minlength="6" required>

                    <!-- submit button for user to create an account -->

                    <button type="submit" name="submit">Create Account</button>

                </form>

            </section>

            <!-- login form for user to sign in -->
            <!-- login heading -->

            <section>

                <h2>Log In</h2>
                <p class="error"><?php echo $error_login;?></p>

            <!-- login form, requires email and password -->
                
                <form>

                    <label for="email_login">Email</label>
                    <input type="email" id="email_login" name="email_login" placeholder="Enter email" required>
                    <label for="password_login">Password</label>
                    <input type="password" id="password_login" name="password_login" placeholder="Enter password" maxlength="20" minlength="6" required>

                    <!-- submit button for user to login account -->

                    <button type="submit" name="submit_login">Log In</button>

                </form>

            </section>

        </main>

</body>
</html>