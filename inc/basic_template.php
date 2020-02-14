<?php
function t_header($title) {
	echo '
<!DOCTYPE html>
<html>
<head>
  <title>'.$title.'</title>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
  <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css"/>
  <link rel="stylesheet" type="text/css" href="css/style.css?'.date('his').'"/>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>';
}
function t_navbar() {
echo '
<div class="navbar fixed-top navbar-default header">
<div class="container-fluid">
	<h3>Bus Ticket Booking System</h3>
	<div class="navbar-right"><form action="index.php" method="post" class="form-inline">
		<input type="text" name="uname" placeholder="Username" class="form-control-sm mr-1"/>
		<input type="password" name="upass" placeholder="Password" class=" form-control-sm mr-1"/>
		<input type="submit" name="login" value="Login" class="btn btn-info btn-sm"/>
	</form></div>
</div></div>';
}
function t_login_nav() {
	echo '
<div class="navbar fixed-top navbar-default header">
<div class="container-fluid">
	<h3>Bus Ticket Booking System</h3>
	<div class="navbar-right">Welcome, '.$_SESSION['user']['uname'].' <a href="/index.php?logout=1"><button class="btn btn-success ml-3 btn-sm">Logout</button></a></div>
</div></div>';
}
function t_sidebar() {
	$file = basename($_SERVER['PHP_SELF'],'.php');
	echo '
<div class="row">
	<div class="col-md-2 sidebar">
			<ul>
				<li><a href="buy_ticket.php"'.(($file == 'buy_ticket') ? 'class="active"' : '').'><i class="fa fa-ticket"></i> Buy Ticket</a></li>
				<li><a href="profile.php"'.(($file == 'profile') ? 'class="active"' : '').'><i class="fa fa-address-card"></i> My Profile</a></li>
				<li><a href="history.php"'.(($file == 'history') ? 'class="active"' : '').'><i class="fa fa-history"></i> History</a></li>
			</ul>
	</div>
	<div class="col-md-10">
		<div class="container-fluid my-4">
	';

}
function t_owner_sidebar() {
	$file = basename($_SERVER['PHP_SELF'],'.php');
	echo '
<div class="row">
	<div class="col-md-2 sidebar">
			<ul>
				<li><a href="my_buses.php"'.(($file == 'my_buses') ? 'class="active"' : '').'><i class="fa fa-bus"></i> My Buses</a></li>
				<li><a href="earning.php"'.(($file == 'earning') ? 'class="active"' : '').'><i class="fa fa-money"></i> My Earning</a></li>
				<li><a href="profile.php"'.(($file == 'profile') ? 'class="active"' : '').'><i class="fa fa-address-card"></i> My Profile</a></li>
			</ul>
	</div>
	<div class="col-md-10">
		<div class="container-fluid my-4">
	';

}
function t_admin_sidebar() {
	$file = basename($_SERVER['PHP_SELF'],'.php');
	echo '
<div class="row">
	<div class="col-md-2 sidebar">
			<ul>
				<li><a href="users.php"'.(($file == 'users') ? 'class="active"' : '').'><i class="fa fa-users"></i> Users</a></li>
				<li><a href="locations.php"'.(($file == 'locations') ? 'class="active"' : '').'><i class="fa fa-map"></i> Locations</a></li>
				<li><a href="buses.php"'.(($file == 'buses') ? 'class="active"' : '').'><i class="fa fa-bus"></i> Buses</a></li>
				<li><a href="profile.php"'.(($file == 'profile') ? 'class="active"' : '').'><i class="fa fa-address-card"></i> My Profile</a></li>
			</ul>
	</div>
	<div class="col-md-10">
		<div class="container-fluid my-4">
	';
}
function t_footer() {
	echo '</div>
	</div>
</div>
<div class="footer">
	<div class="row p-4">
		<div class="col-md-4">BTBS, owned and operated by Uradhura Limited, is a premium online and on-demand service provider committed to make your life convenient, easier and smarter.<br/>© 2015-2019 BTBS · All Rights Reserved</div>
		<div class="col-md-4 text-center">
		<ul class="list-null">
            <li><a href="#">About Us</a></li>
            <li><a href="#">Contact Us</a></li>
            <li><a href="#">Terms of Use</a></li>
            <li><a href="#">Privacy Policy</a></li>
        </ul>
        </div>
		<div class="col-md-4 text-center text-light">
		<ul class="list-null">
            <li><a target="_blank" href="//facebook.com/#"><i class="fa fa-facebook"></i></a></li>
            <li><a target="_blank" href="//www.instagram.com/#"><i class="fa fa-instagram"></i></a></li>
            <li><a target="_blank" href="//www.linkedin.com/#"><i class="fa fa-linkedin"></i></a></li>
            <li><a target="_blank" href="//twitter.com/#"><i class="fa fa-twitter"></i></a></li>
        </ul>
		</div>
	</div>
</div>
</body>
</html>
	';
}
?>
