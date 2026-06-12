<?php
// ==========================================================
// VIEW: SENARAI ADUAN
// Lokasi cadangan: view/senarai_aduan.php
// ==========================================================

require_once '../controller/senarai_aduan_controller.php';

$page_active = 'pengurusan';
include 'includes/header.php';

function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function formatDateTime(?string $value): string
{
    if (empty($value) || $value === '0000-00-00 00:00:00') {
        return '-';
    }

    return date('d/m/Y, h:i A', strtotime($value));
}

function truncateText(?string $value, int $length = 90): string
{
    $value = trim((string)$value);

    if ($value === '') {
        return '-';
    }

    return mb_strlen($value) > $length
        ? mb_substr($value, 0, $length) . '...'
        : $value;
}

function getSeverityBadge(?string $severity): string
{
    $severity = strtoupper(trim((string)$severity));

    switch ($severity) {
        case 'HIGH':
        case 'SEVERE':
            return '<span class="badge bg-danger">' . e($severity) . '</span>';
        case 'MEDIUM':
            return '<span class="badge bg-warning text-dark">MEDIUM</span>';
        case 'LOW':
            return '<span class="badge bg-success">LOW</span>';
        default:
            return '<span class="badge bg-secondary">-</span>';
    }
}

function getTechnicalStatusBadge(?string $status): string
{
    $status = strtoupper(trim((string)$status));

    switch ($status) {
        case 'ASSIGNED':
            return '<span class="badge rounded-pill badge-soft-warning">Dalam Tindakan</span>';
        case 'ANSWERED':
            return '<span class="badge rounded-pill badge-soft-success">Telah Dijawab</span>';
        default:
            return '<span class="badge rounded-pill bg-light text-secondary border">Belum Dialir</span>';
    }
}

function getTypeLabel(?string $type): string
{
    $type = strtoupper(trim((string)$type));

    switch ($type) {
        case 'ISU_SISTEM':
            return 'Isu Sistem';
        case 'MAKLUM_BALAS':
            return 'Maklum Balas';
        default:
            return $type !== '' ? ucwords(strtolower(str_replace('_', ' ', $type))) : '-';
    }
}

function buildPageUrl(int $page): string
{
    global $active_tab, $keyword;

    return 'senarai_aduan.php?' . http_build_query([
        'tab'  => $active_tab,
        'q'    => $keyword,
        'page' => $page,
    ]);
}
?>

