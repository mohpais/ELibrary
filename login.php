<?php
    // Start the session
    session_start();

    // Check if the user is already logged in, redirect to the dashboard
    if (isset($_SESSION['user'])) {
        header('Location: beranda.php');
        exit;
    } else {
        session_destroy();
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>E-Library | Masuk</title>
    <link href="assets/css/login.css" rel="stylesheet" />
    <!-- Toast Library -->
    <link href="assets/lib/toast/toast.min.css" rel="stylesheet" />
    <style>
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-10">
                    <div class="wrap d-md-flex">
                        <div class="text-wrap p-3 p-lg-4 text-center d-flex align-items-center order-md-last">
                            <div class="text w-100">
                                <h2>Perpustakaan Online</h2>
                                <h4>Fakultas Ilmu Komputer</h4>
                            </div>
                        </div>
                        <div class="login-wrap p-4 p-lg-5">
                            <div class="w-100">
                                <h3 class="mb-4">Selamat Datang</h3>
                            </div>
                            <form id="loginForm" action="POST" class="signin-form" novalidate>
                                <div class="form-group mb-3">
                                    <label class="label" for="kode_user">Kode User</label>
                                    <input type="text" class="form-control" id="kode_user" name="kode_user"
                                        placeholder="Masukkan kode dosen / mahasiswa ..." onkeypress="return onlyNumberKey(event)" />
                                </div>
                                <div class="form-group mb-3">
                                    <label class="label" for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Masukkan kata sandi ..." />
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="form-control btn btn-primary submit px-3">Masuk</button>
                                </div>
                                <div class="form-group d-md-flex">
                                    <div class="w-50 text-left">
                                        Tidak punya akun?
                                    </div>
                                    <div class="w-50 text-md-right">
                                        <a href="register.php" class="text-primary">Daftar disini!</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="assets/js/scripts.js"></script>
    <!-- JQuery Library -->
    <script src="assets/lib/jquery/jquery.min.js"></script>
    <script src="assets/lib/jquery/jquery.validate.min.js"></script>
    <script src="assets/lib/jquery/additional-methods.min.js"></script>
    <!-- Toast Library -->
    <script src="assets/lib/toast/toast.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#loginForm").validate({
                rules: {
                    kode_user: "required",
                    password: "required"
                },
                messages: {
                    kode_user: "Kode tidak boleh kosong.",
                    password: "Kata sandi tidak boleh kosong."
                },
                submitHandler: function(form, event) {
                    event.preventDefault();
                    // Serialize the form data
                    var formData = $(form).serialize();
                    $.ajax({
                        method:"POST",
                        url: "services/auth/login-service.php",
                        data: formData,
                        success: function(response) {
                            if(response.success) {
                                toastr.success("Login berhasil");
                                // window.localStorage.setItem("user", JSON.stringify(response.message));
                                window.location.href="beranda.php";
                            } else {
                                toastr.error(response.message);
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>