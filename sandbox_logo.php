<?php

require_once 'config/conn.php';

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function e($value)
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

$keyword = trim($_GET['keyword'] ?? '');
$results = [];

if ($keyword !== '') {

    /*
    |--------------------------------------------------------------------------
    | Sumber imej
    |--------------------------------------------------------------------------
    |
    | 1. constitution_value:
    |    URL telah tersedia sepenuhnya dalam definition_name.
    |
    | 2. fasal_combine:
    |    Nilai di dalam lambang_pic, bendera_pic dan lencana_pic
    |    dianggap sebagai nama fail. URL penuh dibina menggunakan CONCAT.
    |
    | CASE WHEN turut disediakan sebagai perlindungan sekiranya ada row
    | lama yang telah menyimpan URL penuh.
    |
    */


        $sql = "
        SELECT DISTINCT
            image_data.id,
            image_data.society_no,
            image_data.society_name,
            image_data.image_type,
            image_data.image_url,
            image_data.source_table
        FROM
        (
            /*
            |--------------------------------------------------------------------------
            | Constitution Value
            |--------------------------------------------------------------------------
            */

            SELECT
                s.id,
                s.society_no,
                s.society_name,
                'Logo' AS image_type,
                cv.definition_name AS image_url,
                'constitution_value' AS source_table
            FROM eroses_society.society s
            INNER JOIN eroses_society.constitution_value cv
                ON cv.society_id = s.id
            WHERE s.society_name LIKE ?
            AND cv.definition_name LIKE 'https%'

            UNION ALL

            /*
            |--------------------------------------------------------------------------
            | Lambang
            |--------------------------------------------------------------------------
            */

            SELECT
                s.id,
                s.society_no,
                s.society_name,
                'Lambang' AS image_type,
                CASE
                    WHEN fc.lambang_pic LIKE 'http%'
                        THEN fc.lambang_pic
                    ELSE CONCAT(
                        'https://v1.ros.gov.my/upload/photo/lambang/',
                        TRIM(LEADING '/' FROM fc.lambang_pic)
                    )
                END AS image_url,
                'fasal_combine' AS source_table
            FROM eroses_society.society s
            INNER JOIN roses2.fasal_combine fc
                ON fc.no_pertubuhan = s.society_no
            WHERE s.society_name LIKE ?
            AND fc.lambang_pic IS NOT NULL
            AND TRIM(fc.lambang_pic) <> ''
            AND TRIM(fc.lambang_pic) <> '-'

            UNION ALL

            /*
            |--------------------------------------------------------------------------
            | Bendera
            |--------------------------------------------------------------------------
            */

            SELECT
                s.id,
                s.society_no,
                s.society_name,
                'Bendera' AS image_type,
                CASE
                    WHEN fc.bendera_pic LIKE 'http%'
                        THEN fc.bendera_pic
                    ELSE CONCAT(
                        'https://v1.ros.gov.my/upload/photo/bendera/',
                        TRIM(LEADING '/' FROM fc.bendera_pic)
                    )
                END AS image_url,
                'fasal_combine' AS source_table
            FROM eroses_society.society s
            INNER JOIN roses2.fasal_combine fc
                ON fc.no_pertubuhan = s.society_no
            WHERE s.society_name LIKE ?
            AND fc.bendera_pic IS NOT NULL
            AND TRIM(fc.bendera_pic) <> ''
            AND TRIM(fc.bendera_pic) <> '-'

            UNION ALL

            /*
            |--------------------------------------------------------------------------
            | Lencana
            |--------------------------------------------------------------------------
            */

            SELECT
                s.id,
                s.society_no,
                s.society_name,
                'Lencana' AS image_type,
                CASE
                    WHEN fc.lencana_pic LIKE 'http%'
                        THEN fc.lencana_pic
                    ELSE CONCAT(
                        'https://v1.ros.gov.my/upload/photo/lencana/',
                        TRIM(LEADING '/' FROM fc.lencana_pic)
                    )
                END AS image_url,
                'fasal_combine' AS source_table
            FROM eroses_society.society s
            INNER JOIN roses2.fasal_combine fc
                ON fc.no_pertubuhan = s.society_no
            WHERE s.society_name LIKE ?
            AND fc.lencana_pic IS NOT NULL
            AND TRIM(fc.lencana_pic) <> ''
            AND TRIM(fc.lencana_pic) <> '-'
        ) image_data
        ORDER BY
            image_data.society_name ASC,
            image_data.image_type ASC
        LIMIT 500
    ";


    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die(
            'SQL prepare error: '
            . e($conn->error)
        );
    }

    $searchKeyword = '%' . $keyword . '%';

    $stmt->bind_param(
        'ssss',
        $searchKeyword,
        $searchKeyword,
        $searchKeyword,
        $searchKeyword
    );

    $stmt->execute();

    $queryResult = $stmt->get_result();

    while ($row = $queryResult->fetch_assoc()) {
        $results[] = $row;
    }

    $stmt->close();
}

