<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['utype'] != "Owner")
    header("Location: index.php");

include 'inc/basic_template.php';
t_header("Bus Ticket Booking &mdash; Earnings");
t_login_nav();
t_owner_sidebar();
?>

<div class="container">
	<div class="table-con text-center">
		<div class="row">
			<div class="col-md-2">Date</div>
			<div class="col-md-2">Bus Name</div>
			<div class="col-md-2">From</div>
			<div class="col-md-2">To</div>
			<div class="col-md-2">D. Time</div>
			<div class="col-md-2">Earning (৳)</div>
		</div>

<?php
require_once 'inc/database.php';
$conn = initDB();
$res = $conn->query("select jdate,bus_id, b.bname, b.from_loc,b.to_loc,b.from_time, sum(t.fare) as earn from tickets t, buses b where t.bus_id = b.id and b.owner_id = ".$_SESSION['user']['id']." group by(jdate)");
if ($res->num_rows == 0) {
	echo '<div class="row"><h4 class="col-md-12 text-center">No Earning History</h4></div>';
}
else {
	while ($row = $res->fetch_assoc()) {
		echo '<div class="row content">
			<div class="col-md-2">'.$row['jdate'].'</div>
			<div class="col-md-2">'.$row['bname'].'</div>
			<div class="col-md-2">'.$row['from_loc'].'</div>
			<div class="col-md-2">'.$row['to_loc'].'</div>
			<div class="col-md-2">'.$row['from_time'].'</div>
			<div class="col-md-2">'.$row['earn'].'</div>
		</div>';
	}
}
$conn->close();
?>

	</div>
</div>

<?php
t_footer();
?>