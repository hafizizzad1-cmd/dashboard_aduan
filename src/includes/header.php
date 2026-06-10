

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../assets/images/favicon/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="../assets/images/favicon/favicon.png" type="image/x-icon">
    <title>STATISTIK MEJA BANTUAN</title>
    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/font-awesome.css">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/icofont.css">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/themify.css">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/flag-icon.css">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/feather-icon.css">
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/scrollbar.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/animate.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/chartist.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/date-picker.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/slick.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/slick-theme.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/prism.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/whether-icon.css">
	
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/datatables.css">
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/bootstrap.css">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link id="color" rel="stylesheet" href="../assets/css/color-1.css" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="../assets/css/responsive.css">
	
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
	<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
	<script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>



	<style>
		/* =========================================
		   1. PEMBOLEHUBAH WARNA & ASAS (ROOT)
		   ========================================= */
		:root {
			--primary-blue: #1e40af; 
			--accent-blue: #2563eb;  
			--light-blue: #eff6ff;   
			--body-bg: #f8fafc;
			--gold-bg: #fffbeb; 
			--gold-border: #f59e0b;
			--gold-text: #b45309;
			--purple-custom: #7c3aed; 
			--neon-green: #10b981;
			--neon-glow: rgba(16, 185, 129, 0.4);
		}

		body { 
			background-color: var(--body-bg); 
			font-family: 'Segoe UI', sans-serif; 
		}

		/* PENTING: Override margin Enzo supaya content memanjang penuh (Tiada Sidebar) */
		.page-wrapper .page-body-wrapper .page-body {
			margin-left: 0 !important; 
			margin-top: 0 !important;
		}

		/* =========================================
		   2. TOPBAR & NAVIGASI
		   ========================================= */
		.top-title-row {
			background-color: var(--primary-blue);
			color: white;
			font-size: 0.85rem;
			padding: 8px 15px;
			z-index: 1030;
			position: relative;
		}
		
		.modern-topbar {
			background: rgba(255, 255, 255, 0.9);
			backdrop-filter: blur(12px);
			border-bottom: 1px solid rgba(37, 99, 235, 0.1);
			box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
			z-index: 1020;
			height: 75px; /* Ditambah mengikut template terkini bos */
		}
		
		.modern-topbar .nav-item .nav-link {
			color: #475569; font-weight: 600; padding: 0.6rem 1.25rem;
			border-radius: 10px; transition: all 0.3s ease;
		}
		
		.modern-topbar .nav-item .nav-link:hover, 
		.modern-topbar .nav-item .nav-link.active {
			background-color: var(--light-blue); color: var(--accent-blue);
		}
		
		.modern-topbar .dropdown-menu {
			border: none; box-shadow: 0 10px 40px rgba(37, 99, 235, 0.1);
			border-radius: 16px; padding: 0.75rem; margin-top: 10px;
		}
		
		.modern-topbar .dropdown-item {
			border-radius: 8px; font-weight: 500; color: #475569;
			padding: 0.6rem 1rem; transition: all 0.2s;
		}
		
		.modern-topbar .dropdown-item:hover {
			background-color: var(--light-blue); color: var(--accent-blue);
		}

		/* =========================================
		   3. REKAAN KAD UMUM & KAD PADAT
		   ========================================= */
		.card { 
			border: none; 
			border-radius: 16px; 
			box-shadow: 0 4px 15px rgba(0,0,0,0.03); 
			margin-bottom: 20px; 
		}
		
		.card-header { 
			background: transparent; 
			border-bottom: 1px solid #f1f5f9; 
			padding: 15px 20px; 
		}
		
		.card-header h5, .card-header h6 { 
			margin-bottom: 0; 
			font-weight: 700; 
			color: var(--primary-blue); 
		}
		
		.card-body { 
			padding: 20px; 
		}

		.card-padat {
			border-radius: 16px;
			box-shadow: 0 4px 15px rgba(0,0,0,0.03);
			border: none;
		}
		
		/* Form & Plaintext Layout */
		.form-label { 
			font-size: 0.75rem; 
			font-weight: 600; 
			color: #64748b; 
			text-transform: uppercase; 
			margin-bottom: 2px; 
		}
		
		.form-control-plaintext { 
			font-weight: 600; 
			color: #1e293b; 
			padding-top: 0; 
			font-size: 0.95rem; 
		}
		
		.input-padat {
			background-color: var(--body-bg);
			border: 1px solid #e2e8f0;
			text-align: center; font-weight: bold; border-radius: 8px;
		}

		/* Rekaan Kad Statistik / Ringkasan */
		.summary-card {
			border-radius: 16px; border: 1px solid #e2e8f0;
			border-bottom: 4px solid var(--accent-blue) !important;
			transition: all 0.3s ease; background: white;
		}
		.summary-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); }
		.summary-card.rank-1 { border-bottom: 4px solid var(--gold-border) !important; background: linear-gradient(180deg, #ffffff 0%, #fffbeb 100%); }

		.day-card { transition: all 0.3s ease-in-out; border: 1px solid #e2e8f0; border-radius: 20px; display: flex; flex-direction: column; position: relative; }
		.day-card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.12); }

		.card-glow-success:hover { box-shadow: 0 0 40px rgba(16, 185, 129, 0.6) !important; }

		/* Lencana (Badges) */
		.badge-soft-success { background-color: #dcfce7; color: #15803d; }
		
		.achievement-badge {
			position: absolute; top: -12px; right: 15px;
			background: var(--neon-green); color: white;
			padding: 4px 15px; border-radius: 50px; font-size: 0.7rem; font-weight: 800;
			box-shadow: 0 4px 8px rgba(0,0,0,0.15); z-index: 5;
			letter-spacing: 0.5px; display: flex; align-items: center; gap: 4px;
		}
		
		.staff-mini-stat { font-size: 0.75rem; margin-bottom: 12px; }
		.staff-mini-stat .progress { height: 5px; background-color: rgba(0,0,0,0.05); border-radius: 10px; }
		.total-count { font-size: 0.85rem; font-weight: 800; color: var(--accent-blue); }
		
		.top-scorer-name { font-weight: 800 !important; font-size: 0.82rem; color: #0f172a; }
		.top-scorer-score { font-weight: 900 !important; font-size: 0.82rem; }

		/* =========================================
		   4. WIDGET STATISTIK KHAS (PERCENTAGE)
		   ========================================= */
		.widget-stat-card {
			background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
			border: 1px solid rgba(255, 255, 255, 0.8);
			box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04), inset 0 2px 0 rgba(255,255,255,1);
			border-radius: 24px; position: relative; overflow: hidden;
		}
		.widget-stat-card::before {
			content: ''; position: absolute; top: 0; right: 0; width: 200px; height: 200px;
			background: radial-gradient(circle, rgba(0,0,0,0.03) 0%, rgba(255,255,255,0) 70%);
			border-radius: 50%; transform: translate(30%, -30%); z-index: 0;
		}
		.widget-content { position: relative; z-index: 1; }
		.stat-label-main { font-size: 0.8rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; }
		.stat-value-main { font-size: 4.8rem; font-weight: 900; line-height: 1; letter-spacing: -2px; }
		
		.progress-custom { height: 12px; border-radius: 50px; background-color: #e2e8f0; overflow: hidden; box-shadow: inset 0 1px 2px rgba(0,0,0,0.05); }
		.progress-bar-custom { border-radius: 50px; position: relative; }
		.progress-bar-custom::after {
			content: ''; position: absolute; top: 0; left: 0; bottom: 0; right: 0;
			background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.4) 50%, rgba(255,255,255,0) 100%);
			animation: shimmer 2s infinite;
		}
		@keyframes shimmer { 0% { transform: translateX(-100%); } 100% { transform: translateX(100%); } }

		.stat-mini-box { background: white; border: 1px solid #f1f5f9; border-radius: 16px; padding: 15px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.02); }
		.mini-box-title { font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 5px; }
		.mini-box-value { font-size: 1.8rem; font-weight: 800; line-height: 1; }

		/* =========================================
		   5. TEMA DINAMIK WARNA (GOLD/GREEN/YELLOW/PURPLE)
		   ========================================= */
		/* Tema Gold */
		.theme-gold .stat-value-main { 
			background: linear-gradient(45deg, #bf953f, #fcf6ba, #b38728, #fbf5b7, #aa771c);
			-webkit-background-clip: text; -webkit-text-fill-color: transparent; filter: drop-shadow(0px 5px 8px rgba(184, 134, 11, 0.4));
		}
		.theme-gold .progress-bar-custom { background: linear-gradient(90deg, #d4af37 0%, #ffd700 100%); }
		.theme-gold .widget-badge { background-color: #fffbeb; border-color: #fde68a !important; color: #b45309; }
		.theme-gold .icon-theme { color: #d4af37; }
		.theme-gold .perc-color { color: #d4af37 !important; }
		.theme-gold .perc-bg { background-color: rgba(212, 175, 55, 0.1) !important; }
		.theme-gold .perc-border { border-color: #d4af37 !important; }
		
		/* Kad Gold Biasa */
		.card-gold { background-color: var(--gold-bg) !important; border: 2px solid var(--gold-border) !important; }
		.card-gold.card-glow-success { border: 2px solid var(--neon-green) !important; }
		.card-gold .badge { background-color: var(--gold-border) !important; color: white; }
		.card-gold .total-count { color: var(--gold-text) !important; }

		/* Tema Green */
		.theme-green .stat-value-main { color: #10b981; text-shadow: 0 10px 20px rgba(16, 185, 129, 0.2); }
		.theme-green .progress-bar-custom { background: linear-gradient(90deg, #059669 0%, #10b981 100%); }
		.theme-green .widget-badge { background-color: #ecfdf5; border-color: #a7f3d0 !important; color: #059669; }
		.theme-green .icon-theme { color: #10b981; }
		.theme-green .perc-color { color: #059669 !important; }
		.theme-green .perc-bg { background-color: rgba(5, 150, 105, 0.1) !important; }
		.theme-green .perc-border { border-color: #059669 !important; }

		/* Tema Yellow */
		.theme-yellow .stat-value-main { color: #f59e0b; text-shadow: 0 10px 20px rgba(245, 158, 11, 0.2); }
		.theme-yellow .progress-bar-custom { background: linear-gradient(90deg, #d97706 0%, #f59e0b 100%); }
		.theme-yellow .widget-badge { background-color: #fffbeb; border-color: #fde68a !important; color: #d97706; }
		.theme-yellow .icon-theme { color: #f59e0b; }
		.theme-yellow .perc-color { color: #d97706 !important; }
		.theme-yellow .perc-bg { background-color: rgba(217, 119, 6, 0.1) !important; }
		.theme-yellow .perc-border { border-color: #d97706 !important; }
		
		/* Tema Purple */
		.theme-purple .stat-value-main { 
			background: linear-gradient(45deg, #6b21a8, #c084fc, #7e22ce);
			-webkit-background-clip: text; -webkit-text-fill-color: transparent; 
			filter: drop-shadow(0px 5px 8px rgba(107, 33, 168, 0.3));
		}
		.theme-purple .progress-bar-custom { background: linear-gradient(90deg, #7e22ce 0%, #a855f7 100%); }
		.theme-purple .widget-badge { background-color: #f3e8ff; border-color: #d8b4fe !important; color: #7e22ce; }
		.theme-purple .icon-theme { color: #7e22ce; }
		.theme-purple .perc-color { color: #7e22ce !important; }
		.theme-purple .perc-bg { background-color: rgba(126, 34, 206, 0.1) !important; }
		.theme-purple .perc-border { border-color: #7e22ce !important; }
		
		.bg-purple { background-color: var(--purple-custom) !important; }
		.text-purple { color: var(--purple-custom) !important; }

		/* =========================================
		   6. REKAAN JADUAL KECIL & JADUAL NEGERI
		   ========================================= */
		.table-sm th, .table-sm td { font-size: 0.85rem; }
		.table-ajk th, .table-ajk td { font-size: 0.8rem; padding: 8px 10px; }

		.table-negeri {
			border-collapse: separate !important;
			border-spacing: 0 !important; 
			width: 100% !important;
			table-layout: fixed;
			margin-bottom: 0;
		}

		.table-negeri th:nth-child(1), .table-negeri td:nth-child(1) { width: 40%; }
		.table-negeri th:nth-child(2), .table-negeri td:nth-child(2) { width: 20%; }
		.table-negeri th:nth-child(3), .table-negeri td:nth-child(3) { width: 20%; }
		.table-negeri th:nth-child(4), .table-negeri td:nth-child(4) { width: 20%; }

		.table-negeri th {
			background-color: #3b5998 !important; 
			color: white !important;
			font-size: 0.75rem; 
			font-weight: 800;
			text-transform: uppercase;
			padding: 15px 10px !important;
			border: none !important;
			vertical-align: middle;
			position: sticky;
			top: 0;
			z-index: 2;
		}
		
		.table-negeri td {
			font-size: 0.8rem; 
			padding: 10px !important; 
			border-bottom: 1px solid #f1f5f9;
			vertical-align: middle;
			background-color: #ffffff; 
			word-wrap: break-word;
		}
		
		.table-negeri tbody tr:hover td { 
			background-color: #eff6ff !important; 
		}
		
		.state-name-cell { font-weight: 800; color: #1e293b; }
		.tengah-divider { border-right: 1px solid #cbd5e1 !important; }

		/* =========================================
		   7. UTILITI TAMBAHAN, MODAL & SEARCH DROPDOWN
		   ========================================= */
		.month-divider {
			position: sticky; top: 70px; z-index: 10;
			background: var(--body-bg); padding: 20px 0 10px 0;
			border-bottom: 2px solid var(--light-blue); margin-bottom: 25px;
		}

		.footer-blue { 
			background-color: var(--primary-blue); 
			color: #cbd5e1; 
			margin-top: 50px; 
		}
		
		/* Modal & Text Truncate */
		.jawapan-text { display: inline-block; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; vertical-align: middle; transition: all 0.2s; }
		.jawapan-expanded { white-space: normal !important; max-width: 100% !important; display: block !important; background-color: #f1f5f9; padding: 12px; border-radius: 8px; margin-top: 8px; border-left: 4px solid var(--accent-blue); font-size: 0.9rem; color: #334155; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02); }
		#basic-1 tbody tr { cursor: pointer; }
		#basic-1 tbody tr:hover { background-color: #f8fafc !important; }

		/* Search Dropdown Style */
		.search-results {
			position: absolute; top: 100%; left: 0; right: 0; z-index: 1050;
			background: white; border: 1px solid #e2e8f0; border-radius: 10px;
			box-shadow: 0 10px 25px rgba(0,0,0,0.1); max-height: 250px; overflow-y: auto;
			display: none;
		}
		.search-results .list-group-item { border: none; border-bottom: 1px solid #f1f5f9; cursor: pointer; }
		.search-results .list-group-item:hover { background-color: var(--light-blue); }

		/* =========================================
		   8. LOGIK WORKSPACE SCROLL (VERSI SELAMAT)
		   ========================================= */
		
		@media (min-width: 992px) {
			/* 1. BIARKAN BODY BEBAS! Jangan sekat overflow dia */
			body { overflow-x: hidden; }
			
			/* 2. HADKAN KETINGGIAN KOLUM SAHAJA */
			.left-scroll, .right-scroll { 
				/* Kita set max-height tolak navbar (anggaran 90px) */
				max-height: calc(100vh - 90px); 
				overflow-y: auto; 
				padding-top: 15px; 
				padding-right: 15px; 
				padding-bottom: 20px; 
			}
			
			/* 3. DESIGN SCROLLBAR CANTIK */
			.left-scroll::-webkit-scrollbar, .right-scroll::-webkit-scrollbar { width: 6px; }
			.left-scroll::-webkit-scrollbar-track, .right-scroll::-webkit-scrollbar-track { background: transparent; }
			.left-scroll::-webkit-scrollbar-thumb, .right-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
			.left-scroll::-webkit-scrollbar-thumb:hover, .right-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
		}
		/* Warna khas Teal untuk teks & ikon */
        .text-teal {
            color: #009688 !important; /* Kod warna Material Teal */
        }
        
        /* Gaya bila Tab tu AKTIF (Ditekan) */
        .nav-tabs.custom-teal-tabs .nav-link.active {
            color: #009688 !important;
            border-color: #dee2e6 #dee2e6 #fff !important;
            /* Tambah sikit garisan atas supaya nampak lebih menonjol (Opsional) */
            border-top: 3px solid #009688 !important; 
        }
        
        /* Gaya bila mouse lalu atas Tab (Hover) */
        .nav-tabs.custom-teal-tabs .nav-link:hover:not(.active) {
            color: #00796b !important; /* Warna teal gelap sikit bila hover */
            border-color: transparent transparent #dee2e6;
        }
		
		/* Animasi Highlight Kad Carian */
        @keyframes pulseGlow {
            0% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.7); border-color: #0d6efd; }
            70% { box-shadow: 0 0 0 15px rgba(13, 110, 253, 0); border-color: rgba(13, 110, 253, 0.5); }
            100% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0); border-color: transparent; }
        }
        .highlight-pulse {
            animation: pulseGlow 1.5s ease-out 2; /* Kelip 2 kali */
            border: 2px solid #0d6efd !important;
            border-radius: 0.375rem;
            transition: all 0.3s;
        }

		 
    /* Layout Calendar */
    .calendar-container {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
    }

    .calendar-day {
        background: var(--card-bg);
        border: none;
        border-radius: 12px;
        min-height: 180px;
        padding: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
		 display:flex;
    	flex-direction:column;
    }

    .calendar-day:hover {
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .day-number { font-weight: 700; font-size: 0.9rem; color: var(--text-muted); }

    /* Tags Modern */
    .person {
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 6px;
        margin-bottom: 4px;
        font-weight: 500;
        display: flex;
        justify-content: space-between;
    }

    .hadir { background: #f1f5f9; color: #334155; }
    .cuti { background: #ede9fe; color: #5b21b6; }
    .wfh { background: #ffedd5; color: #9a3412; }
    .ganti { background: #e0e7ff; color: #3730a3; }

    /* Warning Modern */
    .warning {
        margin-top: 8px;
        background: #fee2e2;
        color: #991b1b;
        border-radius: 6px;
        padding: 4px;
        font-size: 0.65rem;
        text-align: center;
        font-weight: 700;
    }

    .backup-box {
        margin-top: 5px;
        background: #dcfce7;
        color: #166534;
        border-radius: 6px;
        padding: 4px;
        font-size: 0.65rem;
        font-weight: 700;
    }

    /* Header Bulan */
    .month-title { font-size: 1.25rem; font-weight: 800; color: #1e293b; }
    .month-note { background: #eef2ff; color: #4338ca; font-weight: 600; padding: 2px 10px; font-size: 0.7rem; }
    
    .day-label { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; color: #94a3b8; padding-bottom: 5px; }

    


   .today{

		position:relative;

		border:3px solid #2563eb !important;

		box-shadow:
			0 0 0 5px rgba(37,99,235,.12);

		transform:scale(1.02);

		z-index:10;
	}

	.today::before{

		content:"📍 TODAY";

		position:absolute;

		top:8px;

		right:8px;

		background:#2563eb;

		color:white;

		font-size:10px;

		font-weight:700;

		padding:4px 8px;

		border-radius:999px;
	}

    /* Navigasi ringkas */
    .nav-link-custom {
        background: #fff;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--primary);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    /* Styling Hari Cuti Umum yang menonjol */
   /* Kuning lembut untuk Cuti Umum & Sabtu */
    .cuti-umum, .weekend {
        background: #fefce8 !important; /* Yellow-50 */
        border: 1px solid #fef08a !important;
    }

    /* Hijau border untuk hari yang ada 4 orang ke atas */
    .overstaffed {
        border: 2px solid #22c55e !important; /* Green-500 */
        box-shadow: 0 0 8px rgba(34, 197, 94, 0.2) !important;
    }

    .cuti-umum .day-number {
        color: #9f1239 !important;
        font-weight: 800;
    }

    .cuti-umum .cuti-title {
        color: #e11d48;
        font-size: 0.65rem;
        text-transform: uppercase;
    }

    /* Styling untuk Hari Bertugas (Ahad) */
    .duty-highlight {
        background: #f0fdfa !important; /* Hijau pastel sangat lembut */
        border: 2px solid #0f766e !important; /* Border hijau tua */
    }

    .duty-box {
        background: #0f766e !important;
        color: white !important;
        border-radius: 8px;
        padding: 6px;
        margin-top: 10px;
    }

    .duty-label {
        font-size: 0.6rem !important;
        color: #ccfbf1 !important; /* Warna text hijau cerah */
        letter-spacing: 0.5px;
    }

    .nav-link-custom {
        background: #fff;
        padding: 10px 20px;
        border-radius: 12px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--text-main);
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        transition: all 0.2s ease;
    }

    .nav-link-custom:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
    }

	.duty-hero{

		background:
			linear-gradient(
				135deg,
				#2563eb 0%,
				#1d4ed8 50%,
				#1e40af 100%
			);

		border-radius:24px;

		padding:24px;

		color:white;

		position:relative;

		overflow:hidden;

		box-shadow:
			0 20px 40px rgba(37,99,235,.25);
	}

	/* decorative glow */
	.duty-hero::before{

		content:"";

		position:absolute;

		width:250px;
		height:250px;

		right:-80px;
		top:-80px;

		border-radius:50%;

		background:
			rgba(255,255,255,.08);
	}

	.duty-hero::after{

		content:"";

		position:absolute;

		width:150px;
		height:150px;

		right:30px;
		bottom:-70px;

		border-radius:50%;

		background:
			rgba(255,255,255,.05);
	}

	.duty-label{

		font-size:.75rem;

		letter-spacing:2px;

		font-weight:700;

		opacity:.85;
	}

	.duty-date{

		font-size:1rem;

		margin-top:8px;

		opacity:.9;
	}

	.duty-name{

		font-size:2rem;

		font-weight:800;

		margin-top:6px;

		line-height:1.1;
	}

	.duty-icon{

		font-size:4rem;

		opacity:.15;

		line-height:1;
	}
	.month-nav{

		position:sticky;

		top:95px;

		z-index:50;
	}

	.avatar-mini{

		width:22px;

		height:22px;

		border-radius:50%;

		background:#2563eb;

		color:white;

		display:inline-flex;

		align-items:center;

		justify-content:center;

		font-size:11px;

		font-weight:700;

		margin-right:6px;
	}

	.availability-bar{

		height:6px;

		background:#e5e7eb;

		border-radius:30px;

		overflow:hidden;

		margin-top:4px;
	}

	.availability-fill{

		height:100%;

		background:
			linear-gradient(
				90deg,
				#22c55e,
				#16a34a
			);
	}

	.activity-item{

    display:flex;

    gap:10px;

    align-items:center;

    padding:10px 0;

    border-bottom:1px solid #f1f5f9;
	}

	.activity-item:last-child{

		border-bottom:none;
	}

	.activity-icon{

		width:36px;

		height:36px;

		border-radius:50%;

		background:#f8fafc;

		display:flex;

		align-items:center;

		justify-content:center;
	}

	.sidebar-card{

    background:#fff;

    border-radius:20px;

    padding:18px;

    margin-bottom:16px;

    box-shadow:
        0 5px 20px rgba(0,0,0,.05);
	}

	.sidebar-title{

		font-size:.75rem;

		font-weight:800;

		letter-spacing:1px;

		text-transform:uppercase;

		color:#94a3b8;

		margin-bottom:15px;
	}

	.btn-ooo{

		border-radius:14px;

		padding:12px;

		font-weight:700;
	}

	.stat-row{

		display:flex;

		justify-content:space-between;

		align-items:center;

		padding:10px 0;

		border-bottom:1px solid #f1f5f9;
	}

	.stat-row:last-child{

		border-bottom:none;
	}

	.stat-value{

		font-size:1.2rem;

		font-weight:800;
	}


	.calendar-day{

    transition:
        all .25s ease;
	}

	.calendar-day:hover{

		transform:
			translateY(-4px);

		box-shadow:
			0 15px 30px rgba(0,0,0,.08);
	}

		.quick-add{

		position:absolute;

		top:8px;

		right:8px;

		width:22px;

		height:22px;

		border-radius:50%;

		background:white;

		display:none;

		align-items:center;

		justify-content:center;

		font-size:11px;

		box-shadow:
			0 2px 10px rgba(0,0,0,.1);
	}

	.calendar-day:hover .quick-add{

		display:flex;
	}

	.person-click{

		cursor:pointer;
	}

	.today-focus{

		animation:
			pulseToday
			1s ease-in-out
			2;
	}

	@keyframes pulseToday{

		0%{

			transform:scale(1);
		}

		50%{

			transform:scale(1.06);

			box-shadow:
				0 0 0 10px
				rgba(59,130,246,.15);
		}

		100%{

			transform:scale(1);
		}
	}

	#btnToday{

		font-weight:700;

		min-width:140px;

		transition:.2s;
	}

	#btnToday:hover{

		transform:translateY(-2px);

		box-shadow:
			0 10px 25px rgba(37,99,235,.25);
	}
	
	/* =========================================================
           TOP SUMMARY CARDS
           ========================================================= */
        /* =========================================================
   TOP SUMMARY CARDS
   ========================================================= */
.top-summary-card{
    --card-accent:#2563eb;
    --card-accent-soft:rgba(37,99,235,.14);
    --card-border:rgba(37,99,235,.24);
    --card-bg-start:#eff6ff;
    --card-bg-end:#dbeafe;

    height:100%;
    min-height:138px;
    padding:1rem 1.1rem;
    border-radius:20px;
    border:1px solid var(--card-border);
    background:linear-gradient(
        135deg,
        var(--card-bg-start) 0%,
        var(--card-bg-end) 100%
    );
    box-shadow:
        0 10px 24px rgba(15,23,42,.06),
        inset 0 1px 0 rgba(255,255,255,.72);
    position:relative;
    overflow:hidden;
    transition:
        transform .22s ease,
        box-shadow .22s ease,
        border-color .22s ease;
}

.top-summary-card:hover{
    transform:translateY(-4px);
    box-shadow:
        0 16px 30px rgba(15,23,42,.11),
        inset 0 1px 0 rgba(255,255,255,.78);
}

/* Garisan accent kiri */
.top-summary-card::before{
    content:'';
    position:absolute;
    inset:0 auto 0 0;
    width:5px;
    background:var(--card-accent);
}

/* Decorative glow */
.top-summary-card::after{
    content:'';
    position:absolute;
    width:150px;
    height:150px;
    border-radius:50%;
    right:-58px;
    top:-62px;
    background:rgba(255,255,255,.38);
}

/* =========================================================
   CARD THEMES
   ========================================================= */
.top-summary-card.card-duty{
    --card-accent:#c7d2fe;
    --card-border:rgba(99,102,241,.52);
    --card-bg-start:#2563eb;
    --card-bg-end:#4f46e5;
    color:#ffffff;
    box-shadow:
        0 14px 28px rgba(37,99,235,.22),
        inset 0 1px 0 rgba(255,255,255,.18);
}

.top-summary-card.card-tomorrow{
    --card-accent:#0891b2;
    --card-accent-soft:rgba(8,145,178,.14);
    --card-border:rgba(8,145,178,.28);
    --card-bg-start:#ecfeff;
    --card-bg-end:#cffafe;
    color:#0f172a;
}

.top-summary-card.card-available{
    --card-accent:#16a34a;
    --card-accent-soft:rgba(22,163,74,.14);
    --card-border:rgba(22,163,74,.28);
    --card-bg-start:#f0fdf4;
    --card-bg-end:#dcfce7;
    color:#0f172a;
}

/* =========================================================
   CONTENT TYPOGRAPHY
   ========================================================= */
.top-summary-label{
    display:inline-flex;
    align-items:center;
    gap:.3rem;
    padding:.27rem .52rem;
    border-radius:999px;
    background:rgba(255,255,255,.52);
    color:#334155;
    font-size:.68rem;
    font-weight:800;
    letter-spacing:.09em;
    line-height:1;
}

.top-summary-label iconify-icon{
    font-size:.92rem;
}

.card-duty .top-summary-label{
    background:rgba(255,255,255,.16);
    color:#ffffff;
}

.top-summary-date{
    margin-top:.62rem;
    color:#475569;
    font-size:.78rem;
    font-weight:700;
}

.card-duty .top-summary-date{
    color:rgba(255,255,255,.84);
}

.top-summary-main{
    margin-top:.22rem;
    color:#0f172a;
    font-size:1.5rem;
    line-height:1.15;
    font-weight:900;
    letter-spacing:-.02em;
}

.card-duty .top-summary-main{
    color:#ffffff;
}

.top-summary-meta{
    margin-top:.48rem;
    color:#64748b;
    font-size:.72rem;
    font-weight:700;
}

.top-summary-detail{
    margin-top:.62rem;
    max-width:90%;
    color:#475569;
    font-size:.76rem;
    line-height:1.5;
    font-weight:600;
}

/* =========================================================
   ICON BOX
   ========================================================= */
.top-summary-icon{
    display:flex;
    align-items:center;
    justify-content:center;
    flex:0 0 auto;
    width:48px;
    height:48px;
    border-radius:16px;
    background:var(--card-accent-soft);
    color:var(--card-accent);
    line-height:1;
    box-shadow:inset 0 1px 0 rgba(255,255,255,.55);
    position:relative;
    z-index:1;
}

.top-summary-icon iconify-icon{
    font-size:1.62rem;
}

.card-duty .top-summary-icon{
    background:rgba(255,255,255,.2);
    color:#ffffff;
}

/* =========================================================
   HADIR ESOK STAFF CHIPS
   ========================================================= */
.tomorrow-staff-list{
    display:flex;
    flex-wrap:wrap;
    gap:.48rem;
    margin-top:.72rem;
}

.tomorrow-staff-chip{
    display:inline-flex;
    align-items:center;
    gap:.38rem;
    padding:.48rem .78rem;
    border-radius:999px;
    border:1px solid rgba(8,145,178,.26);
    background:rgba(255,255,255,.84);
    color:#155e75;
    font-size:.8rem;
    font-weight:800;
    box-shadow:
        0 3px 8px rgba(8,145,178,.08),
        inset 0 1px 0 rgba(255,255,255,.9);
}

.tomorrow-staff-chip iconify-icon{
    color:#0891b2;
    font-size:1rem;
}

.tomorrow-staff-chip.is-backup{
    border-color:rgba(22,163,74,.28);
    background:rgba(240,253,244,.9);
    color:#166534;
}

.tomorrow-staff-chip.is-backup iconify-icon{
    color:#16a34a;
}

.tomorrow-staff-chip small{
    padding:.12rem .34rem;
    border-radius:999px;
    background:rgba(22,163,74,.14);
    color:#15803d;
    font-size:.56rem;
    font-weight:900;
    letter-spacing:.04em;
    text-transform:uppercase;
}

/* =========================================================
   AVAILABLE CHIPS
   ========================================================= */
.available-chip{
    display:inline-flex;
    align-items:center;
    gap:.28rem;
    margin:.25rem .25rem 0 0;
    padding:.34rem .58rem;
    border-radius:999px;
    border:1px solid rgba(22,163,74,.2);
    background:rgba(255,255,255,.7);
    color:#166534;
    font-size:.7rem;
    font-weight:800;
}

.available-chip iconify-icon{
    color:#16a34a;
    font-size:.9rem;
}

/* =========================================================
   EMPTY STATE
   ========================================================= */
.staff-empty-note{
    display:inline-flex;
    align-items:center;
    gap:.42rem;
    margin-top:.7rem;
    padding:.46rem .68rem;
    border-radius:12px;
    border:1px solid rgba(100,116,139,.14);
    background:rgba(255,255,255,.72);
    color:#475569;
    font-size:.76rem;
    font-weight:700;
}

.staff-empty-note iconify-icon{
    font-size:1rem;
}

/* =========================================================
   SIDEBAR ICONIFY
   ========================================================= */
.sidebar-stat-label{
    display:flex;
    align-items:center;
    gap:.48rem;
}

.sidebar-stat-label iconify-icon{
    width:18px;
    color:#64748b;
    font-size:1.08rem;
}

.activity-icon iconify-icon{
    color:#64748b;
    font-size:1.1rem;
}

/* =========================================================
   RESPONSIVE
   ========================================================= */
@media (max-width:991.98px){
    .top-summary-card{
        min-height:145px;
    }
}

.warning iconify-icon{
    margin-right:.18rem;
    font-size:.82rem;
    vertical-align:-.12rem;
}

	</style>
	
	
</head>
<body>
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <div class="loader-wrapper">
        <div class="loader"></div>
    </div>

    <div class="top-title-row d-none d-md-block">
        <div class="container-fluid px-4 d-flex justify-content-between align-items-center">
            <div>
                <i class="fa fa-info-circle me-1"></i> Sistem Meja Aduan Bersepadu v2.0
            </div>
            <div>
                <i class="fa fa-map-marker me-1"></i> Ibu Pejabat | <i class="fa fa-envelope ms-3 me-1"></i> -@OE.com.my
            </div>
        </div>
    </div>

   <nav class="navbar navbar-expand-lg modern-topbar py-3 sticky-top">
        <div class="container-fluid px-4">
            
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="index2.php" style="color: var(--primary-blue);">
                <div class="text-white rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; background-color: var(--accent-blue);">
                    <i class="icofont icofont-headphone-alt fs-5"></i>
                </div>
                <span class="fs-4">Meja<span class="text-dark">Bantuan</span><span class="text-info"> PLUS+</span></span>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#topMenu">
                <span class="navbar-toggler-icon"><i class="fa fa-bars text-dark fs-4"></i></span>
            </button>

            <div class="collapse navbar-collapse" id="topMenu">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-1 ms-lg-5">
					<li class="nav-item">
						<a class="nav-link d-flex align-items-center gap-2 nav-akan-datang" href="">
							<i class="icofont icofont-dashboard-web fs-5"></i> Dashboard
						</a>
					</li>

					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle d-flex align-items-center gap-2 nav-akan-datang" href="#" role="button" data-bs-toggle="dropdown">
							<i class="icofont icofont-ui-folder fs-5"></i> Pengurusan Aduan
						</a>
						<ul class="dropdown-menu ">
							<li><a class="dropdown-item" href="senarai.php">Senarai Aduan</a></li>
							<li><a class="dropdown-item" href="log.php">Log Tugas Saya</a></li>
						</ul>
					</li>

					<li class="nav-item">
						<a class="nav-link d-flex align-items-center gap-2 <?php echo ($page_active == 'meja_bantuan') ? 'active' : ''; ?>" href="meja_bantuan.php">
							<i class="icofont chart-line fs-5"></i> Statistik Meja Bantuan Harian
						</a>
					</li>
					
					<li class="nav-item">
						<a class="nav-link d-flex align-items-center gap-2 <?php echo ($page_active == 'aduan_harian') ? 'active' : ''; ?>" href="aduan_harian.php">
							<i class="icofont chart-line fs-5"></i> Pencapaian Harian
						</a>
					</li>
					
					<li class="nav-item">
						<a class="nav-link d-flex align-items-center gap-2" href="jadual_bertugas.php">
							<i class="icofont chart-line fs-5"></i> Jadual Bertugas
						</a>
					</li>
				</ul>

                <div class="d-flex align-items-center gap-4 mt-3 mt-lg-0 border-start ps-lg-4">
                    <div class="position-relative" style="cursor: pointer;">
                        <i class="icofont icofont-notification text-secondary fs-4"></i>
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-white rounded-circle">
                            <span class="visually-hidden">Notifikasi Baru</span>
                        </span>
                    </div>
                    
                    <div class="d-flex align-items-center gap-2" style="cursor: pointer;">
                        <img src="../assets/images/dashboard/profile.jpg" alt="Profile" class="rounded-circle shadow-sm" style="width: 45px; height: 45px;">
                        <div class="d-none d-md-block lh-sm">
                            <h6 class="mb-0 fw-bold text-dark">Hafiz</h6>
                            <span class="text-muted" style="font-size: 0.8rem;">Helpdesk OE</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
