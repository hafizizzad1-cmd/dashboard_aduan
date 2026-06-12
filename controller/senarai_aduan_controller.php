<?php
// ==========================================================
// CONTROLLER: SENARAI ADUAN
// Lokasi cadangan: controller/senarai_aduan_controller.php
// ==========================================================

require_once '../config/conn.php';

if (!isset($conn) || !($conn instanceof mysqli)) {
    throw new RuntimeException('Sambungan database $conn tidak dijumpai. Semak fail config/conn.php.');
}

$allowed_tabs = ['pending', 'all'];
$active_tab   = $_GET['tab'] ?? 'pending';
$active_tab   = in_array($active_tab, $allowed_tabs, true) ? $active_tab : 'pending';

$keyword      = trim($_GET['q'] ?? '');
$current_page = max(1, (int)($_GET['page'] ?? 1));
$per_page     = 20;
$offset       = ($current_page - 1) * $per_page;

/**
 * Bind parameter secara dinamik.
 */
function bindDynamicParams(mysqli_stmt $stmt, string $types, array &$params): void
{
    if ($types !== '') {
        $stmt->bind_param($types, ...$params);
    }
}

// ==========================================================
// RINGKASAN JUMLAH REKOD UNTUK LENCANA TAB
// ==========================================================
$summary_sql = "
    SELECT
        COUNT(*) AS total_all,
        SUM(CASE WHEN status_technical = 'ASSIGNED' THEN 1 ELSE 0 END) AS total_pending
    FROM eroses_complaint.feedback
";

$summary_result = $conn->query($summary_sql);
$summary        = $summary_result ? $summary_result->fetch_assoc() : [];
$total_all      = (int)($summary['total_all'] ?? 0);
$total_pending  = (int)($summary['total_pending'] ?? 0);

// ==========================================================
// BINA FILTER BERDASARKAN TAB + CARIAN
// ==========================================================
$where_parts = [];
$params      = [];
$types       = '';

if ($active_tab === 'pending') {
    $where_parts[] = "status_technical = 'ASSIGNED'";
}

if ($keyword !== '') {
    $search_value = '%' . $keyword . '%';

    $where_parts[] = "(
        CAST(id AS CHAR) LIKE ?
        OR type LIKE ?
        OR title LIKE ?
        OR details LIKE ?
        OR society_no LIKE ?
        OR name LIKE ?
        OR PIC_name LIKE ?
    )";

    for ($i = 0; $i < 7; $i++) {
        $params[] = $search_value;
        $types   .= 's';
    }
}

$where_sql = $where_parts ? 'WHERE ' . implode(' AND ', $where_parts) : '';

// ==========================================================
// KIRA JUMLAH REKOD SELEPAS FILTER
// ==========================================================
$count_sql = "
    SELECT COUNT(*) AS total_filtered
    FROM eroses_complaint.feedback
    {$where_sql}
";

$count_stmt = $conn->prepare($count_sql);
if (!$count_stmt) {
    throw new RuntimeException('Gagal menyediakan query kiraan: ' . $conn->error);
}

$count_params = $params;
bindDynamicParams($count_stmt, $types, $count_params);
$count_stmt->execute();
$count_result   = $count_stmt->get_result()->fetch_assoc();
$total_filtered = (int)($count_result['total_filtered'] ?? 0);
$count_stmt->close();

$total_pages = max(1, (int)ceil($total_filtered / $per_page));
if ($current_page > $total_pages) {
    $current_page = $total_pages;
    $offset       = ($current_page - 1) * $per_page;
}

// ==========================================================
// TARIK REKOD UNTUK JADUAL
// ==========================================================
$order_sql = $active_tab === 'pending'
    ? 'ORDER BY assign_date DESC, created_date DESC'
    : 'ORDER BY created_date DESC';

$list_sql = "
    SELECT
        id,
        type,
        title,
        details,
        society_no,
        name,
        severity,
        assign_date,
        finish_date,
        PIC,
        PIC_name,
        status_technical,
        created_date
    FROM eroses_complaint.feedback
    {$where_sql}
    {$order_sql}
    LIMIT ? OFFSET ?
";

$list_stmt = $conn->prepare($list_sql);
if (!$list_stmt) {
    throw new RuntimeException('Gagal menyediakan query senarai: ' . $conn->error);
}

$list_params   = $params;
$list_params[] = $per_page;
$list_params[] = $offset;
$list_types    = $types . 'ii';

bindDynamicParams($list_stmt, $list_types, $list_params);
$list_stmt->execute();
$list_result = $list_stmt->get_result();

$complaints = [];
while ($row = $list_result->fetch_assoc()) {
    $complaints[] = $row;
}

$list_stmt->close();
