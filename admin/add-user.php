<?php
    require_once 'head.php';
    if(isset($_POST['submit'])){
        $error      = array();
        $date       = new DateTime();
        $createdAt  = $date->format("Y-m-d H:i:s");
        $ho_ten   = $_POST['ho_ten'];
        $username   = $_POST['username'];
        $sdt      = $_POST['sdt'];
        $password   = $_POST['password'];
        $phan_quyen = $_POST['phan_quyen'];
        $chan_user = $_POST['chan_user'];
        if(strlen($ho_ten) <= 10){
            $error['ho_ten'] = 'Họ và tên phải lớn hơn 10 kí tự';
        }

        if (strlen($username) <= 4) {
            $error['username'] = 'Tên đăng nhập phải trên 4 kí tự';
        } elseif (strlen($username) >= 16) {
            $error['username'] = 'Tên đăng nhập phải nhỏ hơn 16 kí tự';
        } elseif (runSQL("SELECT username FROM user WHERE username='".$username."'")!=NULL) {
            $error['username'] = 'Tên đăng nhập đã tồn tại';
        }

        if (strlen($sdt) < 10 || strlen($sdt) > 12 || !str_starts_with($sdt, 0)) {
            $error['sdt'] = 'Sai số điện thoại';
        }

        if(strlen($password) < 6){
            $error['password'] = 'Mật khẩu phải lớn hơn 5 kí tự';
        }  else if (strlen($password) > 15) {
            $error['password'] = 'Mật khẩu phải nhỏ hơn 15 ký tự';
        }
        
        if(count($error) == 0){
            $sql = "INSERT INTO user VALUES(null, '$ho_ten', '$username', '".md5($password)."', '$sdt', $phan_quyen, 0)";
            $query = $db->prepare($sql);
            $query->execute();
            echo '<script>alert("Thêm tài khoản thành công");window.location.href="list-user.php"</script>';
        } else {
            echo '<script>alert("Thêm tài khoản err")</script>';
        }
    }
?>
            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-12">
                                <div class="card-box">

                                    <div class="row mt-4">
                                        <div class="col-sm-12">
                                            <h4 class="header-title">Thêm tài khoản</h4>
                                            <form action="" method="POST" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <label>Họ và tên:</label>
                                                    <input class="form-control" value="<?php echo (isset($ho_ten)) ? $ho_ten : '' ?>" type="text" name="ho_ten" placeholder="Họ và tên" required>
                                                    <span class="error"><?php echo (isset($error['ho_ten'])) ? $error['ho_ten'] : '' ?></span>
                                                </div>

                                                <div class="form-group">
                                                    <label>Tên đăng nhập:</label>
                                                    <input class="form-control" value="<?php echo (isset($username)) ? $username : '' ?>" type="text" name="username" placeholder="Tên đăng nhập" required>
                                                    <span class="error"><?php echo (isset($error['username'])) ? $error['username'] : '' ?></span>
                                                </div>

                                                <div class="form-group">
                                                    <label>Số điện thoại:</label>
                                                    <input class="form-control" value="<?php echo (isset($sdt)) ? $sdt : '' ?>" type="sdt" name="sdt" placeholder="Số điện thoại" required>
                                                    <span class="error"><?php echo (isset($error['sdt'])) ? $error['sdt'] : '' ?></span>
                                                </div>

                                                <div class="form-group">
                                                    <label>Mật khẩu:</label>
                                                    <input class="form-control" value="<?php echo (isset($password)) ? $password : '' ?>" type="password" name="password" placeholder="Mật khẩu" required>
                                                    <span class="error"><?php echo (isset($error['password'])) ? $error['password'] : '' ?></span>
                                                </div>

                                                <div class="form-group">
                                                    <label>Phân quyền:</label>
                                                        <select class="form-control" name="phan_quyen">
                                                            <option value="0" selected>Thành Viên</option>
                                                            <option value="1">Quản Trị</option>
                                                        </select>
                                                </div>

                                                <div class="form-group">
                                                    <input type="submit" name="submit" class="btn btn-success" value="Thêm tài khoản">
                                                </div>

                                            </form>
                                        </div>
                                    </div>

                                </div>
                                <!-- end card-box -->
                            </div>
                            <!-- end col-->

                        </div>
                        <!-- end row -->

                    </div>
                    <!-- end container-fluid -->

                </div>
                <!-- end content -->
<?php
    require 'foot.php';
?>