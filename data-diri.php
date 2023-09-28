<?php
    // Include the functions.php file from the main theme directory
    require_once 'helpers/authorize.php';
    require_once 'helpers/functions.php';
    require_once 'config/connection.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>E-Library | Data Diri</title>
        <link href="assets/css/styles.css" rel="stylesheet" />
        <link href="assets/css/custom.css" rel="stylesheet" />
        <!-- Toast Library -->
        <link href="assets/lib/toast/toast.min.css" rel="stylesheet" />
        <!-- Bootstrap Library -->
        <link href="assets/lib/bootstrap/bootstrap-datepicker.min.css" rel="stylesheet" />
    </head>
    <body class="sb-nav-fixed">
        <!-- Navbar -->
        <?php include 'includes/navbar.php' ?>
        <!-- End Navbar -->
        <div id="layoutSidenav">
            <!-- Sidebar -->
            <?php include 'includes/sidebar.php' ?>
            <!-- End Sidebar -->
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Data Diri</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item">Panel</li>
                            <li class="breadcrumb-item active">Data Diri</li>
                        </ol>
                        <div class="row flex-column gap-1">
                            <?php 
                                if (
                                    !isset($_SESSION['user']['jurusan']) && 
                                    !isset($_SESSION['user']['semester']) && 
                                    !isset($_SESSION['user']['tanggal_bergabung'])) 
                                {
                            ?>
                                <div class="col-12">
                                    <div class="alert bg-danger text-white">Lengkapi data diri sebelum dapat menggunakan fitur didalam website ini!</div>
                                </div>
                            <?php } ?>
                            <div class="col-12">
                                <div class="card card-body shadow-sm">
                                    <h4 class="card-title mb-3">Data Diri</h4>
                                    <form id="formProfile" novalidate>
                                        <div class="row mb-3">
                                            <label for="kode_user" class="col-md-3 col-lg-2 col-form-label">Kode User <span class="text-danger fw-bold">*</span></label>
                                            <div class="col-md-9 col-lg-10">
                                                <input 
                                                    id="kode_user" 
                                                    name="kode_user" 
                                                    type="text" 
                                                    class="form-control" 
                                                    value="<?php echo $_SESSION['user']['kode'] ?>" 
                                                    placeholder="Masukkan kode user ..."
                                                    disabled
                                                />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="full_name" class="col-md-3 col-lg-2 col-form-label">Nama Lengkap <span class="text-danger fw-bold">*</span></label>
                                            <div class="col-md-9 col-lg-10">
                                                <input 
                                                    id="full_name" 
                                                    name="full_name" 
                                                    type="text" 
                                                    class="form-control" 
                                                    placeholder="Masukkan nama lengkap ..."
                                                    value="<?php echo $_SESSION['user']['nama_lengkap'] ?>"
                                                />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="no_telp" class="col-md-3 col-lg-2 col-form-label">No. Telp</label>
                                            <div class="col-md-9 col-lg-10">
                                                <input 
                                                    id="no_telp" 
                                                    name="no_telp" 
                                                    type="text" 
                                                    class="form-control" 
                                                    placeholder="Masukkan no telpon ..."
                                                    maxlength="13"
                                                    onkeypress="return onlyNumberKey(event)"
                                                    value="<?php echo $_SESSION['user']['no_telp'] ?>"
                                                />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="email" class="col-md-3 col-lg-2 col-form-label">Email</label>
                                            <div class="col-md-9 col-lg-10">
                                                <input 
                                                    id="email" 
                                                    name="email" 
                                                    type="email" 
                                                    class="form-control" 
                                                    placeholder="Masukkan email ..."
                                                    value="<?php echo $_SESSION['user']['email'] ?>"
                                                />
                                            </div>
                                        </div>
                                        <?php if ($_SESSION['user']['role'] === 'Mahasiswa') { ?>
                                            <div class="row mb-3">
                                                <label for="jurusan" class="col-md-3 col-lg-2 col-form-label">Jurusan <span class="text-danger fw-bold">*</span></label>
                                                <div class="col-md-9 col-lg-10">
                                                    <select name="jurusan" id="jurusan" class="form-control">
                                                        <option value="" disabled selected>-- Pilih Salah Satu --</option>
                                                        <option value="Sistem Informasi" <?php echo $_SESSION['user']['jurusan'] == 'Sistem Informasi' ? 'selected' : '' ?>>Sistem Informasi</option>
                                                        <option value="Sistem Komputer" <?php echo $_SESSION['user']['jurusan'] == 'Sistem Komputer' ? 'selected' : '' ?>>Sistem Komputer</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="tanggal_bergabung" class="col-md-3 col-lg-2 col-form-label">Tanggal Masuk <span class="text-danger fw-bold">*</span></label>
                                                <div class="col-md-9 col-lg-10">
                                                    <input type="text" class="form-control datepicker" id="tanggal_bergabung" name="tanggal_bergabung"  
                                                    placeholder="Masukkan tanggal masuk ..." value="<?php echo $_SESSION['user']['tanggal_bergabung'] ?>" />
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="semester" class="col-md-3 col-lg-2 col-form-label">Semester <span class="text-danger fw-bold">*</span></label>
                                                <div class="col-md-9 col-lg-10">
                                                    <input 
                                                        id="semester" 
                                                        name="semester" 
                                                        type="text" 
                                                        class="form-control" 
                                                        value="<?php echo $_SESSION['user']['semester'] ?>"
                                                        placeholder="Semester akan terisi secara otomatis ketika mengisi tanggal masuk ..."
                                                        disabled
                                                    />
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="row mt-4">
                                            <div class="col">
                                                <p class="fz-10 text-muted">(<span class="text-danger"><b>*</b></span>) <b>Mandatori</b> wajib diisi.</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-auto mx-auto">
                                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <!-- Navbar -->
                <?php include 'includes/footer.php' ?>
                <!-- End Navbar -->
            </div>
        </div>
        <script src="assets/js/scripts.js"></script>
        <!-- JQuery Library -->
        <script src="assets/lib/jquery/jquery.min.js"></script>
        <script src="assets/lib/jquery/jquery.validate.min.js"></script>
        <script src="assets/lib/jquery/additional-methods.min.js"></script>
        <!-- Bootstrap Library -->
        <script src="assets/lib/bootstrap/bootstrap.bundle.min.js"></script>
        <script src="assets/lib/bootstrap/bootstrap-datepicker.min.js"></script>
        <!-- Font Awesome Library -->
        <script src="assets/lib/font-awesome/all.js"></script>
        <!-- Toast Library -->
        <script src="assets/lib/toast/toast.min.js"></script>
        <script>
            $(document).ready(function () {
                $(document).off('.datepicker.data-api');
                var role = '<?php echo $_SESSION['user']['role'] ?>';

                function calculateSemester(joinDate) {
                    // Parse the joinDate string into a Date object
                    const joinDateObj = new Date(joinDate);

                    // Calculate the current date
                    const currentDate = new Date();

                    // Calculate the difference in months between the current date and join date
                    const monthsDiff = (currentDate.getFullYear() - joinDateObj.getFullYear()) * 12 +
                        (currentDate.getMonth() - joinDateObj.getMonth());

                    // Calculate the semester based on the difference in months
                    const semester = Math.ceil(monthsDiff / 6);
                    return semester === 0 ? 1 : semester > 8 ? 8 : semester;
                }

                $('.datepicker').datepicker({
                    format: 'yyyy/mm/dd',
                    todayHighlight: true,
                    clearBtn: true
                });

                $('.datepicker').change(function (e) {
                    var smstr = calculateSemester(e.target.value);
                    $('#semester').val(smstr);
                });

                $("#formProfile").validate({
                    rules: {
                        kode_user: "required",
                        nama_lengkap: "required",
                        no_telp: {
                            minlength: 12
                        },
                        email: "email",
                        jurusan: {
                            required: role === 'Mahasiswa' ? true : false
                        },
                        tanggal_bergabung: {
                            required: role === 'Mahasiswa' ? true : false,
                            date: true
                        },
                        semester: {
                            required: role === 'Mahasiswa' ? true : false
                        }
                    },
                    messages: {
                        kode_user: "Kode user tidak boleh kosong.",
                        nama_lengkap: "Nama lengkap tidak boleh kosong.",
                        no_telp: {
                            minlength: jQuery.validator.format("Masukkan setidaknya {0} karakter.")
                        },
                        email: "Masukkan email yang valid.",
                        jurusan: "Jurusan tidak boleh kosong.",
                        tanggal_bergabung: {
                            required: "Tanggal masuk tidak boleh kosong.",
                            date: "Invalid date format (YYYY-MM-DD)"
                        },
                        semester: "Semester tidak boleh kosong."
                    },
                    submitHandler: function(form, event) {
                        event.preventDefault();
                        // Serialize the form data into an array of objects
                        var formDataArray = $(form).serializeArray();

                        // Convert the array into a JavaScript object
                        var formDataObject = {};
                        $.each(formDataArray, function(index, field){
                            formDataObject[field.name] = field.value;
                        });
                        formDataObject.kode_user = $('#kode_user').val();
                        formDataObject.semester = $('#semester').val();
                        $.ajax({
                            method:"POST",
                            url: "services/user/update-profile-service.php",
                            data: formDataObject,
                            success: function(response) {
                                // console.log(response);
                                if(response.success) {
                                    toastr.success(response.message);
                                    // Reload the page after 3 seconds
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 2000);
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
