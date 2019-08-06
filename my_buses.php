<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['utype'] != "Owner")
    header("Location: index.php");
$add = "";
if (isset($_POST['add'])) {
    require_once 'inc/database.php';
	$conn = initDB();
	$sql = "insert into buses (bname, bus_no, from_loc, from_time, to_loc, to_time, fare, owner_id) values ('";
	$sql .= $_POST['bname']."','".$_POST['bus_no']."','".$_POST['from_loc']."','".$_POST['from_time']."','";
	$sql .= $_POST['to_loc']."','".$_POST['to_time']."','".$_POST['fare']."','".$_SESSION['user']['id']."')";
	if ($conn->query($sql)) {
		$add = "ok";
	}
	else {
		$add = $sql . "<br/>" .$conn->error;
	}
	$conn->close();
}
include 'inc/basic_template.php';
t_header("Bus Ticket Booking");
t_login_nav();
t_owner_sidebar();
?>
<div class="modal" tabindex="-1" role="dialog" style="display: <?php echo ($_GET['act'] == 'add') ? 'block' : 'none';?>">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Bus</h5>
        <a href="my_buses.php"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button></a>
      </div>
      <form method="post" action="my_buses.php">
      <div class="modal-body">
        <p>
        <div class="form-group row">
            <div class="col-sm-3">Bus Name</div>
                <div class="col-sm-8">
                    <input type="text" name="bname" class="form-control"/>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3">Bus No.</div>
                <div class="col-sm-8">
                    <input type="text" name="bus_no" class="form-control"/>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3">From</div>
                <div class="col-sm-8">
                    <input type="text" name="from_loc" class="form-control" id="inputFrom"/>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3">Diparture Time</div>
                <div class="col-sm-8">
                    <input type="text" name="from_time" class="form-control"/>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3">To</div>
                <div class="col-sm-8">
                    <input type="text" name="to_loc" class="form-control" id="inputTo"/>
            </div>
        </div>
        <link rel="stylesheet" href="css/easy-autocomplete.min.css"/>
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
            <div class="col-sm-3">Arrival Time</div>
                <div class="col-sm-8">
                    <input type="text" name="to_time" class="form-control"/>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3">Fare</div>
                <div class="col-sm-8">
                    <input type="text" name="fare" class="form-control"/>
            </div>
        </div>
        </p>
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-primary" value="Add" name="add"/>
        <a href="my_buses.php"><button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button></a>
      </div>
      </form>
    </div>
  </div>
</div>
<div class="container">
<?php
    if ($add!="") {
        if ($add == "ok") {
            echo '<div class="alert alert-success">Bus Added <strong>Success</strong>fully!</div>';
        }
        else {
            echo '<div class="alert alert-danger"><strong>Error: </strong>'.$acc.'</div>';
        }
    }
?>
<div class="row mb-2">
    <h4 class="col-md-3">My Buses</h4>
    <div class="col-md-8 text-right ml-4">
        <a href="my_buses.php?act=add"><button type="button" class="btn btn-sm btn-primary">+ Add Bus</button></a>
    </div>
</div>
<table width="95%" class="table-con">
<tr class="head">
    <th>ID</th>
    <th>Bus Name</th>
    <th>Bus No.</th>
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
$res = $conn->query("select * from buses where owner_id=" . $_SESSION['user']['id']);
if ($res->num_rows == 0) {
    echo '
    <tr class="row">
        <td colspan="9">No Bus</td>
    </tr>';
}
else {
    while ($row = $res->fetch_assoc()) {
        echo '
        <tr class="content">
            <td>' . $row["id"] . '</td>
            <td>' . $row["bname"] . '</td>
            <td>' . $row["bus_no"] . '</td>
            <td>' . $row["from_loc"] . '</td>
            <td>' . $row["from_time"] . '</td>
            <td>' . $row["to_loc"] . '</td>
            <td>' . $row["to_time"] . '</td>
            <td>' . $row["fare"] . '</td>
            <td>' . (($row["approved"]) ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>' ) . '</td>
        </tr>';
    }
}
$conn->close();
?>
</table>
</div>
<?php
t_footer();
?>