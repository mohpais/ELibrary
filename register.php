<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>E-Library | Register</title>
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
            <main>
                <div class="container">
                    <div class="row justify-content-center align-items-center min-vh-100">
                        <div class="col-lg-6">
                            <div class="card shadow-lg border-0 rounded-lg">
                                <div class="card-body">
                                    <div class="w-100 text-center mb-4">
                                        <div class="h3 fw-500 text-ubk">Bergabung dengan kami!</div>
                                        <div class="h6 fw-400 text-dark">Silahkan daftar untuk dapat mengakses website
                                            ...</div>
                                    </div>
                                    <form id="registrationForm" class="row g-3" method="POST" novalidate>
                                        <div class="col-12">
                                            <label for="kode_user" class="form-label mb-1">Kode Mahasiswa</label>
                                            <input type="text" class="form-control" id="kode_user" name="kode_user"
                                                placeholder="Masukkan kode mahasiswa ..." onkeypress="return onlyNumberKey(event)" />
                                        </div>
                                        <div class="col-12 mt-2">
                                            <label for="full_name" class="form-label mb-1">Nama Lengkap</label>
                                            <input type="text" class="form-control" id="full_name" name="full_name"
                                                placeholder="Masukkan nama lengkap ..." />
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <label for="password" class="form-label mb-1">Kata Sandi</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                placeholder="Masukkan kata sandi ..." />
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <label for="confirm_password" class="form-label mb-1">Ulangi Kata Sandi</label>
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                                placeholder="Masukkan kata sandi ..." />
                                        </div>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-ubk">Daftar</button>
                                        </div>
                                        <div class="d-grid gap-0 mt-2 mb-0">
                                            <p>Sudah punya akun? <a href="login.php">Masuk disini!</a></p>
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
                    // if ($('#password').val() === $('#confirm_password').val()) {
                    //     console.log('here');
                    // } else {
                    //     console.log('here 1');
                    // }
                    // $.ajax({
                    //     method:"POST",
                    //     url: "services/auth/register-service.php",
                    //     data:$(this).serialize(),
                    //     success: function(data) {
                    //         if(data === 'success') {
                    //             window.location.href="dashboard.php";
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