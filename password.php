<?php
include("../header.php");
?>
<!DOCTYPE html>
<html lang="en">

<style>
    body, html {
        height: 80vh;
        background-color: #303030;
        color: white;
    }
</style>

<body>
    <div class='container-fluid d-flex align-items-center justify-content-center h-100'>
        <div class='col-lg-5 col-md-8 col-sm-12 col-12 my-auto'>
            <?php if (isset($_SESSION['success'])) { ?>
                <div class='alert alert-success py-2'><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php } ?>
            <?php if (isset($_SESSION['error'])) { ?>
                <div class='alert alert-danger py-2'><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php } ?>
            <div class='card bg-dark'>
                <form method="post">
                    <div class='card-header'>
                        <h4>แก้ไขรหัสผ่าน</h4>
                    </div>
                    <div class='card-body'>
                            <p>
                                <label>รหัสผ่านเก่า</label>
                                <input name='old_password' type="password" class='form-control'>
                            </p>
                            <p>
                                <label>รหัสผ่านใหม่</label>
                                <input name='new_password' type="password" class='form-control'>
                            </p>
                    </div>
                    <div class='card-footer px-2 pb-0'>
                        <button name='login' class='btn btn-primary me-1'>แก้ไข</button>
                        <a href="../index.php" class='btn btn-danger'>ยกเลิก</a>
                    </div>
                </form>
                <?php
                if (isset($_POST['login']))
                {
                    $old_password = $_POST['old_password'];
                    $new_password = $_POST['new_password'];
                    function check_empty($ori, $cust)
                    {
                        if (empty($ori) || ctype_space($ori))
                        {
                            $_SESSION['error'] = "โปรดใส่ " . $cust;
                            header("location: ../action/password.php");
                            return true;
                        }
                        return false;
                    }
                    if (check_empty($old_password, "รหัสผ่านเก่า")) return;
                    if (check_empty($new_password, "รหัสผ่านใหม่")) return;
                    $get = "select * from user where user_id='{$_SESSION['user_id']}'";
                    $result = $mysqli -> query($get);
                    if ($result -> num_rows > 0)
                    {
                        $obj = $result -> fetch_object();
                        if ($old_password != $obj -> password)
                        {
                            $_SESSION['error'] = "รหัสผ่านเก่าผิด!";
                            header("location: password.php");
                            return;
                        }
                        $sql = "update user set password='$new_password' where user_id='{$_SESSION['user_id']}'";
                        $query = $mysqli -> query($sql);
                        if ($query )
                        {
                            $_SESSION['success'] = "Success!";
                            header("location: password.php");
                        }
                        else
                        {
                            $_SESSION['error'] = "Failed";
                            header("location: password.php");
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>