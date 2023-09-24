<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>E-Library | Login</title>
        <link href="assets/css/styles.css" rel="stylesheet" />
        <link href="assets/css/custom.css" rel="stylesheet" />
        <!-- Toast Library -->
        <link href="assets/lib/toast/toast.min.css" rel="stylesheet" />
        <!-- Bootstrap Library -->
        <link href="assets/lib/bootstrap/bootstrap-datepicker.min.css" rel="stylesheet" />
    </head>
    <body class="bg-ubk py-auto">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main >
                    <div class="container">
                        <div class="row justify-content-center align-items-center min-vh-100">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg">
                                    <div class="card-body">
                                        <div class="w-100 text-center mb-4">
                                            <div class="h2 fw-500 text-ubk">Selamat Datang!</div>
                                            <div class="h5 fw-400 text-dark">Silahkan masuk untuk melanjutkan ...</div>
                                        </div>
                                        <form id="loginForm" class="row g-3" method="POST" novalidate>
                                            <div class="col-12">
                                                <label for="kode_user" class="form-label mb-1">Kode Dosen / Mahasiswa</label>
                                                <input type="text" class="form-control" id="kode_user" name="kode_user"
                                                    placeholder="Masukkan kode dosen / mahasiswa ..." onkeypress="return onlyNumberKey(event)" />
                                            </div>
                                            <div class="col-12 mt-2">
                                                <label for="password" class="form-label mb-1">Kata Sandi</label>
                                                <input type="password" class="form-control" id="password" name="password"
                                                    placeholder="Masukkan kata sandi ..." />
                                            </div>
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-ubk">Masuk</button>
                                                <!-- <a href="dashboard.php" class="btn btn-ubk">Masuk</a> -->
                                            </div>
                                            <div class="d-grid gap-0 mt-3 mb-0">
                                                <p>Tidak punya akun? <a href="register.php">Buat akun!</a></p>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
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
                                    window.localStorage.setItem("user", JSON.stringify(response.message));
                                    window.location.href="dashboard.php";
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
