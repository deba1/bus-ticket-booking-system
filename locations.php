<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['utype'] != "Admin")
    header("Location: index.php");

include 'inc/basic_template.php';
t_header("Bus Ticket Booking &mdash; User Manager");
t_login_nav();
t_admin_sidebar();
?>

<div class="row mb-2">
    <h4 class="col-md-3">Buses</h4>
    <div class="col-md-8 text-right ml-4">
    <form method="post" action="">
    	<input type="text" name="loc" class="form-control-sm" value="<?php echo (isset($_POST['loc'])) ? $_POST['loc'] : ""; ?>">
        <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Search</button>
    </form>
    </div>
</div>
<table width="95%" class="table-con">
<tr class="head">
    <th width="40px">ID</th>
    <th class="text-left pl-3">Location name</th>
</tr>
<?php
require_once 'inc/database.php';
$conn = initDB();
$sql = "select * from locations";
if (isset($_POST['loc'])) {
	$sql .= " where (name like '%".$_POST['loc']."%')";
}
$sql .= " order by name";
$res = $conn->query($sql);
if ($res->num_rows == 0) {
    echo '
    <tr class="row">
        <td colspan="2" class="text-center">No Locations</td>
    </tr>';
}
else {
    while ($row = $res->fetch_assoc()) {
        echo '
        <tr class="content">
            <td>' . $row["id"] . '</td>
            <td class="text-left pl-3">' . $row["name"] . '</td>';
    }
}
$conn->close();
?>
</table>
</div>
<?php
t_footer();
?>