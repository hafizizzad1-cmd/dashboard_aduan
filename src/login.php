<?php
// Mulakan sesi (Session) untuk simpan data login
session_start();

// Kalau pengguna dah login, terus tendang masuk ke sistem utama (index.php)
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("location: index.php");
    exit;
}

// ==========================================================
// DATA PENGGUNA HARDCODE (Ganti Database)
// ==========================================================
$senarai_pengguna = [
    '960604105929' => [
        'password' => 'P@ss9606', 
        'name' => 'MUHAMMAD HAFIZ IZZAD BIN ABDUL RAZAK', 
        'role' => 'admin'
    ],
    '960909085930' => [
        'password' => 'User#9609', 
        'name' => 'IFFA NADHIRA BINTI MOHD HAZANOL', 
        'role' => 'pegawai'
    ],
    '970815145516' => [
        'password' => 'Sistem2026', 
        'name' => 'Liyaana Shahirah Binti Wan Abd Aziz', 
        'role' => 'pegawai'
    ],
    '990622146334' => [
        'password' => 'Bantuan!99', 
        'name' => 'Fareisya Zulaikha Binti Mohd Sani', 
        'role' => 'pegawai'
    ],
    '950220101111' => [
        'password' => 'Bos!9502', 
        'name' => 'Ketua Pengarah (Pengesah)', 
        'role' => 'pengesah'
    ]
];

// Tetapkan pembolehubah kosong untuk tangkap ralat
$username = $password = "";
$username_err = $password_err = $login_err = "";

