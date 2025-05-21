<<<<<<< HEAD
<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Aplikasi Zakat Fitrah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
    body {
        min-height: 100vh;
        background: url('../assets/img/bg-zakat.jpg') no-repeat center center fixed;
        background-size: cover;
        background-color: #f0f4fa;
        margin: 0;
        padding: 0;
    }
    .sidebar {
        min-height: 100vh;
        width: 260px;
        background: #198754;
        color: #fff;
        position: fixed;
        left: 0;
        top: 0;
        display: flex;
        flex-direction: column;
        box-shadow: 2px 0 16px rgba(0,0,0,0.08);
        z-index: 2000;
        transition: transform 0.3s;
    }
    .sidebar.collapsed {
        transform: translateX(-100%);
    }
    .sidebar .sidebar-header {
        padding: 24px 24px 12px 24px;
        font-size: 1.3rem;
        font-weight: bold;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        gap: 10px;
        justify-content: space-between;
        position: relative;
    }
    .sidebar .sidebar-header i.bi-gem {
        font-size: 1.7rem;
        color: #fff176;
    }
    .sidebar .sidebar-toggle {
        background: transparent;
        color: #fff;
        border: none;
        font-size: 1.7rem;
        margin-left: 8px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        right: 0;
        top: 0;
        height: 100%;
        padding: 0 16px;
        z-index: 2;
        transition: color 0.2s;
    }
    .sidebar.collapsed .sidebar-toggle {
        display: none;
    }
    .floating-toggle {
        display: none;
        position: fixed;
        top: 24px;
        left: 24px;
        z-index: 2100;
        background: #198754;
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 44px;
        height: 44px;
        align-items: center;
        justify-content: center;
        font-size: 1.7rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: background 0.2s;
    }
    .floating-toggle:hover {
        background: #157347;
        color: #fff;
    }
    .sidebar.collapsed ~ .floating-toggle {
        display: flex;
    }
    .sidebar .nav-link {
        color: #e0ffe0;
        font-size: 0.97rem;
        padding: 10px 24px;
        border-radius: 8px;
        margin: 2px 8px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: background 0.2s, color 0.2s;
        font-weight: 500;
        letter-spacing: 0.2px;
    }
    .sidebar .nav-link.active,
    .sidebar .nav-link:hover {
        background: #157347;
        color: #fff;
    }
    .sidebar .sidebar-footer {
        margin-top: auto;
        padding: 20px;
        border-top: 1px solid #157347;
        display: flex;
        align-items: center;
        gap: 12px;
        background: #198754;
    }
    .sidebar .sidebar-footer img {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
    }
    .sidebar .sidebar-footer .user-info {
        font-size: 0.90rem;
        color: #fff;
        line-height: 1.2;
    }
    .main-content {
        margin-left: 260px;
        padding: 0;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: margin-left 0.3s;
    }
    .main-content.expanded {
        margin-left: 0;
    }
    .welcome-card {
        background: rgba(255,255,255,0.92);
        border-radius: 16px;
        padding: 48px 32px;
        box-shadow: 0 4px 32px rgba(0,0,0,0.08);
        text-align: center;
        max-width: 480px;
        width: 100%;
        animation: fadeIn 1s;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(40px);}
        to { opacity: 1; transform: translateY(0);}
    }
    @media (max-width: 900px) {
        .sidebar {
            transform: translateX(-100%);
        }
        .sidebar.show {
            transform: translateX(0);
        }
        .main-content {
            margin-left: 0;
        }
    }
    </style>
</head>
<body>
<div class="sidebar" id="sidebarNav">
    <div class="sidebar-header">
        <span><i class="bi bi-gem"></i> Zakat Fitrah</span>
        <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
            <i class="bi bi-list"></i>
        </button>
    </div>
    <nav class="nav flex-column mt-2">
        <a class="nav-link" href="../modules/muzakki.php"><i class="bi bi-people"></i> Master Data Muzakki</a>
        <a class="nav-link" href="../modules/kategori_mustahik.php"><i class="bi bi-tags"></i> Master Data Kategori Mustahik</a>
        <a class="nav-link" href="../modules/bayarzakat.php"><i class="bi bi-cash-stack"></i> Pengumpulan Zakat</a>
        <a class="nav-link" href="../modules/mustahik_warga.php"><i class="bi bi-house-door"></i> Distribusi Zakat Warga</a>
        <a class="nav-link" href="../modules/mustahik_lainnya.php"><i class="bi bi-people-fill"></i> Distribusi Zakat Mustahik</a>
        <a class="nav-link" href="../modules/laporan.php"><i class="bi bi-file-earmark-bar-graph"></i> Laporan Zakat</a>
        <a class="nav-link" href="../pages/login.php" onclick="return confirm('Yakin mau logout?')"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </nav>
    <div class="sidebar-footer">
        <img src="../assets/img/user.png" alt="User">
        <div class="user-info">
            <div><?= htmlspecialchars($_SESSION['username']); ?></div>
            <small>Petugas Zakat</small>
        </div>
    </div>