<style>
    .complaint-list-page {
        min-height: calc(100vh - 150px);
        background: #f8fafc;
    }

    .page-heading-card {
        border: 0;
        border-radius: 18px;
        background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
        box-shadow: 0 10px 30px rgba(37, 99, 235, 0.06);
    }

    .complaint-tab-wrapper {
        display: inline-flex;
        gap: 6px;
        padding: 6px;
        border-radius: 14px;
        background: #eef2ff;
    }

    .complaint-tab {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 10px;
        color: #64748b;
        font-size: 0.9rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .complaint-tab:hover {
        color: #2563eb;
    }

    .complaint-tab.active {
        color: #1d4ed8;
        background: #ffffff;
        box-shadow: 0 5px 16px rgba(37, 99, 235, 0.12);
    }

    .complaint-tab .count-pill {
        min-width: 28px;
        padding: 2px 8px;
        border-radius: 999px;
        background: #e2e8f0;
        color: #475569;
        font-size: 0.72rem;
        text-align: center;
    }

    .complaint-tab.active .count-pill {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .list-card {
        border: 0;
        border-radius: 18px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
    }

    .complaint-table thead th {
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #64748b;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .complaint-table tbody td {
        border-color: #eef2f7;
        color: #334155;
        font-size: 0.84rem;
        vertical-align: middle;
    }

    .complaint-table tbody tr {
        transition: background-color 0.2s ease;
    }

    .complaint-table tbody tr:hover {
        background-color: #f8fbff;
    }

    .complaint-title {
        color: #0f172a;
        font-weight: 700;
    }

    .search-box {
        max-width: 460px;
    }

    .search-box .form-control,
    .search-box .input-group-text {
        border-color: #e2e8f0;
        background: #ffffff;
    }

    .badge-soft-warning {
        border: 1px solid #fde68a;
        background: #fef3c7;
        color: #92400e;
    }

    .badge-soft-success {
        border: 1px solid #bbf7d0;
        background: #dcfce7;
        color: #166534;
    }

    .empty-state {
        padding: 72px 20px;
        color: #94a3b8;
        text-align: center;
    }

    .empty-state i {
        display: block;
        margin-bottom: 14px;
        color: #cbd5e1;
        font-size: 3rem;
    }
</style>

<div class="complaint-list-page">
    <div class="container-fluid px-4 py-4">
        <div class="card page-heading-card mb-4">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <a href="index.php" class="text-decoration-none text-primary small fw-bold">
                                <i class="bi bi-chevron-left me-1"></i>Dashboard
                            </a>
                        </div>
                        <h4 class="mb-1 fw-bold text-dark">Pengurusan Aduan</h4>
                        <p class="mb-0 text-muted">Semak aduan yang sedang menunggu tindakan teknikal dan rekod aduan terdahulu.</p>
                    </div>

                    <div class="complaint-tab-wrapper">
                        <a href="senarai_aduan.php?tab=pending" class="complaint-tab <?php echo $active_tab === 'pending' ? 'active' : ''; ?>">
                            <i class="bi bi-hourglass-split"></i>
                            Pending Aduan
                            <span class="count-pill"><?php echo $total_pending; ?></span>
                        </a>
                        <a href="senarai_aduan.php?tab=all" class="complaint-tab <?php echo $active_tab === 'all' ? 'active' : ''; ?>">
                            <i class="bi bi-list-check"></i>
                            Senarai Aduan
                            <span class="count-pill"><?php echo $total_all; ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card list-card">
            <div class="card-body p-0">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 px-4 py-3 border-bottom">
                    <div>
                        <h6 class="mb-1 fw-bold text-dark">
                            <?php echo $active_tab === 'pending' ? 'Aduan Menunggu Tindakan' : 'Semua Rekod Aduan'; ?>
                        </h6>
                        <span class="text-muted small">
                            <?php echo number_format($total_filtered); ?> rekod ditemui
                        </span>
                    </div>

                    <form method="get" class="search-box w-100">
                        <input type="hidden" name="tab" value="<?php echo e($active_tab); ?>">
                        <div class="input-group">
                            <span class="input-group-text border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input
                                type="text"
                                name="q"
                                value="<?php echo e($keyword); ?>"
                                class="form-control border-start-0"
                                placeholder="Cari ID, tajuk, nama atau no. pertubuhan..."
                            >
                            <?php if ($keyword !== ''): ?>
                                <a href="senarai_aduan.php?tab=<?php echo e($active_tab); ?>" class="btn btn-light border" title="Kosongkan carian">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                            <?php endif; ?>
                            <button class="btn btn-primary px-3" type="submit">Cari</button>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table complaint-table mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">ID Aduan</th>
                                <th>Tarikh</th>
                                <th>Jenis</th>
                                <th style="min-width: 270px;">Aduan</th>
                                <th>Pertubuhan / Pengadu</th>
                                <th>Severity</th>
                                <th>PIC</th>
                                <th>Status Teknikal</th>
                                <th class="text-center pe-4">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($complaints)): ?>
                                <tr>
                                    <td colspan="9">
                                        <div class="empty-state">
                                            <i class="bi bi-inbox"></i>
                                            <h6 class="fw-bold text-secondary">Tiada aduan dijumpai</h6>
                                            <p class="mb-0 small">Cuba ubah kata kunci carian atau semak tab yang lain.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($complaints as $complaint): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <span class="fw-bold text-primary">#<?php echo e((string)$complaint['id']); ?></span>
                                        </td>
                                        <td class="text-nowrap">
                                            <?php echo e(formatDateTime($complaint['created_date'])); ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                <?php echo e(getTypeLabel($complaint['type'])); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="complaint-title mb-1"><?php echo e($complaint['title'] ?: '-'); ?></div>
                                            <div class="text-muted small"><?php echo e(truncateText($complaint['details'])); ?></div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark"><?php echo e($complaint['society_no'] ?: '-'); ?></div>
                                            <div class="text-muted small"><?php echo e($complaint['name'] ?: '-'); ?></div>
                                        </td>
                                        <td><?php echo getSeverityBadge($complaint['severity']); ?></td>
                                        <td>
                                            <div class="fw-semibold text-dark"><?php echo e($complaint['PIC_name'] ?: '-'); ?></div>
                                            <?php if (!empty($complaint['assign_date'])): ?>
                                                <div class="text-muted small">Alir: <?php echo e(formatDateTime($complaint['assign_date'])); ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo getTechnicalStatusBadge($complaint['status_technical']); ?></td>
                                        <td class="text-center pe-4">
                                            <a href="view_aduan.php?id=<?php echo urlencode((string)$complaint['id']); ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 text-nowrap">
                                                <i class="bi bi-eye me-1"></i>Lihat
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($total_pages > 1): ?>
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 px-4 py-3 border-top">
                        <span class="text-muted small">
                            Halaman <?php echo $current_page; ?> daripada <?php echo $total_pages; ?>
                        </span>

                        <nav aria-label="Pagination aduan">
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item <?php echo $current_page <= 1 ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo e(buildPageUrl(max(1, $current_page - 1))); ?>">Sebelum</a>
                                </li>

                                <?php
                                    $start_page = max(1, $current_page - 2);
                                    $end_page   = min($total_pages, $current_page + 2);
                                ?>

                                <?php for ($page = $start_page; $page <= $end_page; $page++): ?>
                                    <li class="page-item <?php echo $page === $current_page ? 'active' : ''; ?>">
                                        <a class="page-link" href="<?php echo e(buildPageUrl($page)); ?>"><?php echo $page; ?></a>
                                    </li>
                                <?php endfor; ?>

                                <li class="page-item <?php echo $current_page >= $total_pages ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo e(buildPageUrl(min($total_pages, $current_page + 1))); ?>">Seterusnya</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
