<?php

require_once __DIR__ . '/config/conn.php';

/*
|--------------------------------------------------------------------------
| Helper Functions
|--------------------------------------------------------------------------
*/

function e(?string $value): string
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

function buildUrl(int $page): string
{
    $params = $_GET;
    $params['page'] = $page;

    return '?' . http_build_query($params);
}

/*
|--------------------------------------------------------------------------
| Available Red Flag Categories
|--------------------------------------------------------------------------
*/

$categories = [
    'flag_society_information' => [
        'label' => 'Maklumat Pertubuhan',
        'icon'  => 'solar:buildings-2-bold-duotone',
    ],
    'flag_society_committee' => [
        'label' => 'Senarai AJK',
        'icon'  => 'solar:users-group-two-rounded-bold-duotone',
    ],
    'flag_members' => [
        'label' => 'Keahlian',
        'icon'  => 'solar:user-id-bold-duotone',
    ],
    'flag_meetings' => [
        'label' => 'Mesyuarat',
        'icon'  => 'solar:calendar-bold-duotone',
    ],
    'flag_notice' => [
        'label' => 'Notis',
        'icon'  => 'solar:bell-bold-duotone',
    ],
    'flag_complaint' => [
        'label' => 'Aduan',
        'icon'  => 'solar:chat-square-warning-bold-duotone',
    ],
    'flag_annual_statement' => [
        'label' => 'Penyata Tahunan',
        'icon'  => 'solar:document-text-bold-duotone',
    ],
];

/*
|--------------------------------------------------------------------------
| Input
|--------------------------------------------------------------------------
*/

$keyword          = trim($_GET['keyword'] ?? '');
$selectedCategory = trim($_GET['category'] ?? '');
$currentPage      = max(1, (int) ($_GET['page'] ?? 1));

$limit  = 30;
$offset = ($currentPage - 1) * $limit;

/*
|--------------------------------------------------------------------------
| Validate Category
|--------------------------------------------------------------------------
*/

if (
    $selectedCategory !== ''
    && !array_key_exists(
        $selectedCategory,
        $categories
    )
) {
    $selectedCategory = '';
}

/*
|--------------------------------------------------------------------------
| Build Filter
|--------------------------------------------------------------------------
*/

$where      = ['flag_total > 0'];
$bindTypes  = '';
$bindValues = [];

if ($keyword !== '') {
    $where[] = '(
        society_id LIKE ?
        OR society_name LIKE ?
    )';

    $searchValue = '%' . $keyword . '%';

    $bindTypes   .= 'ss';
    $bindValues[] = $searchValue;
    $bindValues[] = $searchValue;
}

if ($selectedCategory !== '') {
    /*
    | Column name cannot be passed as a bind parameter.
    | It is safe here because the value has already been
    | validated against the $categories array.
    */
    $where[] = "{$selectedCategory} > 0";
}

$whereSql = ' WHERE ' . implode(' AND ', $where);

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

$summarySql = "
    SELECT
        COUNT(*) AS total_societies,
        COALESCE(SUM(flag_total), 0) AS total_flags,
        MAX(checking_date) AS latest_checking_date
    FROM eroses_society.redflag_list
    WHERE flag_total > 0
";

$summaryResult = $conn->query($summarySql);
$summary       = $summaryResult->fetch_assoc();

/*
|--------------------------------------------------------------------------
| Category Totals
|--------------------------------------------------------------------------
*/

$categorySql = "
    SELECT
        COALESCE(
            SUM(flag_society_information),
            0
        ) AS flag_society_information,

        COALESCE(
            SUM(flag_society_committee),
            0
        ) AS flag_society_committee,

        COALESCE(
            SUM(flag_members),
            0
        ) AS flag_members,

        COALESCE(
            SUM(flag_meetings),
            0
        ) AS flag_meetings,

        COALESCE(
            SUM(flag_notice),
            0
        ) AS flag_notice,

        COALESCE(
            SUM(flag_complaint),
            0
        ) AS flag_complaint,

        COALESCE(
            SUM(flag_annual_statement),
            0
        ) AS flag_annual_statement
    FROM eroses_society.redflag_list
    WHERE flag_total > 0