</div>
<button class="floating-toggle" id="floatingSidebarToggle" aria-label="Show sidebar">
    <i class="bi bi-list"></i>
</button>
<div class="main-content" id="mainContent">
    <div class="welcome-card">
        <h2 class="mb-3">Selamat Datang, <span class="text-success"><?= htmlspecialchars($_SESSION['username']); ?></span>!</h2>
        <p class="lead mb-4">Silakan pilih menu di samping untuk mengelola data zakat fitrah.</p>
        <div class="alert alert-success" role="alert" style="opacity:0.85;">
            <b>Tips:</b> Gunakan menu sidebar untuk mengakses fitur aplikasi zakat.
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const sidebar = document.getElementById('sidebarNav');
const toggleBtn = document.getElementById('sidebarToggle');
const floatingToggle = document.getElementById('floatingSidebarToggle');
const mainContent = document.getElementById('mainContent');

function openSidebar() {
    sidebar.classList.remove('collapsed');
    mainContent.classList.remove('expanded');
}
function closeSidebar() {
    sidebar.classList.add('collapsed');
    mainContent.classList.add('expanded');
}
function toggleSidebar() {
    if (sidebar.classList.contains('collapsed')) {
        openSidebar();
    } else {
        closeSidebar();
    }
}
toggleBtn.addEventListener('click', closeSidebar);
floatingToggle.addEventListener('click', openSidebar);

// Responsive: close sidebar by default on mobile
function handleResize() {
    if (window.innerWidth <= 900) {
        closeSidebar();
    } else {
        openSidebar();
    }
}
window.addEventListener('resize', handleResize);
document.addEventListener('DOMContentLoaded', handleResize);

// Optional: close sidebar when clicking outside on mobile
document.addEventListener('click', function(e) {
    if (window.innerWidth <= 900 && !sidebar.classList.contains('collapsed')) {
        if (!sidebar.contains(e.target) && !floatingToggle.contains(e.target)) {
            closeSidebar();
        }
    }
});
</script>
</body>
</html>
=======
<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Aplikasi Zakat Fitrah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
    body {
        min-height: 100vh;
        background: url('../assets/img/bg-zakat.jpg') no-repeat center center fixed;
        background-size: cover;
        background-color: #f0f4fa;
        margin: 0;
        padding: 0;
    }
    .sidebar {
        min-height: 100vh;
        width: 260px;
        background: #198754;
        color: #fff;
        position: fixed;
        left: 0;
        top: 0;
        display: flex;
        flex-direction: column;
        box-shadow: 2px 0 16px rgba(0,0,0,0.08);
        z-index: 2000;
        transition: transform 0.3s;
    }
    .sidebar.collapsed {
        transform: translateX(-100%);
    }
    .sidebar .sidebar-header {
        padding: 24px 24px 12px 24px;
        font-size: 1.3rem;
        font-weight: bold;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        gap: 10px;
        justify-content: space-between;
        position: relative;
    }
    .sidebar .sidebar-header i.bi-gem {
        font-size: 1.7rem;
        color: #fff176;
    }
    .sidebar .sidebar-toggle {
        background: transparent;
        color: #fff;
        border: none;
        font-size: 1.7rem;
        margin-left: 8px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        right: 0;
        top: 0;
        height: 100%;
        padding: 0 16px;
        z-index: 2;
        transition: color 0.2s;
    }
    .sidebar.collapsed .sidebar-toggle {
        display: none;
    }
    .floating-toggle {
        display: none;
        position: fixed;
        top: 24px;
        left: 24px;
        z-index: 2100;
        background: #198754;
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 44px;
        height: 44px;
        align-items: center;
        justify-content: center;
        font-size: 1.7rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: background 0.2s;
    }
    .floating-toggle:hover {
        background: #157347;
        color: #fff;
    }
    .sidebar.collapsed ~ .floating-toggle {
        display: flex;
    }
    .sidebar .nav-link {
        color: #e0ffe0;
        font-size: 0.97rem;
        padding: 10px 24px;
        border-radius: 8px;
        margin: 2px 8px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: background 0.2s, color 0.2s;
        font-weight: 500;
        letter-spacing: 0.2px;
    }
    .sidebar .nav-link.active,
    .sidebar .nav-link:hover {
        background: #157347;
        color: #fff;
    }
    .sidebar .sidebar-footer {
        margin-top: auto;
        padding: 20px;
        border-top: 1px solid #157347;
        display: flex;
        align-items: center;
        gap: 12px;
        background: #198754;
    }
    .sidebar .sidebar-footer img {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
    }
    .sidebar .sidebar-footer .user-info {
        font-size: 0.90rem;
        color: #fff;
        line-height: 1.2;
    }
    .main-content {
        margin-left: 260px;
        padding: 0;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: margin-left 0.3s;
    }
    .main-content.expanded {
        margin-left: 0;
    }
    .welcome-card {
        background: rgba(255,255,255,0.92);
        border-radius: 16px;
        padding: 48px 32px;
        box-shadow: 0 4px 32px rgba(0,0,0,0.08);
        text-align: center;
        max-width: 480px;
        width: 100%;
        animation: fadeIn 1s;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(40px);}
        to { opacity: 1; transform: translateY(0);}
    }
    @media (max-width: 900px) {
        .sidebar {
            transform: translateX(-100%);
        }
        .sidebar.show {
            transform: translateX(0);
        }
        .main-content {
            margin-left: 0;
        }
    }
    </style>
