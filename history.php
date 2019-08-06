<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['utype'] != "Passenger")
    header("Location: index.php");
include 'inc/basic_template.php';
t_header("Bus Ticket Booking &mdash; History");
t_login_nav();
t_sidebar();
?>

<div class="container">
    <div class="popup" id="seatViewer"></div>
    <div class="loader text-center" id="wait"><img src="/img/bus-loader.gif" alt="Wait..."/></div>
    <h4>History</h4>
<div class="table-con">
<div class="row">
    <div class="col-md-1">ID</div>
    <div class="col-md-2">Bus Name</div>
    <div class="col-md-1">From</div>
    <div class="col-md-1">To</div>
    <div class="col-md-2">Dep. Time</div>
    <div class="col-md-2">Arr. Time</div>
    <div class="col-md-1">J. Date</div>
    <div class="col-md-1">Fare (৳)</div>
    <div class="col-md-1">Seats</div>
</div>
<?php
require_once 'inc/database.php';
$conn = initDB();
$query = "select t.id as t_id, t.jdate, t.fare, t.seats, b.id as bus_id, b.bname as bus_name,";
$query .= "b.from_loc, b.to_loc, b.from_time, b.to_time from tickets t, buses b where t.bus_id = b.id and t.passenger_id=" . $_SESSION['user']['id'];
$res = $conn->query($query);
if ($res->num_rows == 0) {
    echo '
    <div class="row">
        <div class="col-md-12">No Tickets</div>
    </div>';
}
else {
    while ($row = $res->fetch_assoc()) {
        echo '
        <div class="content row">
            <div class="col-md-1">' . $row["t_id"] . '</div>
            <div class="col-md-2">' . $row["bus_name"] . '</div>
            <div class="col-md-1">' . $row["from_loc"] . '</div>
            <div class="col-md-1">' . $row["to_loc"] . '</div>
            <div class="col-md-2">' . $row["from_time"] . '</div>
            <div class="col-md-2">' . $row["to_time"] . '</div>
            <div class="col-md-1">' . $row["jdate"] . '</div>
            <div class="col-md-1">' . $row["fare"] . '</div>
            <div class="col-md-1">'.count(unserialize($row["seats"])).'</div>
        </div>';
    }
}
$conn->close();
?>
</div>
</div>
<script>
    $(".content").click(function() {
        var ticket = $(this).find(">:first-child").html();
        $.ajax({
            url: "/inc/ajax.php?type=showseats&ticket=" + ticket,
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
        }, 1000);
    });
</script>
<?php
t_footer();
?>