// Proses data bila borang (form) dihantar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    if (empty(trim($_POST["username"]))) {
        $username_err = "Sila masukkan ID Pengguna / No. K/P.";
    } else {
        $username = trim($_POST["username"]);
    }
    
    if (empty(trim($_POST["password"]))) {
        $password_err = "Sila masukkan kata laluan.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    if (empty($username_err) && empty($password_err)) {
        if (array_key_exists($username, $senarai_pengguna)) {
            if ($password === $senarai_pengguna[$username]['password']) {
                session_regenerate_id(); 
                
                $_SESSION["loggedin"] = true;
                $_SESSION["username"] = $username;
                $_SESSION["full_name"] = $senarai_pengguna[$username]['name'];
                $_SESSION["role"] = $senarai_pengguna[$username]['role'];                            
                
                header("location: index.php");
                exit;
            } else {
                $login_err = "Kata Laluan tidak sah.";
            }
        } else {
            $login_err = "ID Pengguna tidak wujud dalam sistem.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Masuk | Meja Bantuan Plus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body {
            overflow-x: hidden; /* Elak scroll melintang */
        }
        
        /* Gambar akan tutup seluruh ruang kiri dengan cantik */
        .login-bg-img {
            object-fit: cover;
            width: 100%;
            height: 100vh;
            filter: brightness(0.85); /* Gelapkan sikit gambar supaya nampak eksklusif */
        }

        /* Overlay text pada gambar (opsional) */
        .carousel-caption {
            background: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            padding: 20px;
            backdrop-filter: blur(5px);
            bottom: 3rem;
        }

        /* Ruangan Form Login */
        .login-form-container {
            max-width: 420px;
            width: 100%;
            margin: 0 auto;
        }

        .input-group-text {
            background-color: #f8fafc;
            border-right: none;
        }
        .form-control {
            background-color: #f8fafc;
            border-left: none;
        }
        .form-control:focus {
            background-color: #ffffff;
            box-shadow: none;
            border-color: var(--bs-primary);
        }
        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control,
        .input-group:focus-within .btn-outline-secondary {
            background-color: #ffffff;
            border-color: var(--bs-primary);
        }
    </style>
</head>
<body>

    <div class="container-fluid p-0">
        <div class="row g-0 vh-100">
            
            <div class="col-lg-7 d-none d-lg-block position-relative">
                
                <div id="loginCarousel" class="carousel slide carousel-fade h-100" data-bs-ride="carousel" data-bs-pause="false">
                    <div class="carousel-inner h-100">
                        
                        <div class="carousel-item active h-100" data-bs-interval="5000">
                            <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=1600" class="login-bg-img" alt="Bangunan">
                            <div class="carousel-caption d-none d-md-block">
                                <h4 class="fw-bold text-white">Sistem Pendaftaran Pertubuhan</h4>
                                <p class="text-white-50 mb-0">Memudahkan pengurusan rekod dan aduan secara bersepadu.</p>
                            </div>
                        </div>

                        <div class="carousel-item h-100" data-bs-interval="5000">
                            <img src="https://images.unsplash.com/photo-1577415124269-fc1140a69e91?auto=format&fit=crop&q=80&w=1600" class="login-bg-img" alt="Mesyuarat">
                            <div class="carousel-caption d-none d-md-block">
                                <h4 class="fw-bold text-white">Efisien & Dinamik</h4>
                                <p class="text-white-50 mb-0">Platform digital untuk kemudahan pegawai meja bantuan.</p>
                            </div>
                        </div>

                        <div class="carousel-item h-100" data-bs-interval="5000">
                            <img src="https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&q=80&w=1600" class="login-bg-img" alt="Kerja Berpasukan">
                            <div class="carousel-caption d-none d-md-block">
                           
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <div class="position-absolute top-0 start-0 p-4" style="z-index: 10;">
                    <h5 class="text-white fw-bold shadow-sm" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                        <i class="bi bi-shield-check me-2"></i>eROSES
                    </h5>
                </div>

            </div>


            <div class="col-lg-5 d-flex flex-column align-items-center justify-content-center bg-white position-relative shadow-lg" style="z-index: 5;">
                
                <div class="login-form-container px-4 px-md-0">
                    
                    <div class="text-center mb-5">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-inline-flex align-items-center justify-content-center mb-4 shadow-sm" style="width: 70px; height: 70px;">
                            <i class="bi bi-box-seam-fill fs-1"></i>
                        </div>
                        <h3 class="fw-bold text-dark mb-1">Meja Bantuan Plus+</h3>
                        <p class="text-muted">Log masuk untuk meneruskan sesi anda.</p>
                    </div>

                    <?php 
                    if(!empty($login_err)){
                        echo '<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                                <div>' . $login_err . '</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                              </div>';
                    }        
                    ?>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small">No. Kad Pengenalan</label>
                            <div class="input-group input-group-lg <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>">
                                <span class="input-group-text"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" placeholder="Cth: 960604105929" autocomplete="off">
                            </div>
                            <span class="text-danger small mt-1 d-block"><?php echo $username_err; ?></span>
                        </div>    

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label fw-bold text-dark small mb-0">Kata Laluan</label>
                                <a href="#" class="text-decoration-none small fw-medium">Lupa laluan?</a>
                            </div>
                            <div class="input-group input-group-lg mt-2 <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                                <span class="input-group-text"><i class="bi bi-key text-muted"></i></span>
                                <input type="password" name="password" id="inputPassword" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" placeholder="Masukkan kata laluan">
                                <button class="btn btn-outline-secondary border border-start-0" type="button" id="btnTogglePass">
                                    <i class="bi bi-eye-slash text-muted" id="iconTogglePass"></i>
                                </button>
                            </div>
                            <span class="text-danger small mt-1 d-block"><?php echo $password_err; ?></span>
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="rememberMe">
                            <label class="form-check-label text-muted small" for="rememberMe">Biarkan saya sentiasa log masuk</label>
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold shadow">
                                Log Masuk Sistem
                            </button>
                        </div>
                        
                    </form>

                </div>

                <div class="position-absolute bottom-0 text-center w-100 pb-3 pt-3 bg-white">
                    <small class="text-muted" style="font-size: 0.75rem;">
                        &copy; Object Expression Sdn. Bhd.<br>Paparan terbaik menggunakan Chrome / Edge terkini.
                    </small>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Logik mata tunjuk password
        const btnTogglePass = document.getElementById('btnTogglePass');
        const inputPassword = document.getElementById('inputPassword');
        const iconTogglePass = document.getElementById('iconTogglePass');

        btnTogglePass.addEventListener('click', function() {
            if (inputPassword.type === 'password') {
                inputPassword.type = 'text';
                iconTogglePass.classList.remove('bi-eye-slash');
                iconTogglePass.classList.add('bi-eye');
            } else {
                inputPassword.type = 'password';
                iconTogglePass.classList.remove('bi-eye');
                iconTogglePass.classList.add('bi-eye-slash');
            }
        });
    </script>
</body>
</html>