</head>
<body>
<div class="sidebar" id="sidebarNav">
    <div class="sidebar-header">
        <span><i class="bi bi-gem"></i> Zakat Fitrah</span>
        <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
            <i class="bi bi-list"></i>
        </button>
    </div>
    <nav class="nav flex-column mt-2">
        <a class="nav-link" href="../modules/muzakki.php"><i class="bi bi-people"></i> Master Data Muzakki</a>
        <a class="nav-link" href="../modules/kategori_mustahik.php"><i class="bi bi-tags"></i> Master Data Kategori Mustahik</a>
        <a class="nav-link" href="../modules/bayarzakat.php"><i class="bi bi-cash-stack"></i> Pengumpulan Zakat</a>
        <a class="nav-link" href="../modules/mustahik_warga.php"><i class="bi bi-house-door"></i> Distribusi Zakat Warga</a>
        <a class="nav-link" href="../modules/mustahik_lainnya.php"><i class="bi bi-people-fill"></i> Distribusi Zakat Mustahik</a>
        <a class="nav-link" href="../modules/laporan.php"><i class="bi bi-file-earmark-bar-graph"></i> Laporan Zakat</a>
        <a class="nav-link" href="../pages/login.php" onclick="return confirm('Yakin mau logout?')"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </nav>
    <div class="sidebar-footer">
        <img src="../assets/img/user.png" alt="User">
        <div class="user-info">
            <div><?= htmlspecialchars($_SESSION['username']); ?></div>
            <small>Petugas Zakat</small>
        </div>
    </div>
</div>
<button class="floating-toggle" id="floatingSidebarToggle" aria-label="Show sidebar">
    <i class="bi bi-list"></i>
</button>
<div class="main-content" id="mainContent">
    <div class="welcome-card">
        <h2 class="mb-3">Selamat Datang, <span class="text-success"><?= htmlspecialchars($_SESSION['username']); ?></span>!</h2>
        <p class="lead mb-4">Silakan pilih menu di samping untuk mengelola data zakat fitrah.</p>
        <div class="alert alert-success" role="alert" style="opacity:0.85;">
            <b>Tips:</b> Gunakan menu sidebar untuk mengakses fitur aplikasi zakat.
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const sidebar = document.getElementById('sidebarNav');
const toggleBtn = document.getElementById('sidebarToggle');
const floatingToggle = document.getElementById('floatingSidebarToggle');
const mainContent = document.getElementById('mainContent');

function openSidebar() {
    sidebar.classList.remove('collapsed');
    mainContent.classList.remove('expanded');
}
function closeSidebar() {
    sidebar.classList.add('collapsed');
    mainContent.classList.add('expanded');
}
function toggleSidebar() {
    if (sidebar.classList.contains('collapsed')) {
        openSidebar();
    } else {
        closeSidebar();
    }
}
toggleBtn.addEventListener('click', closeSidebar);
floatingToggle.addEventListener('click', openSidebar);

// Responsive: close sidebar by default on mobile
function handleResize() {
    if (window.innerWidth <= 900) {
        closeSidebar();
    } else {
        openSidebar();
    }
}
window.addEventListener('resize', handleResize);
document.addEventListener('DOMContentLoaded', handleResize);

// Optional: close sidebar when clicking outside on mobile
document.addEventListener('click', function(e) {
    if (window.innerWidth <= 900 && !sidebar.classList.contains('collapsed')) {
        if (!sidebar.contains(e.target) && !floatingToggle.contains(e.target)) {
            closeSidebar();
        }
    }
});
</script>
</body>
</html>
>>>>>>> 0e225968c8361bc689abad1d5af361922cc7bfb0
