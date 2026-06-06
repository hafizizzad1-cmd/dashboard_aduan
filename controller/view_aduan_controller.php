<?php
// ==========================================================
// Fail: controller/get_aduan_detail.php
// Fungsi: Tarik rekod aduan & maklumat pertubuhan untuk paparan
// ==========================================================

// 1. Panggil fail sambungan pangkalan data bos
require_once '../config/conn.php'; 

// ==========================================================
// BAHAGIAN 1: TARIK DATA ADUAN
// ==========================================================
$aduan_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$complaint = []; 

if ($aduan_id > 0) {
    $sql_aduan = "SELECT 
                id, 
                title, 
                details, 
                severity, 
                status_technical, 
                assign_date, 
                PIC, 
                PIC_name, 
                note, 
                finish_date, 
                tech_feedback,
                created_date
            FROM eroses_complaint.feedback 
            WHERE id = ? 
            LIMIT 1";
            
    if ($stmt = $conn->prepare($sql_aduan)) { 
        $stmt->bind_param("i", $aduan_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $complaint = $result->fetch_assoc();
        } else {
            die("<div style='padding: 20px; font-family: sans-serif; color: red;'><b>Makluman:</b> Rekod aduan tidak dijumpai dalam sistem.</div>");
        }
        $stmt->close();
    } else {
        die("Ralat Pangkalan Data (Aduan): " . $conn->error);
    }
} else {
    die("<div style='padding: 20px; font-family: sans-serif; color: red;'><b>Makluman:</b> ID aduan tidak sah atau URL tidak lengkap.</div>");
}


// ==========================================================
// BAHAGIAN 2: TARIK DATA PERTUBUHAN (DINAMIK BERDASARKAN CARIAN)
// ==========================================================
$pertubuhan = [];

// Tangkap nombor pertubuhan dari carian (jika ada)
$search_society_no = isset($_GET['society_no']) ? trim($_GET['society_no']) : '';

if (!empty($search_society_no)) {
   $sql_pertubuhan = "SELECT 
        s.id,
        s.society_no,
        s.society_name,
        ac.category_name_bm AS nama_kategori,
        ac2.category_name_bm AS nama_subkategori, 
        s.society_level AS taraf_pertubuhan,
        s.status_code,
        s.sub_status_code,
        s.application_status_code,
        s.address,
        s.city,
        s.postcode,
        aa_district.name AS daerah_urusan,
        aa_state.name AS negeri_urusan,
        s.mailing_address,
        s.mailing_city,
        s.mailing_postcode,
        aa_mailing_district.name AS daerah_surat,
        aa_mailing_state.name AS negeri_surat
    FROM 
        eroses_society.society s
    LEFT JOIN 
        eroses_society.adm_addresses aa_district ON aa_district.id = s.district_id 
    LEFT JOIN 
        eroses_society.adm_addresses aa_state ON aa_state.id = s.state_id 
    LEFT JOIN 
        eroses_society.adm_addresses aa_mailing_district ON aa_mailing_district.id = s.mailing_district_code 
    LEFT JOIN 
        eroses_society.adm_addresses aa_mailing_state ON aa_mailing_state.id = s.mailing_state_code 
    LEFT JOIN 
        eroses_society.adm_category ac ON ac.id = s.category_code_jppm         
    LEFT JOIN 
        eroses_society.adm_category ac2 ON ac2.id = s.sub_category_code       
    WHERE 
        s.society_no = ? 
    LIMIT 1";

    if ($stmt_pertubuhan = $conn->prepare($sql_pertubuhan)) {
        // Guna "s" kerana society_no adalah varchar (huruf + nombor)
        $stmt_pertubuhan->bind_param("s", $search_society_no); 
        $stmt_pertubuhan->execute();
        $result_pertubuhan = $stmt_pertubuhan->get_result();
        
        if ($result_pertubuhan->num_rows > 0) {
            $pertubuhan = $result_pertubuhan->fetch_assoc();
        }
        $stmt_pertubuhan->close();
    } else {
        die("Ralat Pangkalan Data (Pertubuhan): " . $conn->error);
    }
}

// ==========================================================
// BAHAGIAN 3: TARIK DATA SENARAI PINDAAN
// ==========================================================
$senarai_pindaan = [];

// Pastikan maklumat pertubuhan wujud dan ada ID
if (!empty($pertubuhan['id'])) {
    
    // Guna ID dari carian pertubuhan tadi
    $society_id = $pertubuhan['id']; 
    
    // Susun dari tarikh terbaru ke terlama
    $sql_pindaan = "SELECT id, goal, payment_date, application_status_code 
                    FROM eroses_society.amendment_list 
                    WHERE society_id = ? 
                    ORDER BY payment_date DESC";
                    
    if ($stmt_pindaan = $conn->prepare($sql_pindaan)) {
        // Guna "s" (string) atau "i" (integer) bergantung pada jenis column DB
        $stmt_pindaan->bind_param("s", $society_id); 
        $stmt_pindaan->execute();
        $result_pindaan = $stmt_pindaan->get_result();
        
        while ($row = $result_pindaan->fetch_assoc()) {
            $senarai_pindaan[] = $row;
        }
        $stmt_pindaan->close();
    }
}


// ==========================================================
// BAHAGIAN 4: TARIK DATA PERLEMBAGAAN (DEFAULT STATUS 11)
// ==========================================================
$senarai_perlembagaan = [];

// A. Tangkap ID Pindaan dari URL jika pegawai ada klik mana-mana baris jadual
$amendment_id = isset($_GET['amendment_id']) ? trim($_GET['amendment_id']) : '';

// B. JIKA TIADA REKOD PINDAAN DIPILIH (First Load)
if (empty($amendment_id) && !empty($senarai_pindaan)) {
    
    // Loop senarai pindaan (Bahagian 3) untuk cari yang berstatus Aktif/Lulus (KOD 11)
    foreach ($senarai_pindaan as $pindaan) {
        if ((string)$pindaan['application_status_code'] === '11') {
            $amendment_id = $pindaan['id'];
            break; // Bila dah jumpa yang Lulus (paling latest), terus stop loop
        }
    }
    
    // C. Fallback: Kalau semua rekod pindaan pertubuhan tu takde yang status 11 
    // (Contoh: baru hantar 1 kali dan tengah pending), kita ambil je yang atas sekali
    if (empty($amendment_id)) {
        $amendment_id = $senarai_pindaan[0]['id'];
    }
}

// D. Jalankan query perlembagaan menggunakan ID yang telah dimuktamadkan di atas
if (!empty($amendment_id)) {
    $sql_perlembagaan = "SELECT clause_no, clause_name, description 
                         FROM eroses_society.constitution_content 
                         WHERE amendment_id = ? 
                         ORDER BY CAST(clause_no AS UNSIGNED)";

    if ($stmt_perlembagaan = $conn->prepare($sql_perlembagaan)) {
        $stmt_perlembagaan->bind_param("s", $amendment_id);
        $stmt_perlembagaan->execute();
        $result_perlembagaan = $stmt_perlembagaan->get_result();
        
        while ($row = $result_perlembagaan->fetch_assoc()) {
            $senarai_perlembagaan[] = $row;
        }
        $stmt_perlembagaan->close();
    }
}

?>