<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['utype'] != "Passenger")
  header("Location: index.php");
include 'inc/basic_template.php';
t_header("Bus Ticket Booking &mdash; Buy Tickets");
t_login_nav();
t_sidebar();

if (isset($_POST['buy'])) {
  require_once 'inc/database.php';
  $conn = initDB();
  if (!isset($_POST['seats']) || $_POST['seats'] == "") {
    echo '<div class="alert alert-danger"><strong>Error: </strong>No seats selected</div>';
  }
  else {
    $sql = "insert into tickets (passenger_id, bus_id, jdate, seats, fare) values ('";
    $sql .= $_SESSION['user']['id']."','".$_POST['bus_id']."','".$_POST['jdate']."','".serialize($_POST['seats'])."','";
    $sql .= $_POST['fare']."')";

    if ($conn->query($sql)) {
      echo '<div class="alert alert-success">Purchase <strong>Success</strong>full!<a class="text-right" href="print.php?ticket='.$conn->insert_id.'"><button class="btn btn-info">Print</button></a></div>';
    }
    else {
      echo '<div class="alert alert-danger"><strong>Error: </strong>'.$conn->error.'</div>';
    }
  }
	$conn->close();
}
?>
<!-- Select Locations -->
<link rel="stylesheet" href="css/easy-autocomplete.min.css"/>
<link rel="stylesheet" href="css/bootstrap-datepicker.min.css"/>
<form action="" method="get">
	<div class="form-group row">
      <label for="from" class="col-sm-2 col-form-label">From</label>
      <div class="col-sm-7 well">
      	<input type="text" class="form-control" id="inputFrom" name="from" value="<?php echo (isset($_GET['from'])) ? $_GET['from'] : ''; ?>"/>
      </div>
  </div>
  <div class="form-group row">
    <label for="to" class="col-sm-2 col-form-label">To</label>
    <div class="col-sm-7">
      <input type="text" class="form-control" id="inputTo" name="to" value="<?php echo (isset($_GET['to'])) ? $_GET['to'] : ''; ?>" />
    </div>
  </div>
  <script src="js/jquery.easy-autocomplete.min.js"></script>
  <script>
    var opt = {
      url: "inc/ajax.php?type=locations",
      list: {
        match: {
          enabled: true
        }
      }
    };
    $("#inputFrom").easyAutocomplete(opt);
    $("#inputTo").easyAutocomplete(opt);
  </script>
  <div class="form-group row">
    <label for="jdate" class="col-sm-2 col-form-label">Journey Date</label>
    <div class="col-sm-7 input-group">
      <input name="jdate" class="form-control" id="inputJDate" type="text" value="<?php echo (isset($_GET['jdate'])) ? $_GET['jdate'] : ''; ?>"/>
    </div>
  </div>
  <script src="js/bootstrap-datepicker.min.js"></script>
  <script>
    $('#inputJDate').datepicker({
      format: "dd/mm/yyyy",
      weekStart: 6,
      startDate: "today",
      autoclose: true,
      todayHighlight: true
    });
  </script>
  <div class="form-group row">
    <div class="col-sm-2"></div>
    <div class="col-sm-7">
      <input type="submit" class="btn btn-info" name="submit" value="Submit" />
    </div>
  </div>
</form>
<div class="popup" id="seatViewer"></div>
<div class="loader text-center" id="wait"><img src="/img/bus-loader.gif" alt="Wait..."/></div>
<div class="table-con">
<div class="row">
  <div class="col-sm-2">Sl.</div>
  <div class="col-sm-4">Bus Name</div>
  <div class="col-sm-2">Dip. Time</div>
  <div class="col-sm-2">Arr. Time</div>
  <div class="col-sm-2">Fare (৳)</div>
</div>
<?php
require_once 'inc/database.php';
$conn = initDB();
$from = isset($_GET['from']) ? $_GET['from'] : "";
$to = isset($_GET['to']) ? $_GET['to'] : "";
$res = $conn->query("select * from buses where  from_loc='".$from."' and to_loc='".$to."'");
if ($res->num_rows == 0 || !isset($_GET['jdate']) || $_GET['jdate'] == '') {
  echo '<div class="row">
    <div class="col-sm-12 text-center"><h4>No Bus</h4></div>
  </div>';
}
else {
  while ($row = $res->fetch_assoc()) {
    echo '
    <div class="row content">
      <div class="col-sm-2">'.$row['id'].'</div>
      <div class="col-sm-4">'.$row['bname'].'</div>
      <div class="col-sm-2">'.$row['from_time'].'</div>
      <div class="col-sm-2">'.$row['to_time'].'</div>
      <div class="col-sm-2">'.$row['fare'].'</div>
    </div>
    ';
  }
}
$conn->close();
?>
</div>
<script>
$(".content").click(function() {
    var bus = $(this).find(">:first-child").html();
    var date = "<?php echo $_GET['jdate'];?>";
    $.ajax({
        url: "/inc/ajax.php?type=showseats&bus=" + bus + "&date=" + date,
        success: function(result) {
            setTimeout(function() {
                $("#seatViewer").html(result);
            }, 1000);
        },
        beforeSend: function() {
            $("#wait").show();
        },
        complete: function() {
            setTimeout(function() {
                $("#wait").hide();
            }, 1000);
        }
    });
    setTimeout(function() {
        $("#seatViewer").show();
    }, 1100);
});
</script>
<br>
<br>
<br>
<br>
<br>
<br>
<?php
t_footer();
?>