";

$categoryResult = $conn->query($categorySql);
$categoryTotals = $categoryResult->fetch_assoc();

/*
|--------------------------------------------------------------------------
| Count Filtered Records
|--------------------------------------------------------------------------
*/

$countSql = "
    SELECT
        COUNT(*) AS total
    FROM eroses_society.redflag_list
    {$whereSql}
";

$countStatement = $conn->prepare($countSql);

if (!empty($bindValues)) {
    $countStatement->bind_param(
        $bindTypes,
        ...$bindValues
    );
}

$countStatement->execute();

$countResult   = $countStatement->get_result();
$totalFiltered = (int) $countResult
    ->fetch_assoc()['total'];

$totalPages = max(
    1,
    (int) ceil($totalFiltered / $limit)
);

if ($currentPage > $totalPages) {
    $currentPage = $totalPages;
    $offset      = ($currentPage - 1) * $limit;
}

/*
|--------------------------------------------------------------------------
| Fetch Main List
|--------------------------------------------------------------------------
*/

$dataSql = "
    SELECT
        id,
        society_id,
        society_name,
        flag_society_information,
        flag_society_committee,
        flag_members,
        flag_meetings,
        flag_notice,
        flag_complaint,
        flag_annual_statement,
        flag_total,
        checking_date
    FROM eroses_society.redflag_list
    {$whereSql}
    ORDER BY
        flag_total DESC,
        society_name ASC
    LIMIT ?
    OFFSET ?
";

$dataStatement = $conn->prepare($dataSql);

$dataBindTypes  = $bindTypes . 'ii';
$dataBindValues = array_merge(
    $bindValues,
    [$limit, $offset]
);

$dataStatement->bind_param(
    $dataBindTypes,
    ...$dataBindValues
);

$dataStatement->execute();

$rows = $dataStatement
    ->get_result()
    ->fetch_all(MYSQLI_ASSOC);

/*
|--------------------------------------------------------------------------
| Pagination Window
|--------------------------------------------------------------------------
*/

$paginationStart = max(
    1,
    $currentPage - 2
);

$paginationEnd = min(
    $totalPages,
    $currentPage + 2
);

?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Sandbox Red Flag</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <script
        src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"
    ></script>

    <style>
        body {
            background-color: #f4f7fb;
            color: #172033;
        }

        .page-header {
            padding: 30px 0 18px;
        }

        .page-title-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 46px;
            margin-right: 10px;
            border-radius: 14px;
            background-color: #fff0f0;
            color: #d9363e;
            font-size: 27px;
        }

        .summary-card,
        .filter-card,
        .table-card {
            border: 0;
            border-radius: 18px;
            box-shadow: 0 6px 20px rgba(17, 31, 54, 0.06);
        }

        .summary-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 13px;
            background-color: #eef4ff;
            color: #3166b8;
            font-size: 24px;
        }

        .summary-value {
            margin-top: 14px;
            font-size: 1.8rem;
            font-weight: 750;
        }

        .category-box {
            border-radius: 14px;
            background-color: #f8fafc;
            padding: 13px;
            height: 100%;
        }

        .category-box iconify-icon {
            color: #50627d;
            font-size: 21px;
        }

        .category-total {
            font-size: 1.3rem;
            font-weight: 700;
        }

        .table-card {
            overflow: hidden;
        }

        .table thead th {
            padding: 15px 13px;
            background-color: #202b3d;
            color: #ffffff;
            border: 0;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.02rem;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 13px;
            border-color: #edf0f4;
            font-size: 0.86rem;
            vertical-align: middle;
        }

        .society-name {
            min-width: 310px;
            font-weight: 600;
        }

        .flag-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin: 2px 2px 2px 0;
            padding: 5px 8px;
            border-radius: 999px;
            background-color: #f0f4fa;
            color: #344660;
            font-size: 0.75rem;
            white-space: nowrap;
        }

        .flag-pill.has-value {
            background-color: #fff0f0;
            color: #b72b33;
            font-weight: 600;
        }

        .total-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 30px;
            padding: 0 9px;
            border-radius: 999px;
            background-color: #d9363e;
            color: #ffffff;
            font-weight: 700;
        }

        .detail-group {
            margin-bottom: 15px;
            border: 1px solid #e8edf4;
            border-radius: 14px;
            overflow: hidden;
        }

        .detail-group-header {
            padding: 12px 14px;
            background-color: #f5f7fa;
            font-weight: 700;
        }

        .detail-row {
            padding: 13px 14px;
            border-top: 1px solid #edf0f4;
        }

        .detail-item {
            color: #344660;
            font-weight: 650;
        }

        .detail-value {
            margin-top: 4px;
            color: #101828;
        }

        .empty-state {
            padding: 65px 20px;
            color: #6b778c;
            text-align: center;
        }

        .empty-state iconify-icon {
            font-size: 50px;
        }
    </style>
