<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['utype'] != "Admin")
    header("Location: index.php");

include 'inc/basic_template.php';
t_header("Bus user Booking &mdash; User Manager");
t_login_nav();
t_admin_sidebar();
?>

<div class="container">
    <div class="popup" id="userViewer"></div>
    <div class="loader text-center" id="wait"><img src="/img/bus-loader.gif" alt="Wait..."/></div>
    <div class="row mb-2">
    <h4 class="col-md-3">Users</h4>
    <div class="col-md-8 text-right ml-4">
    <form method="post" action="">
    	<input type="text" name="user" class="form-control-sm" value="<?php echo (isset($_POST['user'])) ? $_POST['user'] : ""; ?>">
        <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Search</button>
    </form>
    </div>
</div>
<div class="table-con">
<div class="row">
    <div class="col-md-1">ID</div>
    <div class="col-md-1">UserName</div>
    <div class="col-md-2">Name</div>
    <div class="col-md-2">Email</div>
    <div class="col-md-1">Gender</div>
    <div class="col-md-1">Type</div>
    <div class="col-md-2">Address</div>
    <div class="col-md-2">Mobile</div>
</div>
<?php
require_once 'inc/database.php';
$conn = initDB();
$query = "select * from users where id<>" . $_SESSION['user']['id'];
if (isset($_POST['user'])) {
	$query .= " and (uname like '%".$_POST['user']."%' or name like '%".$_POST['user']."%')";
}
$res = $conn->query($query);
if ($res->num_rows == 0) {
    echo '
    <div class="row">
        <div class="col-md-12">No Users</div>
    </div>';
}
else {
    while ($row = $res->fetch_assoc()) {
        echo '
        <div class="content row">
            <div class="col-md-1">' . $row["id"] . '</div>
            <div class="col-md-1">' . $row["uname"] . '</div>
            <div class="col-md-2">' . $row["name"] . '</div>
            <div class="col-md-2">' . $row["email"] . '</div>
            <div class="col-md-1">' . $row["gender"] . '</div>
            <div class="col-md-1">' . $row["utype"] . '</div>
            <div class="col-md-2">' . $row["address"] . '</div>
            <div class="col-md-2">0' . $row["mobile"] . '</div>
        </div>';
    }
}
$conn->close();
?>
</div>
</div>
<script>
    $(".content").click(function() {
        var user = $(this).find(">:first-child").html();
        $.ajax({
            url: "/inc/ajax.php?type=user&user=" + user,
            success: function(result) {
                setTimeout(function() {
                    $("#userViewer").html(result);
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
            $("#userViewer").show();
        }, 1000);
    });
</script>

<?php
t_footer();
?>