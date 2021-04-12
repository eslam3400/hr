<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <span class="navbar-brand ml-5 h1" href="/hr/">خواطر دمشقيه</span>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <?php
            if(isset($_SESSION['username'])){
                $first_name = $_SESSION['first-name'];
                echo"
                    <span class='ml-auto text-light'>مرحبا بك $first_name</span>
                    <form class='form-inline my-2 my-lg-0 ml-auto' method='get'>
                        <button class='btn btn-outline-danger my-2 my-sm-0' name='logout' type='submit'>تسجيل الخروج</button>
                    </form>";
            }
            if (isset($_GET['logout'])){
                $host = $_SERVER['SERVER_NAME'];
                session_unset();
                session_destroy();
                redirect("http://$host:8080/hr/login.php");
            }
        ?>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
</nav>