</head>

<body>

<div class="container-fluid px-4 px-xl-5 pb-5">

    <header class="page-header">
        <div class="d-flex align-items-center">
            <span class="page-title-icon">
                <iconify-icon
                    icon="solar:shield-warning-bold-duotone"
                ></iconify-icon>
            </span>

            <div>
                <h2 class="fw-bold mb-1">
                    Sandbox Red Flag
                </h2>

                <div class="text-secondary">
                    Senarai pertubuhan yang memerlukan semakan lanjut.
                </div>
            </div>
        </div>
    </header>

    <section class="row g-3 mb-4">

        <div class="col-12 col-md-4">
            <div class="card summary-card h-100">
                <div class="card-body">
                    <span class="summary-icon">
                        <iconify-icon
                            icon="solar:buildings-3-bold-duotone"
                        ></iconify-icon>
                    </span>

                    <div class="summary-value">
                        <?= number_format(
                            (int) $summary['total_societies']
                        ) ?>
                    </div>

                    <div class="text-secondary small">
                        Pertubuhan mempunyai red flag
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card summary-card h-100">
                <div class="card-body">
                    <span class="summary-icon">
                        <iconify-icon
                            icon="solar:flag-bold-duotone"
                        ></iconify-icon>
                    </span>

                    <div class="summary-value">
                        <?= number_format(
                            (int) $summary['total_flags']
                        ) ?>
                    </div>

                    <div class="text-secondary small">
                        Jumlah keseluruhan red flag
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card summary-card h-100">
                <div class="card-body">
                    <span class="summary-icon">
                        <iconify-icon
                            icon="solar:calendar-date-bold-duotone"
                        ></iconify-icon>
                    </span>

                    <div class="summary-value fs-5 pt-2">
                        <?= e(
                            $summary['latest_checking_date']
                            ?: '-'
                        ) ?>
                    </div>

                    <div class="text-secondary small mt-2">
                        Tarikh semakan terkini
                    </div>
                </div>
            </div>
        </div>

    </section>

    <section class="card summary-card mb-4">
        <div class="card-body">

            <div class="fw-bold mb-3">
                Pecahan Red Flag Mengikut Kategori
            </div>

            <div class="row g-2">

                <?php foreach (
                    $categories as $column => $category
                ): ?>

                    <div class="col-6 col-md-4 col-xl">
                        <div class="category-box">

                            <iconify-icon
                                icon="<?= e($category['icon']) ?>"
                            ></iconify-icon>

                            <div class="category-total mt-2">
                                <?= number_format(
                                    (int) (
                                        $categoryTotals[$column]
                                        ?? 0
                                    )
                                ) ?>
                            </div>

                            <div class="small text-secondary">
                                <?= e($category['label']) ?>
                            </div>

                        </div>
                    </div>

                <?php endforeach; ?>

            </div>
        </div>
    </section>

    <section class="card filter-card mb-4">
        <div class="card-body">

            <form
                method="GET"
                class="row g-3 align-items-end"
            >

                <div class="col-12 col-lg-6">
                    <label
                        for="keyword"
                        class="form-label fw-semibold"
                    >
                        Carian
                    </label>

                    <input
                        type="text"
                        id="keyword"
                        name="keyword"
                        class="form-control"
                        value="<?= e($keyword) ?>"
                        placeholder="Cari ID atau nama pertubuhan"
                    >
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <label
                        for="category"
                        class="form-label fw-semibold"
                    >
                        Kategori Red Flag
                    </label>

                    <select
                        id="category"
                        name="category"
                        class="form-select"
                    >
                        <option value="">
                            Semua kategori
                        </option>

                        <?php foreach (
                            $categories as $column => $category
                        ): ?>

                            <option
                                value="<?= e($column) ?>"
                                <?= $selectedCategory === $column
                                    ? 'selected'
                                    : '' ?>
                            >
                                <?= e($category['label']) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="col-12 col-md-6 col-lg-3">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <iconify-icon
                            icon="solar:magnifer-linear"
                        ></iconify-icon>

                        Cari
                    </button>

                    <a
                        href="index.php"
                        class="btn btn-outline-secondary"
                    >
                        Reset
                    </a>

                </div>

            </form>

        </div>
    </section>

    <section>

        <div
            class="d-flex flex-wrap justify-content-between gap-2 mb-3"
        >
            <div>
                <strong>
                    <?= number_format($totalFiltered) ?>
                </strong>

                pertubuhan ditemui
            </div>

            <div class="small text-secondary">
                Halaman
                <?= number_format($currentPage) ?>
                daripada
                <?= number_format($totalPages) ?>
            </div>
        </div>

        <div class="card table-card">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ID Pertubuhan</th>
                            <th>Nama Pertubuhan</th>
                            <th>Pecahan Red Flag</th>
                            <th class="text-center">Jumlah</th>
                            <th>Tarikh Semakan</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (empty($rows)): ?>

                        <tr>
                            <td colspan="7">
                                <div class="empty-state">

                                    <iconify-icon
                                        icon="solar:shield-check-bold-duotone"
                                    ></iconify-icon>

                                    <div class="fw-bold mt-2">
                                        Tiada rekod ditemui
                                    </div>

                                    <div class="small mt-1">
                                        Cuba ubah kata kunci atau tapisan.
                                    </div>

                                </div>
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach (
                            $rows as $index => $row
                        ): ?>

                            <tr>

                                <td>
                                    <?= number_format(
                                        $offset + $index + 1
                                    ) ?>
                                </td>

                                <td>
                                    <?= e($row['society_id']) ?>
                                </td>

                                <td class="society-name">
                                    <?= e($row['society_name']) ?>
                                </td>

                                <td>
                                    <?php foreach (
                                        $categories
                                        as $column => $category
                                    ): ?>

                                        <?php
                                        $value = (int) (
                                            $row[$column]
                                            ?? 0
                                        );
                                        ?>

                                        <?php if ($value > 0): ?>

                                            <span
                                                class="flag-pill has-value"
                                                title="<?= e(
                                                    $category['label']
                                                ) ?>"
                                            >
                                                <?= e(
                                                    $category['label']
                                                ) ?>

                                                <?= number_format(
                                                    $value
                                                ) ?>
                                            </span>

                                        <?php endif; ?>

                                    <?php endforeach; ?>
                                </td>

                                <td class="text-center">
                                    <span class="total-badge">
                                        <?= number_format(
                                            (int) $row['flag_total']
                                        ) ?>
                                    </span>
                                </td>

                                <td class="text-nowrap">
                                    <?= e($row['checking_date']) ?>
                                </td>

                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#detailModal"
                                        data-society-id="<?= e(
                                            $row['society_id']
                                        ) ?>"
                                        data-society-name="<?= e(
                                            $row['society_name']
                                        ) ?>"
                                    >
                                        <iconify-icon
                                            icon="solar:eye-linear"
                                        ></iconify-icon>

                                        Lihat Butiran
                                    </button>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

        <?php if ($totalPages > 1): ?>

            <nav class="mt-4">
                <ul
                    class="pagination justify-content-center flex-wrap"
                >

                    <li
                        class="page-item <?= $currentPage <= 1
                            ? 'disabled'
                            : '' ?>"
                    >
                        <a
                            class="page-link"
                            href="<?= e(
                                buildUrl(
                                    max(
                                        1,
                                        $currentPage - 1
                                    )
                                )
                            ) ?>"
                        >
                            Sebelum
                        </a>
                    </li>

                    <?php for (
                        $page = $paginationStart;
                        $page <= $paginationEnd;
                        $page++
                    ): ?>

                        <li
                            class="page-item <?= $page === $currentPage
                                ? 'active'
                                : '' ?>"
                        >
                            <a
                                class="page-link"
                                href="<?= e(buildUrl($page)) ?>"
                            >
                                <?= number_format($page) ?>
                            </a>
                        </li>

                    <?php endfor; ?>

                    <li
                        class="page-item <?= $currentPage >= $totalPages
                            ? 'disabled'
                            : '' ?>"
                    >
                        <a
                            class="page-link"
                            href="<?= e(
                                buildUrl(
                                    min(
                                        $totalPages,
                                        $currentPage + 1
                                    )
                                )
                            ) ?>"
                        >
                            Seterusnya
                        </a>
                    </li>

                </ul>
            </nav>

        <?php endif; ?>

    </section>

