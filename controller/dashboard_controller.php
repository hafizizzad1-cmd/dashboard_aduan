<?php
include '../config/conn.php'; 

$sql = "SELECT 
    DATE(ft.created_date) AS tarikh,

    SUM(CASE WHEN ft.created_by = 423618 THEN 1 ELSE 0 END) AS Yaana,
    SUM(CASE WHEN ft.created_by = 423627 THEN 1 ELSE 0 END) AS Fareisya,
    SUM(CASE WHEN ft.created_by = 356748 THEN 1 ELSE 0 END) AS Hafiz,
    SUM(CASE WHEN ft.created_by = 356745 THEN 1 ELSE 0 END) AS Laili,
    SUM(CASE WHEN ft.created_by = 406884 THEN 1 ELSE 0 END) AS Syida,
    SUM(CASE WHEN ft.created_by = 356891 THEN 1 ELSE 0 END) AS Farah,
    SUM(CASE WHEN ft.created_by = 356639 THEN 1 ELSE 0 END) AS Affi,
    SUM(CASE WHEN ft.created_by = 360167 THEN 1 ELSE 0 END) AS Iffa,
    SUM(CASE WHEN ft.created_by = 424781 THEN 1 ELSE 0 END) AS Hani,
    SUM(CASE WHEN ft.created_by = 356700 THEN 1 ELSE 0 END) AS Fiza,
    SUM(CASE WHEN ft.created_by = 358176 THEN 1 ELSE 0 END) AS `Fareisya 2`

FROM eroses_complaint.feedback_trail ft
LEFT OUTER JOIN eroses_user.users u 
    ON ft.created_by = u.id

WHERE 
    ft.status_technical = 'ANSWERED'
    AND DATE(ft.created_date) >= CURDATE() - INTERVAL 50 DAY

GROUP BY DATE(ft.created_date)
ORDER BY DATE(ft.created_date) DESC";

$result = mysqli_query($conn, $sql); 

// ==============================================================================
// 2. FUNGSI BANTUAN TARIKH BAHASA MELAYU
// ==============================================================================
function dapatkanHariMelayu($tarikh) {
    $hari_inggeris = date('l', strtotime($tarikh));
    $hari_melayu = ['Sunday'=>'Ahad', 'Monday'=>'Isnin', 'Tuesday'=>'Selasa', 'Wednesday'=>'Rabu', 'Thursday'=>'Khamis', 'Friday'=>'Jumaat', 'Saturday'=>'Sabtu'];
    return $hari_melayu[$hari_inggeris];
}

function dapatkanBulanTahunMelayu($tarikh) {
    $bulan_angka = date('m', strtotime($tarikh));
    $tahun = date('Y', strtotime($tarikh));
    $bulan_melayu = ['01'=>'Januari', '02'=>'Februari', '03'=>'Mac', '04'=>'April', '05'=>'Mei', '06'=>'Jun', '07'=>'Julai', '08'=>'Ogos', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Disember'];
    return strtoupper($bulan_melayu[$bulan_angka] . ' ' . $tahun);
}

function formatTarikhPendek($tarikh) {
    $bulan_angka = date('m', strtotime($tarikh));
    $hari = date('d', strtotime($tarikh));
    $bulan_melayu = ['01'=>'Jan', '02'=>'Feb', '03'=>'Mac', '04'=>'Apr', '05'=>'Mei', '06'=>'Jun', '07'=>'Jul', '08'=>'Ogo', '09'=>'Sep', '10'=>'Okt', '11'=>'Nov', '12'=>'Dis'];
    return $hari . ' ' . $bulan_melayu[$bulan_angka];
}

// ==============================================================================
// 3. PENGUMPULAN DATA UNTUK JUMLAH KESELURUHAN & KAD HARIAN
// ==============================================================================
$senarai_staf = ['Yaana', 'Fareisya', 'Hafiz', 'Laili', 'Syida', 'Farah', 'Affi', 'Iffa', 'Hani', 'Fiza', 'Fareisya 2'];
$total_terkumpul = array_fill_keys($senarai_staf, 0); // Sediakan array dengan nilai 0
$data_harian = []; 

if($result && mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $data_harian[] = $row; // Simpan row untuk dipaparkan kat bawah nanti
        
        // Tambah score ke dalam total keseluruhan
        foreach ($senarai_staf as $staf) {
            $total_terkumpul[$staf] += (int)$row[$staf];
        }
    }
}
// Susun total dari markah tertinggi ke terendah
arsort($total_terkumpul);
?>