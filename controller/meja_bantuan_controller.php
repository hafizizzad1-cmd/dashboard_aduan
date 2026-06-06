<?php
include("../config/conn.php");
// ==============================================================================
// 1. KIRA STATISTIK PANTAS UNTUK WIDGET (TANPA LOAD SEMUA ROW)
// ==============================================================================
$sql_stats = "SELECT 
    COUNT(*) AS total_semua,
    SUM(CASE WHEN UPPER(status_technical) = 'ANSWERED' OR status = 3 THEN 1 ELSE 0 END) AS total_answered
FROM eroses_complaint.feedback
WHERE assign_IT = 1";

$result_stats = mysqli_query($conn, $sql_stats);
$row_stats = mysqli_fetch_assoc($result_stats);

$db_total_semua = (int)$row_stats['total_semua'];
$db_total_answered = (int)$row_stats['total_answered'];



?>

<?php
// ==============================================================================
// 3. QUERY STATISTIK ADUAN BELUM DIALIR (MENGIKUT NEGERI)
// ==============================================================================
$sql_negeri = "SELECT 
    n.name AS negeri,
    COALESCE(SUM(CASE WHEN f.status_technical IS NULL THEN 1 ELSE 0 END), 0) AS belum_teknikal,
    COALESCE(SUM(CASE WHEN f.status_technical = 'ANSWERED' THEN 1 ELSE 0 END), 0) AS answered
FROM
(
    SELECT 'JOHOR' AS name UNION ALL SELECT 'KEDAH' UNION ALL SELECT 'KELANTAN' UNION ALL SELECT 'MELAKA' UNION ALL SELECT 'NEGERI SEMBILAN' UNION ALL SELECT 'PAHANG' UNION ALL SELECT 'PULAU PINANG' UNION ALL SELECT 'PERAK' UNION ALL SELECT 'PERLIS' UNION ALL SELECT 'SELANGOR' UNION ALL SELECT 'TERENGGANU' UNION ALL SELECT 'SABAH' UNION ALL SELECT 'SARAWAK' UNION ALL SELECT 'WILAYAH PERSEKUTUAN KUALA LUMPUR' UNION ALL SELECT 'WILAYAH PERSEKUTUAN PUTRAJAYA'
) n
LEFT JOIN eroses_society.adm_addresses aa 
    ON (CASE WHEN aa.name = 'WILAYAH PERSEKUTUAN LABUAN' THEN 'SABAH' ELSE aa.name END) = n.name
LEFT JOIN eroses_complaint.feedback f
    ON aa.id = f.state_id AND f.status < 3 AND f.type != 'KEPUASAN_PELANGAN'
GROUP BY n.name
ORDER BY n.name";

$result_negeri = mysqli_query($conn, $sql_negeri);

$data_statistik_negeri = [];
$total_baru = 0;
$total_dijawab = 0;

if ($result_negeri && mysqli_num_rows($result_negeri) > 0) {
    while ($row_neg = mysqli_fetch_assoc($result_negeri)) {
        $data_statistik_negeri[] = $row_neg;
        $total_baru += (int)$row_neg['belum_teknikal'];
        $total_dijawab += (int)$row_neg['answered'];
    }
}
$grand_total_semua = $total_baru + $total_dijawab;

// 1. Tambah index 'total' untuk tujuan sorting
if (!empty($data_statistik_negeri)) {
    foreach ($data_statistik_negeri as $key => $stat) {
        $data_statistik_negeri[$key]['total'] = (int)$stat['belum_teknikal'] + (int)$stat['answered'];
    }

    // 2. Sort array ikut total menurun (DESC)
    usort($data_statistik_negeri, function($a, $b) {
        return $b['total'] <=> $a['total']; // Susun dari paling besar ke kecil
    });
}


// ==============================================================================
// 4. QUERY COUNT EMEL
// ==============================================================================

$sql_emel= "SELECT open,close
FROM eroses.helpdesk_emel_count
";

$result_emel = mysqli_query($conn, $sql_emel);
while ($row_emel = mysqli_fetch_assoc($result_emel)) {
	$data_emel_open = $row_emel['open'];
	$data_emel_close = $row_emel['close'];
}



?>