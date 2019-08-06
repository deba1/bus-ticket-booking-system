<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['utype'] != "Admin")
    header("Location: index.php");

include 'inc/basic_template.php';
t_header("Bus Ticket Booking &mdash; User Manager");
t_login_nav();
t_admin_sidebar();

if (isset($_GET['toggle'])) {
	require_once 'inc/database.php';
	$conn = initDB();
	if ($conn->query("update buses set approved=".$_GET['toggle']." where id=".$_GET['id']))
		echo '<script>alert("OK");</script>';
	else
		echo '<script>alert("Fail");</script>';
	$conn->close();
}

?>

<div class="row mb-2">
    <h4 class="col-md-3">Buses</h4>
    <div class="col-md-8 text-right ml-4">
    <form method="post" action="">
    	<input type="text" name="bus" class="form-control-sm" value="<?php echo (isset($_POST['bus'])) ? $_POST['bus'] : ""; ?>">
        <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Search</button>
    </form>
    </div>
</div>
<table width="95%" class="table-con">
<tr class="head">
    <th>ID</th>
    <th>Bus Name</th>
    <th>Bus No.</th>
    <th>Owner</th>
    <th>From</th>
    <th>Diparture</th>
    <th>To</th>
    <th>Arrival</th>
    <th>Fare</th>
    <th>Status</th>
</tr>
<?php
require_once 'inc/database.php';
$conn = initDB();
$sql = "select *,users.uname as owner,buses.id as bid from buses, users where owner_id=users.id";
if (isset($_POST['bus'])) {
	$sql .= " and (bname like '%".$_POST['bus']."%' or bus_no like '%".$_POST['bus']."%')";
}
$sql .= " order by approved";
$res = $conn->query($sql);
if ($res->num_rows == 0) {
    echo '
    <tr class="row">
        <td colspan="9" class="text-center">No Bus</td>
    </tr>';
}
else {
    while ($row = $res->fetch_assoc()) {
        echo '
        <tr class="content">
            <td>' . $row["bid"] . '</td>
            <td>' . $row["bname"] . '</td>
            <td>' . $row["bus_no"] . '</td>
            <td>' . $row["owner"] . '</td>
            <td>' . $row["from_loc"] . '</td>
            <td>' . $row["from_time"] . '</td>
            <td>' . $row["to_loc"] . '</td>
            <td>' . $row["to_time"] . '</td>
            <td>' . $row["fare"] . '</td>
            <td><a href="buses.php?id=' . $row["bid"] . '&toggle=';
            if ($row["approved"])
            	echo '0" title="Click to Unapprove"><i class="fa fa-check text-success">';
            else
            	echo '1" title="Click to Approve"><i class="fa fa-times text-danger">';
        echo '</i></a></td></tr>';
    }
}
$conn->close();
?>
</table>
</div>
<?php
t_footer();
?>