</div>

<div
    class="modal fade"
    id="detailModal"
    tabindex="-1"
    aria-hidden="true"
>
    <div
        class="modal-dialog modal-lg modal-dialog-scrollable"
    >
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold">
                        Butiran Red Flag
                    </h5>

                    <div
                        id="modalSocietyName"
                        class="small text-secondary mt-1"
                    ></div>
                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Tutup"
                ></button>
            </div>

            <div
                id="modalBody"
                class="modal-body"
            >
                <div class="text-center py-5">
                    Memuatkan maklumat...
                </div>
            </div>

        </div>
    </div>
</div>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

<script>
    const detailModal = document.getElementById(
        'detailModal'
    );

    const modalBody = document.getElementById(
        'modalBody'
    );

    const modalSocietyName = document.getElementById(
        'modalSocietyName'
    );

    detailModal.addEventListener(
        'show.bs.modal',
        async function (event) {
            const button = event.relatedTarget;

            const societyId = button.getAttribute(
                'data-society-id'
            );

            const societyName = button.getAttribute(
                'data-society-name'
            );

            modalSocietyName.textContent =
                societyId + ' | ' + societyName;

            modalBody.innerHTML = `
                <div class="text-center py-5">
                    <div
                        class="spinner-border text-primary"
                        role="status"
                    ></div>

                    <div class="mt-3 text-secondary">
                        Memuatkan butiran red flag...
                    </div>
                </div>
            `;

            try {
                const response = await fetch(
                    'ajax/get_redflag_details.php?society_id='
                    + encodeURIComponent(societyId)
                );

                if (!response.ok) {
                    throw new Error(
                        'Failed to load details'
                    );
                }

                modalBody.innerHTML =
                    await response.text();

            } catch (error) {
                modalBody.innerHTML = `
                    <div
                        class="alert alert-danger mb-0"
                    >
                        Maklumat tidak dapat dimuatkan.
                    </div>
                `;
            }
        }
    );
</script>

</body>
</html>