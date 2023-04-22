<!-- Index Page (Page User Is Presented With after Login) -->

<!-- Login Session Validation -->

<?php
    session_start();
    if(empty($_SESSION['user'])){
        header("Location: log-reg.php");
        exit;
    }
?>

<!-- Header - Title, Favicon, Stylesheet (Style Sheet is Auto Updated) -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arkanine</title>
    <link rel="shortcut icon" type="image/png" href="img/favicon.png">
    <link rel="stylesheet" href="assets/css/style.css?<?php echo date('his')?>">
    <script defer src="assets/js/text_script.js"></script>
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

    <!-- Simple Content - User Name & Logout -->
    <!-- Set Time to Another Nation & City -->

    <div class="centered">
        Welcome <?php echo $_SESSION['user'][1]; ?>, you loaded in at <?php date_default_timezone_set('Australia/Melbourne'); echo date("g:i a") . " AEST";?>, to logout click&nbsp;<a href="logout.php">here</a>.
    </div>

    <!-- background -->
    <!-- <script src=assets/js/matrix.js></script> -->

    <!-- forum -->
    <form id="forum_form" action="index.php" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <textarea id="forum_input" name="comment" rows="5" cols="40" placeholder="Make a post" required></textarea>
    <input id="forum_submit" type="submit" value="Post">
    </form>

    <!-- write/append to file -->
    <?php

    $commentErr = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["comment"])) {
          $commentErr = "Comment is required";
          echo $commentErr;
        } else {
          $comment = test_input($_POST["comment"]);
        }
    }

    $comment = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $comment = test_input($_POST["comment"]);
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $user = $_SESSION['user'][1];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $myfile = fopen("data/newfile.txt", "a") or die("Unable to open file!");
        fwrite($myfile, $user . ": " . $comment . "\n");
        fclose($myfile);
    }

    ?>

    <div class="scrollable">
        <div id="websites" class="container mt-5">

            <div class="form_text">

            <!-- read file -->
            <?php

            $myfile = file("data/newfile.txt");
            $myfile = array_reverse($myfile);
            foreach($myfile as $f){
                echo $f."<br /> <br />";
            }

            ?>

            </div>

        </div>
    </div>

    <!-- prevent form submission on page refresh -->
    <script>

        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }

    </script>

</body>
</html>