/*
|--------------------------------------------------------------------------
| Badge Style
|--------------------------------------------------------------------------
*/

function getBadgeClass($imageType)
{
    switch ($imageType) {
        case 'Lambang':
            return 'text-bg-primary';

        case 'Bendera':
            return 'text-bg-danger';

        case 'Lencana':
            return 'text-bg-success';

        default:
            return 'text-bg-secondary';
    }
}

?>

<!doctype html>
<html lang="ms">

<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Carian Imej Pertubuhan</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background-color: #f5f7fa;
        }

        .page-header {
            background-color: #ffffff;
            border-bottom: 1px solid #e5e7eb;
        }

        .image-card {
            height: 100%;
            overflow: hidden;
            transition:
                transform 0.15s ease,
                box-shadow 0.15s ease;
        }

        .image-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.08);
        }

        .image-container {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 220px;
            padding: 1rem;
            background-color: #ffffff;
            border-bottom: 1px solid #e9ecef;
        }

        .image-preview {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .image-type-badge {
            position: absolute;
            top: 0.75rem;
            left: 0.75rem;
        }

        .society-name {
            min-height: 48px;
        }

        .source-table {
            font-size: 0.75rem;
        }
    </style>
</head>

<body>

<header class="page-header py-4 mb-4">
    <div class="container">
        <h1 class="h3 mb-1">
            Carian Imej Pertubuhan
        </h1>

        <p class="text-secondary mb-0">
            Cari logo, lambang, bendera dan lencana berdasarkan nama pertubuhan.
        </p>
    </div>
</header>

<main class="container pb-5">

    <form
        method="get"
        class="card card-body shadow-sm mb-4"
    >
        <div class="row g-2">

            <div class="col-md-10">
                <input
                    type="text"
                    name="keyword"
                    class="form-control form-control-lg"
                    placeholder="Contoh: pertubuhan amal"
                    value="<?= e($keyword) ?>"
                    required
                >
            </div>

            <div class="col-md-2 d-grid">
                <button
                    type="submit"
                    class="btn btn-primary btn-lg"
                >
                    Cari
                </button>
            </div>

        </div>
    </form>

    <?php if ($keyword !== ''): ?>

        <div class="d-flex justify-content-between align-items-center mb-3">

            <h2 class="h5 mb-0">
                Hasil carian:
                <span class="text-primary">
                    <?= e($keyword) ?>
                </span>
            </h2>

            <span class="badge text-bg-secondary">
                <?= count($results) ?> imej
            </span>

        </div>

        <?php if (count($results) === 0): ?>

            <div class="alert alert-warning">
                Tiada imej dijumpai untuk kata kunci tersebut.
            </div>

        <?php else: ?>

            <div class="row g-4">

                <?php foreach ($results as $row): ?>

                    <div class="col-sm-6 col-lg-4 col-xl-3">

                        <div class="card image-card shadow-sm">

                            <div class="image-container">

                                <span
                                    class="
                                        badge
                                        image-type-badge
                                        <?= e(getBadgeClass($row['image_type'])) ?>
                                    "
                                >
                                    <?= e($row['image_type']) ?>
                                </span>

                                <img
                                    src="<?= e($row['image_url']) ?>"
                                    alt="<?= e($row['society_name']) ?>"
                                    class="image-preview"
                                    loading="lazy"
                                    onerror="
                                        this.onerror = null;
                                        this.src = 'https://placehold.co/400x250?text=Imej+Tidak+Dapat+Dipaparkan';
                                    "
                                >

                            </div>

                            <div class="card-body">

                                <h3 class="h6 society-name mb-3">
                                    <?= e($row['society_name']) ?>
                                </h3>

                                <div class="small mb-2">
                                    <div class="text-secondary">
                                        No. Pertubuhan
                                    </div>

                                    <div class="fw-semibold">
                                        <?= e($row['society_no']) ?>
                                    </div>
                                </div>

                                <div class="small mb-3">
                                    <div class="text-secondary">
                                        Nama Pertubuhan
                                    </div>
                                </div>

                                <a
                                    href="<?= e($row['image_url']) ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn btn-outline-primary btn-sm w-100"
                                >
                                    Buka Imej
                                </a>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    <?php endif; ?>

</main>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>