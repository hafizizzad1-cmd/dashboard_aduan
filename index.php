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
    'Hafiz' => [
        'password' => 'H@5929', 
        'name' => 'MUHAMMAD HAFIZ IZZAD BIN ABDUL RAZAK', 
        'role' => 'Helpdesk'
    ],
    'Iffa' => [
        'password' => 'I@5930', 
        'name' => 'IFFA NADHIRA BINTI MOHD HAZANOL', 
        'role' => 'Helpdesk'
    ],
    'Liyaana' => [
        'password' => 'L@5516', 
        'name' => 'Liyaana Shahirah Binti Wan Abd Aziz', 
        'role' => 'Helpdesk'
    ],
    'Fareisya' => [
        'password' => 'F@6334', 
        'name' => 'Fareisya Zulaikha Binti Mohd Sani', 
        'role' => 'Helpdesk'
    ],
    'Raja' => [
        'password' => 'R!5109', 
        'name' => 'Ketua Pengarah (Pengesah)', 
        'role' => 'Developer'
    ],
    'Amirul' => [
        'password' => 'A!1234', 
        'name' => 'Amirul Adli bin Zaimal', 
        'role' => 'Project Manager'
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
                
                header("location: src/meja_bantuan.php");
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
       body{

            background:
                linear-gradient(
                    135deg,
                    #eef2ff,
                    #f8fafc
                );
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

       .login-form-container{

            background:white;

            border-radius:32px;

            padding:90px 90px;

            box-shadow:
                0 20px 50px rgba(15,23,42,.08);
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

       .login-showcase{

            background:#071f63;

            overflow:hidden;

            position:relative;
        }

        .login-showcase::before{

            content:"";

            position:absolute;

            width:500px;
            height:500px;

            border-radius:50%;

            background:
                rgba(255,255,255,.08);

            top:-200px;
            right:-150px;
        }

        .quick-login{

            border-radius:999px;

            padding:6px 14px;

            font-weight:600;

            border:1px solid #e2e8f0;

            transition:.2s;
        }

        .quick-login:hover{

            transform:translateY(-2px);

            box-shadow:
                0 6px 15px rgba(37,99,235,.15);

            border-color:#2563eb;

            color:#2563eb;
        }


      .showcase-content{

            position:absolute;

            inset:0;

            display:flex;

            justify-content:center;

            align-items:center;

            z-index:10;

            padding:0;
        }

       .showcase-content{

    position:absolute;

    inset:0;

    display:flex;

    align-items:center;

    justify-content:center;

    padding:40px;

    z-index:10;
}

.showcase-glass{

    position:relative;

    width:92%;

    border-radius:32px;

    overflow:hidden;

    background:
        rgba(255,255,255,.08);

    backdrop-filter:
        blur(25px);

    border:
        1px solid rgba(255,255,255,.15);

    box-shadow:
        0 25px 60px rgba(0,0,0,.25),
        inset 0 1px 0 rgba(255,255,255,.15);
}

    .bubbles{

        position:absolute;

        inset:0;

        overflow:hidden;

        z-index:1;
    }

    .bubbles span{

        position:absolute;

        bottom:-100px;

        border-radius:50%;

        background:
            rgba(255,255,255,.08);

        animation:
            bubbleMove 25s linear infinite;
    }

    .bubbles span:nth-child(1){
        width:80px;
        height:80px;
        left:10%;
    }

    .bubbles span:nth-child(2){
        width:120px;
        height:120px;
        left:25%;
        animation-duration:30s;
    }

    .bubbles span:nth-child(3){
        width:60px;
        height:60px;
        left:50%;
        animation-duration:20s;
    }

    .bubbles span:nth-child(4){
        width:150px;
        height:150px;
        left:70%;
    }

    .bubbles span:nth-child(5){
        width:90px;
        height:90px;
        left:85%;
    }

    .bubbles span:nth-child(6){
        width:200px;
        height:200px;
        left:35%;
        animation-duration:35s;
    }

    @keyframes bubbleMove{

        from{

            transform:
                translateY(0)
                rotate(0deg);

            opacity:0;
        }

        20%{

            opacity:1;
        }

        80%{

            opacity:1;
        }

        to{

            transform:
                translateY(-120vh)
                rotate(360deg);

            opacity:0;
        }
    }

    .showcase-card{

        background:
            rgba(255,255,255,.05);

        backdrop-filter:
            blur(20px);

        border:
            1px solid rgba(255,255,255,.08);

        border-radius:32px;

        padding:20px;
    }

    .showcase-banner{

        width:100%;

        display:block;

         position:relative;

       overflow:hidden;

       opacity:.85;

    }
    .showcase-glass::before{

        content:"";

        position:absolute;

        top:0;

        left:0;

        width:100%;

        height:1px;

        background:
            linear-gradient(
                90deg,
                transparent,
                rgba(255,255,255,.7),
                transparent
            );

        z-index:20;
    }
    .showcase-glass::after{

    content:"";

    position:absolute;

    inset:-80px;

    background:
        radial-gradient(
            circle,
            rgba(37,99,235,.35),
            transparent 70%
        );

    z-index:-1;

    content:"";

    position:absolute;

    inset:0;

    background:
        linear-gradient(
            135deg,
            rgba(255,255,255,.08),
            rgba(255,255,255,.03)
        );

    pointer-events:none;
}
    </style>
</head>
<body>

    <div class="container-fluid p-0">
        <div class="row g-0 vh-100">
            
            <div class="col-lg-7 d-none d-lg-block login-showcase">

                <!-- Bubble Background -->
                <div class="bubbles">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>

                <!-- Logo -->
                <div class="position-absolute top-0 start-0 p-4"
                    style="z-index:20;">

                    <h5 class="text-white fw-bold">
                        <img
                            src="assets/images/rosie-icon.png"
                            style="height:40px;"
                            class="me-2">

                        MEJA BANTUAN PLUS+
                    </h5>

                </div>
               <div class="showcase-content">

                    <div class="showcase-glass">

                        <img
                            src="assets/images/login-banner.png"
                            class="showcase-banner">

                    </div>

                </div>

            </div>


           <div class="col-lg-5 d-flex flex-column align-items-center justify-content-center position-relative">
                
                <div class="login-form-container ">
                    
                    <div class="text-center mb-5">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-inline-flex align-items-center justify-content-center mb-4 shadow-sm" style="width: 70px; height: 70px;">
                            <img
                                src="assets/images/rosie-icon.png"
                                style="
                                    width:70px;
                                    height:70px;">
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
                            <label class="form-label fw-bold text-dark small">Username:</label>
                            <div class="input-group input-group-lg <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>">
                                <span class="input-group-text"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" id = 'inputUsername' name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" placeholder="Cth: 960604105929" autocomplete="off">
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
                        <div class="mt-4">

                            <div class="small text-muted fw-bold mb-2">
                                Quick Login
                            </div>

                            <div class="d-flex flex-wrap gap-2">

                                <button
                                    type="button"
                                    class="btn btn-light btn-sm quick-login"
                                    data-user="Hafiz"
                                    >

                                    Hafiz

                                </button>

                                <button
                                    type="button"
                                    class="btn btn-light btn-sm quick-login"
                                    data-user="Iffa"
                                    >

                                    Iffa

                                </button>

                                <button
                                    type="button"
                                    class="btn btn-light btn-sm quick-login"
                                    data-user="Fareisya"
                                   >

                                    Fareisya

                                </button>

                                <button
                                    type="button"
                                    class="btn btn-light btn-sm quick-login"
                                    data-user="Liyaana"
                                   >

                                    Liyaana

                                </button>

                                <button
                                    type="button"
                                    class="btn btn-light btn-sm quick-login"
                                    data-user="Raja"
                                   >

                                    Raja

                                </button>

                                <button
                                    type="button"
                                    class="btn btn-light btn-sm quick-login"
                                    data-user="Amirul"
                                   >

                                    Amirul

                                </button>

                            </div>

                        </div>
                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold shadow">
                                Log Masuk Sistem
                            </button>
                        </div>
                        
                    </form>

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
        document
        .querySelectorAll('.quick-login')
        .forEach(button => {

            button.addEventListener(
                'click',
                function(){

                    document
                    .getElementById('inputUsername')
                    .value =
                        this.dataset.user;

                   
                }
            );

        });
    </script>
</body>
</html>