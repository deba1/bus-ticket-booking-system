<?php
$acc = "";
session_start();
if (isset($_GET['logout'])) {
	session_destroy();
}
elseif (isset($_SESSION['user'])) {
	if ($_SESSION['user']['utype'] == "Passenger") {
		header("Location: buy_ticket.php");
	}
	elseif ($_SESSION['user']['utype'] == "Owner") {
		header("Location: my_buses.php");
	}
	elseif ($_SESSION['user']['utype'] == "Admin") {
		header("Location: users.php");
	}
	else {
		header("Location: index.php?logout=1");
	}
}
elseif (isset($_POST['signup'])) {
	require_once 'inc/database.php';
	$conn = initDB();
	$sql = "insert into users (name, uname, email, password, gender, utype, address, mobile) values ('";
	$sql .= $_POST['name']."','".$_POST['uname']."','".$_POST['email']."','".$_POST['password']."','";
	$sql .= $_POST['gender']."','".$_POST['utype']."','".$_POST['address']."','".$_POST['mobile']."')";
	if ($conn->query($sql)) {
		$acc = "ok";
	}
	else {
		$acc = $sql . "<br/>" .$conn->error;
	}
	$conn->close();
}
elseif (isset($_POST['login'])) {
	require_once 'inc/database.php';
	$conn = initDB();
	$res = $conn->query("select id,utype from users where uname='" . $_POST['uname'] . "' and password='" . $_POST['upass'] . "'");
	if ($res->num_rows == 0)
		$acc = "Invalid Username or Password.";
	else {
		$data = $res->fetch_assoc();
		$_SESSION['user'] = array('id' => $data['id'], 'uname' => $_POST['uname'], 'utype' => $data['utype']);
		header("Location: index.php");
	}
	$conn->close();
}
else {}
include 'inc/basic_template.php';
t_header("Bus Ticket Booking");
t_navbar();
?>
<table width="100%">
<tr>
	<td width="70%">
		<img src="img/cover_bus.jpg" alt="Bus" height="100%"/>
	</div>
	<td width="30%">
		<?php
			if ($acc!="") {
				if ($acc == "ok") {
					echo '<div class="alert alert-success">Account <strong>Success</strong>fully Created!</div>';
				}
				else {
					echo '<div class="alert alert-danger"><strong>Error: </strong>'.$acc.'</div>';
				}
			}
		?>
		<h4 class="my-3">Create an Account</h4>
		<form action="index.php" method="post">
	    <div class="form-group row">
	      <label for="uname" class="col-sm-2 col-form-label">Username</label>
	      <div class="col-sm-7">
	        <input name="uname" type="text" class="form-control" id="inputUname" placeholder="Username"/>
	      </div>
		  <div class="col-sm-2" id="infoUname"></div>
	    </div>
	    <div class="form-group row">
	      <label for="name" class="col-sm-2 col-form-label">Name</label>
	      <div class="col-sm-7">
	        <input name="name" type="text" class="form-control" id="inputName" placeholder="Full Name"/>
	      </div>
		  <div class="col-sm-2" id="infoName"></div>
	    </div>
	    <div class="form-group row">
	      <label for="email" class="col-sm-2 col-form-label">Email</label>
	      <div class="col-sm-7">
	        <input name="email" type="text" class="form-control" id="inputEmail" placeholder="Email"/>
	      </div>
		  <div class="col-sm-2" id="infoEmail"></div>
	    </div>
	    <div class="form-group row">
	      <label for="upass" class="col-sm-2 col-form-label">Password</label>
	      <div class="col-sm-7">
	        <input name="password" type="password" class="form-control" id="inputPassword" placeholder="Password">
	      </div>
		  <div class="col-sm-2" id="infoPass"></div>
	    </div>
	    <div class="form-group row">
	      <label class="col-form-legend col-sm-2" for="gender">Gender</label>
	      <div class="col-sm-7 px-5">
	        <input class="form-check-input" type="radio" name="gender" id="radioMale" value="1" checked> Male <br/>
            <input class="form-check-input" type="radio" name="gender" id="radioFemale" value="2"> Female
	      </div>
	    </div>
	    <div class="form-group row">
	      <label class="col-form-legend col-sm-2" for="utype">User Type</label>
	      <div class="col-sm-7 px-5">
	        <input class="form-check-input" type="radio" name="utype" id="radioPass" value="3" checked> Passenger <br/>
            <input class="form-check-input" type="radio" name="utype" id="radioBO" value="2"> Bus Owner
	      </div>
	    </div>
	    <div class="form-group row">
	      <label for="address" class="col-sm-2 col-form-label">Address</label>
	      <div class="col-sm-7">
	        <input name="address" type="text" class="form-control" id="inputAddress" placeholder="Address" />
	      </div>
		  <div class="col-sm-2" id="infoAddress"></div>
	    </div>

	    <script type="text/javascript">
		var autocomplete;
		function initialize() {
			autocomplete = new google.maps.places.Autocomplete(document.getElementById("inputAddress"));
			autocomplete.setComponentRestrictions({'country': 'bd'});
			google.maps.event.addListener(autocomplete, 'place_changed', function() {});
		}
	    </script>
	    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDT6virNnyW0tcOqRcxKpdKENTESrp4ojE&libraries=places&callback=initialize" async defer></script>
		<div class="form-group row">
	      <label for="mobile" class="col-sm-2 col-form-label">Mobile</label>
	      <div class="col-sm-7 input-group">
	      	<span class="input-group-addon">+880</span>
	        <input name="mobile" type="text" class="form-control" id="inputMobile" placeholder="Mobile No."/>
	      </div>
		  <div class="col-sm-2" id="infoMobile"></div>
	    </div>
	    <div class="form-group row">
	      <div class="offset-sm-2 col-sm-10">
	        <button type="submit" class="btn btn-primary" name="signup">Sign Up</button>
	      </div>
	    </div>
		<script async>
		$("#inputUname").keyup(function() {
			$.ajax({
				url: "inc/ajax.php?type=username&q=" + $(this).val(),
				success: function(result) {
					$("#infoUname").html(result);
				}
			});
		});
		$("#inputName").keyup(function() {
			if ( $(this).val().match('^[a-zA-Z ]{3,16}$') ) {
				$("#infoName").html(' ');
			}
			else {
				$("#infoName").html('<span class="text-danger">Invalid Name</span>');
			}
		});
		$("#inputEmail").keyup(function() {
			$.ajax({
				url: "inc/ajax.php?type=email&q=" + $(this).val(),
				success: function(result) {
					$("#infoEmail").html(result);
				}
			});
		});
		$("#inputPassword").keyup(function() {
			if ($(this).val().length >= 6) {
				$("#infoPass").html(' ');
			}
			else {
				$("#infoPass").html('<span class="text-danger">Weak Password</span>');
			}
		});
		$("#inputAddress").keyup(function() {
			if ( $(this).val().match('^[a-zA-Z0-9 ,-]{5,120}$') ) {
				$("#infoAddress").html(' ');
			}
			else {
				$("#infoAddress").html('<span class="text-danger">Invalid Address</span>');
			}
		});
		$("#inputMobile").keyup(function() {
			if ( $(this).val().match('^[0-9]{10,10}$') ) {
				$("#infoMobile").html(' ');
			}
			else {
				$("#infoMobile").html('<span class="text-danger">Invalid Number</span>');
			}
		});
		</script>
	  </form>
	</td>
</tr>
</table>
<?php
t_footer();
?>