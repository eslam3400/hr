<?php
session_start();
require('../app.php');
app_handler();
$page_title = "Manager Panel";
$css_path = "css/";
require('../comp/start.php');
require('../comp/navbar.php');
?>
<style>
    .col-4 {
        height: 90vh;
    }
</style>
<div id="att" class="row">
    <div class="overflow-auto col-4">
        <?php
        render_emp_list($_SESSION['pranch']);
        ?>
    </div>
    <div class="col-8 p-0">
        <div>
            <form class="text-center" method="POST">
                <?php
                if (isset($_GET['id']) && isset($_GET['stat'])) {
                    $id = $_GET['id'];
                    $stat = $_GET['stat'];
                    $conn = makeConnection();
                    $sql = "SELECT img FROM users WHERE id=$id";
                    $result = $conn->query($sql);
                    $result->num_rows;
                    $row = $result->fetch_assoc();
                    $img = $row['img'];
                    echo "Pic: <img src='../img/$img' width='300px' hight='300px'>";
                    echo "<br>";
                    echo "ID: <input class='text-center' type='number' style='border:none;' value='$id' name='id' readonly>";
                    echo "<br>";
                    echo "Statue: <input class='text-center' type='text' style='border:none;' value='$stat' name='stat' readonly>";
                    echo "<br>";
                    if ($stat === "offline") echo "<input type='submit' value='Start' name='start'>";
                    if ($stat === "online") echo "<input type='submit' value='End' name='end'>";
                    closeConnection($conn);
                }
                if (isset($_GET['r'])) {
                    if ($_GET['r'] == 'start') {
                        echo "<script>alert('started')</script>";
                    }
                    if ($_GET['r'] == 'end') {
                        echo "<script>alert('ended')</script>";
                    }
                    if ($_GET['r'] == 'error') {
                        echo "<script>alert('cant do this')</script>";
                    }
                }
                ?>
            </form>
        </div>
    </div>
</div>
<?php
require('../comp/end.php');
?>