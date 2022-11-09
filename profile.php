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
                <form method="post" enctype="multipart/form-data">
                    <div class='card-header'>
                        <h4>แก้ไข</h4>
                    </div>
                    <div class='card-body'>
                        <p>
                            <label>รูปโปรไฟล์</label>
                            <input name='img' value='<?php echo $_SESSION['username']; ?>' type="file" class='form-control'>
                        </p>
                        <p>
                            <label>ชื่อผู้ใช้</label>
                            <input name='username' value='<?php echo $_SESSION['username']; ?>' readonly type="text" class='form-control'>
                        </p>
                        <p>
                            <label>ชื่อจริง</label>
                            <input name='fullname' value='<?php echo $_SESSION['fullname']; ?>' type="text" class='form-control'>
                        </p>
                        <p>
                            <label>อีเมล</label>
                            <input name='email' value='<?php echo $_SESSION['email']; ?>' type="text" class='form-control'>
                        </p>
                        <p>
                            <label>ที่อยู่</label>
                            <input name='address' value='<?php echo $_SESSION['address']; ?>' type="text" class='form-control'>
                        </p>
                        <p>
                            <label>เบอร์โทร</label>
                            <input name='tel' value='<?php echo $_SESSION['tel']; ?>' type="number" class='form-control'>
                        </p>
                        <?php if ($_SESSION['user_type'] == "Rider") { ?>
                            <p>
                                <label>เลขทะเบียนรถ</label>
                                <input name='car_no' type="number" class='form-control' placeholder='สำหรับ Rider เท่านั้น ไม่ใช่ Rider อย่าใส่ไม่งั้นแบน!'>
                            </p>
                        <?php } ?>
                    </div>
                    <div class='card-footer px-2 pb-0'>
                        <button name='edit' class='btn btn-primary me-1'>แก้ไข</button>
                        <a href="../index.php" class='btn btn-danger'>ยกเลิก</a>
                    </div>
                </form>
                <?php
                if (isset($_POST['edit']))
                {
                    $fullname = $_POST['fullname'];
                    $email = $_POST['email'];
                    $address = $_POST['address'];
                    $tel = $_POST['tel'];
                    $car_no = "";
                    if ($_SESSION['user_type'] == "rider")
                    {
                        $_POST['car_no'] = $_POST['car_no'];
                    }
                    function check_empty($ori, $cust)
                    {
                        if (empty($ori) || ctype_space($ori))
                        {
                            $_SESSION['error'] = "โปรดใส่ " . $cust;
                            header("location: register.php");
                            return true;
                        }
                        return false;
                    }
                    if (check_empty($fullname, "ชื่อจริง")) return;
                    if (check_empty($email, "อีเมล")) return;
                    if (check_empty($address, "ที่อยู่")) return;
                    if (check_empty($tel, "เบอร์โทร")) return;
                    if ($_SESSION['user_type'] == "Rider")
                    {
                        if (check_empty($tel, "ทะเบียนรถ")) return;
                    }
                    $sql = "";

                    $img = "KoonBest.png";
                    if (empty($_SESSION['picture']) || ctype_space($_SESSION['picture'])) {
                        $img = "KoonBest.png";  
                    } else {
                        $img = $_SESSION['picture'];
                    }

                    $target_dir = "../img/";
                    $target_path = $target_dir . basename($_FILES['img']['name']);
                    if ($_FILES['img']['size'] != 0)
                    {
                        if (move_uploaded_file($_FILES['img']['tmp_name'], $target_path))
                        {
                            $img = basename($_FILES['img']['name']);
                        }
                    }

                    if ($_SESSION['user_type'] == "Rider")
                    {
                        $sql = "update user set 
                        fullname='$fullname',
                        address='$address',
                        email='$email',
                        tel='$tel',
                        car_no='$car_no',
                        picture='$img'
                        where user_id='{$_SESSION['user_id']}'";
                    } 
                    else 
                    {
                        $sql = "update user set 
                        fullname='$fullname',
                        address='$address',
                        email='$email',
                        tel='$tel',
                        picture='$img'
                        where user_id='{$_SESSION['user_id']}'";
                    }
                    $query = $mysqli -> query($sql);
                    if ($query)
                    {
                        $_SESSION['success'] = "Success!";
                        $_SESSION['fullname'] = $fullname;
                        $_SESSION['address'] = $address;
                        $_SESSION['email'] = $email;
                        $_SESSION['tel'] = $tel;
                        $_SESSION['picture'] = $img;
                        header("location: profile.php");
                    } else {
                        $_SESSION['error'] = "Failed!";
                        header("location: profile.php");
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>