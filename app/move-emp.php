<?php
session_start();
require('../app.php');
app_handler();
$page_title = "Manager Panel";
$css_path = "css/";
require('../comp/start.php');
require('../comp/navbar.php');
?>
<form method="get">
    <input style='display:none' type='number' name='id' value='<?php echo $_GET['id'] ?>'>
    <select class="mt-1" id="pranch" name="pranch">
        <?php
        $pranchs = [
            "سبورتنج" => "<option value='سبورتنج'> سبورتنج</option>",
            "سموحه" => "<option value='سموحه'> سموحه</option>",
            "المخزن" => "<option value='المخزن'> المخزن </option>",
            "المكتب" => "<option value='المكتب'> المكتب </option>"
        ];
        if ($_SESSION['pranch'] == "سبورتنج") {
            echo $pranchs["سموحه"];
            echo $pranchs["المخزن"];
            echo $pranchs["المكتب"];
        }
        if ($_SESSION['pranch'] == "سموحه") {
            echo $pranchs["سبورتنج"];
            echo $pranchs["المخزن"];
            echo $pranchs["المكتب"];
        }
        if ($_SESSION['pranch'] == "المخزن") {
            echo $pranchs["سبورتنج"];
            echo $pranchs["سموحه"];
            echo $pranchs["المكتب"];
        }
        if ($_SESSION['pranch'] == "المكتب") {
            echo $pranchs["سبورتنج"];
            echo $pranchs["سموحه"];
            echo $pranchs["المخزن"];
        }
        ?>
    </select>
    <input type="submit" name="move" value="نقل">
</form>
<?php
$id = $_GET['id'];
if (isset($_GET['move'])) {
    $host = $_SERVER['SERVER_NAME'];
    $conn = makeConnection();
    $pranch = $_GET['pranch'];
    $sql = "UPDATE users SET pranch='$pranch' WHERE id='$id'";
    $conn->query($sql);
    closeConnection($conn);
    redirect("http://$host:8080/hr/app/man-panel.php?r=moved");
}
require('../comp/end.php');
?>