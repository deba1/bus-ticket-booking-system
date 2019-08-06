<?php
require_once 'database.php';
switch ($_GET['type']) {

    case 'locations':
        $conn = initDB();
        $res = $conn->query("select name from locations");
        $jarr = array();
        while ($row = $res->fetch_assoc()) {
            $jarr[] = $row['name'];
        }
        echo json_encode($jarr);
        $conn->close();
        break;


    case 'username':
        if (strlen($_GET['q']) < 3)
            die('<span class="text-danger">Invalid Username</span>');
        $conn = initDB();
        $res = $conn->query("select id from users where uname='" . $_GET['q'] . "'");
        if ($res->num_rows == 0)
            echo '<span class="text-success">Username Available</span>';
        else
            echo '<span class="text-danger">Username Unvailable</span>';
        $conn->close();
        break;


    case 'email':
        if (!filter_var($_GET['q'], FILTER_VALIDATE_EMAIL))
            die('<span class="text-danger">Invalid Email</span>');
        $conn = initDB();
        $res = $conn->query("select id from users where email='" . $_GET['q'] . "'");
        if ($res->num_rows == 0)
            echo '';
        else
            echo '<span class="text-danger">Email Already Exist</span>';
        $conn->close();
        break;


    case 'showseats':
        $con = initDB();
        $query = "";
        $busid = "";
        $date = "";
        $fare = 0;
        if (isset($_GET['ticket'])) {
            $query = "select bus_id, jdate, seats, fare from tickets where id=".$_GET['ticket'];
        }
        else {
            $query = "select seats from tickets where bus_id='".$_GET['bus']."' and jdate='".$_GET['date']."'";
            $busid = $_GET['bus'];
            $date = $_GET['date'];
        }
        $seats = array("A1","A2","A3","A4","B1","B2","B3","B4","C1","C2","C3","C4","D1","D2","D3","D4","E1","E2","E3","E4","F1","F2","F3","F4","G1","G2","G3","G4","H1","H2","H3","H4","I1","I2","I3","J4","J1","J2","J3","J4");
        $reserved = array();
        $res = $con->query($query);
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $reserved = array_merge($reserved, unserialize($row['seats']));
                if (isset($_GET['ticket'])) {
                    $busid = "".$row['bus_id'];
                    $date = $row['jdate'];
                    $fare = $row['fare'];
                }
            }
        }
        $con->close();
        $con = initDB();
        $res = $con->query("select * from buses where id=".$busid);
        $businfo = $res->fetch_assoc();
        $con->close();
        echo '<div class="modal modal-lg" tabindex="-1" role="dialog" style="display: block">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">'.$businfo['bname'].'</h5>
            <a onclick="$(\'#seatViewer\').hide()"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button></a>
            </div>';
            if (!isset($_GET['ticket']))
                echo '<form method="post" action="buy_ticket.php?from='.$businfo['from_loc'].'&to='.$businfo['to_loc'].'&jdate='.$_GET['date'].'">';
            echo '<div class="modal-body">
            <div class="row">
            <div class="col-md-6 bus-model text-center">';

            for ($i=0; $i < count($seats); $i++) { 
                echo '<input type="checkbox" class="seat" name="seats[]" value="'.$seats[$i].'" title="'.$seats[$i].'" '.((in_array($seats[$i],$reserved)) ? (isset($_GET['ticket'])) ? 'checked' : 'disabled' : '').((isset($_GET['ticket'])) ? ' disabled' : '').'/>';
                if (($i+1) % 4 == 0)
                    echo '<br/>';
                elseif (($i+1) % 2 == 0)
                echo '<span style="margin-left: 25px"></span>';
            }
    
            echo '</div>
            <div class="col-md-6">
            Bus No.: '.$businfo['bus_no'].'<br/>
            Journey Date: '.$date.'<br/>
            Fare: <span id="fare">' . (($fare == 0) ? '0' : ($fare-50)).  '</span> BDT<br/>
            Charge: 50 BDT<br/>
            Total: <span id="total">'.$fare.'</span> BDT
            </div>
            </div>
            </div>
            <div class="modal-footer">';
            if (!isset($_GET['ticket'])) {
                echo '<input type="hidden" name="bus_id" value="'.$busid.'"/>
                <input type="hidden" name="jdate" value="'.$date.'"/>
                <input type="hidden" name="fare" id="ifare" value="0"/>
                <input type="submit" class="btn btn-primary" value="Buy" name="buy"/>
                </form>';
            }
            echo '<a onclick="$(\'#seatViewer\').hide()"><button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button></a>
            </div>
            <script async>
            $(".seat[type=checkbox]").click(
                function() {
                    let sts = $("input[type=checkbox]:checked").length;
                    $("#fare").html(sts*'.$businfo['fare'].');
                    $("#total").html(sts*'.$businfo['fare'].'+50);
                    let total = 0;
                    if (sts != 0)
                        total = sts*'.$businfo['fare'].'+50;
                    $("#ifare").val(total);
                }
            );
            </script>
        </div>
        </div>
        </div>';
        break;


    default:
        break;
}