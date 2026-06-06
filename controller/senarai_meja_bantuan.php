<?php
include '../config/conn.php'; // Pastikan path connection betul

$sql = "SELECT 
f.id, f.type, f.title, f.details, f.name, f.email, f.assign_date, f.assign_IT, f.severity, f.status, f.status_technical
FROM eroses_complaint.feedback f
LEFT OUTER JOIN eroses_complaint.adm_branch ab ON f.current_assigned_branch_id = ab.id
WHERE f.assign_IT = 1";

$result = mysqli_query($conn, $sql);
$data = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        
        $status_tech = strtoupper($row['status_technical'] ?? '');
        
        // 1. Format Badge untuk Status Technical
        if ($status_tech == 'ANSWERED') {
            $badge_status = '<span class="badge bg-success rounded-pill px-3">ANSWERED</span>';
        } else if ($status_tech == 'ASSIGNED') {
            $badge_status = '<span class="badge bg-warning text-dark rounded-pill px-3">ASSIGNED</span>';
        } else {
            $badge_status = '<span class="badge bg-secondary rounded-pill px-3">'.($status_tech ? $status_tech : 'N/A').'</span>';
        }

        // 2. Format Details supaya tak panjang sangat (Truncate)
        $details_safe = htmlspecialchars($row['details'] ?? '');
        $details_html = '<span class="d-inline-block text-truncate" style="max-width: 150px;" title="'.$details_safe.'">'.$details_safe.'</span>';

        // 3. Format Butang Action
        $action_btn = '<button class="btn btn-sm btn-primary py-1 px-2"><i class="bi bi-pencil-square"></i></button>';

        // Masukkan semua yang dah siap format ke dalam Array mengikut column
        $data[] = [
            "id" => $row['id'],
            "type" => $row['type'],
            "title" => $row['title'],
            "details" => $details_html,
            "name" => $row['name'],
            "email" => $row['email'],
            "assign_date" => $row['assign_date'],
            "severity" => $row['severity'],
            "status_technical" => $badge_status,
            "action" => $action_btn
        ];
    }
}

// Pulangkan dalam format JSON yang dibaca oleh DataTables
echo json_encode(["data" => $data]);
?>