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
    <title>E-Library | Pendaftaran</title>
    <link href="assets/css/login.css" rel="stylesheet" />
    <!-- Toast Library -->
    <link href="assets/lib/toast/toast.min.css" rel="stylesheet" />
    <style>
        .error {
            color: red;
        }
    </style>
</head>

<body >
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
                        <div class="login-wrap px-4 py-3 py-lg-4">
                            <div class="w-100">
                                <h3 class="mb-2">Daftar disini!</h3>
                            </div>
                            <form id="registrationForm" action="POST" class="signin-form" novalidate>
                                <div class="form-group mb-1">
                                    <label class="label" for="kode_user">Kode User</label>
                                    <input type="text" class="form-control" id="kode_user" name="kode_user"
                                                placeholder="Masukkan kode mahasiswa ..." onkeypress="return onlyNumberKey(event)" />
                                </div>
                                <div class="form-group mb-1">
                                    <label class="label" for="full_name">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name"
                                                placeholder="Masukkan nama lengkap ..." />
                                </div>
                                <div class="form-group mb-1">
                                    <label class="label" for="password">Kata Sandi</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Masukkan kata sandi ..." />
                                </div>
                                <div class="form-group mb-1">
                                    <label class="label" for="confirm_password">Ulangi Kata Sandi</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                        placeholder="Masukkan kata sandi ..." />
                                </div>
                                <div class="form-group mt-3">
                                    <button type="submit" class="form-control btn btn-primary submit px-3">Daftar</button>
                                </div>
                                <div class="form-group d-md-flex">
                                    <div class="w-50 text-left">
                                        Sudah punya akun?
                                    </div>
                                    <div class="w-50 text-md-right">
                                        <a href="login.php" class="text-primary">Masuk disini!</a>
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
            // toastr.error('Are you the 6 fingered man?')
            var validator = $("#registrationForm").validate({
                rules: {
                    kode_user: "required",
                    full_name: "required",
                    password: {
                        required: true,
                        minlength: 6
                    },
                    confirm_password: {
                        required: true,
                        minlength: 6,
                        equalTo: "#password"
                    }
                },
                messages: {
                    kode_user: "Kode mahasiswa tidak boleh kosong.",
                    full_name: "Nama lengkap tidak boleh kosong.",
                    password: {
                        required: "Kata sandi tidak boleh kosong.",
                        minlength: jQuery.validator.format("Kata sandi setidaknya {0} karakter.")
                    },
                    confirm_password: {
                        required: "Kata sandi tidak boleh kosong.",
                        minlength: jQuery.validator.format("Kata sandi setidaknya {0} karakter."),
                        equalTo: "Kata sandi tidak sama."
                    }
                },
                submitHandler: function(form, event) {
                    // Serialize the form data
                    var formData = $(form).serialize();
                    event.preventDefault();
                    $.ajax({
                        method:"POST",
                        url: "services/auth/register-service.php",
                        data: formData,
                        success: function(response) {
                            if(response.success) {
                                toastr.success(response.message);
                                setTimeout(function() {
                                    window.location.href = "login.php";
                                }, 2000);
                                // window.location.href="login.php";
                            } else {
                                toastr.error(response.message);
                            }
                        }
                    });
                }
            });
        });
    </script>
    <!-- <script>
             $(document).ready(function() {
                $('#confirm_password').keyup(function () {
                    var value = $(this).val();
                    if (value && value == $("#password").val() && !this.validity.valid) {
                        $(this).removeClass(':invalid').addClass(':valid');
                    } else {
                        $(this).removeClass(':valid').addClass(':invalid');
                    }
                    //     $("#password").addClass('form-control:invalid');
                    //     $("#error_password").text("Kata sandi minimal 6 karakter.");
                });

                // Validate Email
                // const email = document.getElementById("email");
                // email.addEventListener("blur", () => {
                //     let regex =
                //     /^([_\-\.0-9a-zA-Z]+)@([_\-\.0-9a-zA-Z]+)\.([a-zA-Z]){2,7}$/;
                //     let s = email.value;
                //     if (regex.test(s)) {
                //         email.classList.remove("is-invalid");
                //         emailError = true;
                //     } else {
                //         email.classList.add("is-invalid");
                //         emailError = false;
                //     }
                // });
                
                $('#registrationForm').submit(function(e) {
                    e.preventDefault();
                    if ($(this).hasClass()) {
                        
                    }
                    // if ($('#password').val() == $('#confirm_password').val()) {
                    //     console.log('here');
                    // } else {
                    //     console.log('here 1');
                    // }
                    // $.ajax({
                    //     method:"POST",
                    //     url: "services/auth/register-service.php",
                    //     data:$(this).serialize(),
                    //     success: function(data) {
                    //         if(data == 'success') {
                    //             window.location.href="beranda.php";
                    //         } else {
                    //             $('#msg').html(data);
                    //             $('#registerForm').find('input').val('')
                    //         }
                        
                    //     }
                    // });
                });
             });
        </script> -->
</body>

</html>