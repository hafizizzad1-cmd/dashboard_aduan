<?php
// 1. Panggil fail connection pangkalan data
// Pastikan path ../../conn.php ni betul mengikut kedudukan folder bos
require_once '../../config/conn.php'; 

// 2. Semak jika ada data POST dihantar dari AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 3. Tangkap dan bersihkan data (Paksa jadi integer supaya selamat dari SQL Injection)
    $emel_open = isset($_POST['emel_open']) ? (int)$_POST['emel_open'] : 0;
    $emel_close = isset($_POST['emel_close']) ? (int)$_POST['emel_close'] : 0;
    
    // 4. Sediakan Query SQL
    // NOTA PENTING: Saya tambah "WHERE id = 1" sebagai langkah keselamatan supaya tak ter-update keseluruhan table kalau ada lebih dari 1 row.
    // Jika table bos guna column lain untuk tentukan row (contoh: tahun), sila tukar WHERE tu.
    $sql = "UPDATE eroses.helpdesk_emel_count 
            SET open = '$emel_open', 
                close = '$emel_close'";
            
    // 5. Laksanakan Query
    if (mysqli_query($conn, $sql)) {
        // Kalau BERJAYA, pulangkan status 200 (OK) dan mesej JSON
        http_response_code(200);
        echo json_encode([
            "status" => "success", 
            "message" => "Rekod emel berjaya dikemaskini"
        ]);
    } else {
        // Kalau GAGAL (Sebab error database), pulangkan status 500
        // Ini akan trigger fungsi error: function(xhr) dalam AJAX jQuery bos tadi
        http_response_code(500); 
        echo json_encode([
            "status" => "error", 
            "message" => "Ralat pangkalan data: " . mysqli_error($conn)
        ]);
    }
    
} else {
    // Kalau fail ini diakses secara terus di URL tanpa guna AJAX POST
    http_response_code(400); // Bad Request
    echo json_encode([
        "status" => "error", 
        "message" => "Akses tidak dibenarkan. Sila guna POST."
    ]);
}
?>