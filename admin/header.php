<?php
session_start();

// Verificar si el administrador está logueado
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: index.php');
    exit;
}

require_once 'config/DataBase.php';

$db = new Database();
$con = $db->connect();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Panel de Administración - Hidrosistemas">
    <meta name="author" content="">

    <title>Panel de Administración - Hidrosistemas</title>

    <!-- Bootstrap 5.3.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6.4.2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Custom fonts for this template-->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Estilos SB Admin 2 Simplificados -->
    <style>
        /* Estilos base SB Admin 2 */
        :root {
            --primary: #4e73df;
            --success: #1cc88a;
            --info: #36b9cc;
            --warning: #f6c23e;
            --danger: #e74a3b;
            --secondary: #858796;
            --light: #f8f9fc;
            --dark: #5a5c69;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fc;
        }
        
        #wrapper {
            display: flex;
        }
        
        #content-wrapper {
            width: 100%;
            overflow-x: hidden;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 14rem;
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary) 10%, #224abe 100%);
            transition: all 0.3s;
        }
        
        .sidebar .nav-item {
            margin-bottom: 0.2rem;
        }
        
        .sidebar .nav-item .nav-link {
            color: rgba(255,255,255,.8);
            padding: 1rem;
            display: flex;
            align-items: center;
        }
        
        .sidebar .nav-item .nav-link i {
            font-size: 0.85rem;
            margin-right: 0.5rem;
        }
        
        .sidebar .nav-item .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,.1);
        }
        
        .sidebar .nav-item .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,.1);
        }
        
        .sidebar .sidebar-brand {
            height: 4.375rem;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 800;
            padding: 1.5rem 1rem;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            z-index: 1;
            color: #fff;
        }
        
        .sidebar .sidebar-brand .sidebar-brand-icon i {
            font-size: 2rem;
        }
        
        .sidebar .sidebar-brand .sidebar-brand-text {
            display: inline;
        }
        
        .sidebar .sidebar-divider {
            margin: 0 1rem 1rem;
            border-top: 1px solid rgba(255,255,255,.15);
        }
        
        .sidebar-heading {
            text-align: left;
            padding: 0 1rem;
            font-weight: 800;
            font-size: 0.65rem;
            color: rgba(255,255,255,.4);
        }
        
        /* Topbar Styles */
        .topbar {
            height: 4.375rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .topbar #sidebarToggleTop {
            height: 2.5rem;
            width: 2.5rem;
        }
        
        .topbar .nav-item .nav-link {
            height: 4.375rem;
            display: flex;
            align-items: center;
            padding: 0 0.75rem;
        }
        
        .topbar .nav-item .nav-link:focus {
            outline: none;
        }
        
        .topbar .nav-item .nav-link .img-profile {
            height: 2rem;
            width: 2rem;
        }
        
        /* Content Styles */
        .container-fluid {
            padding: 0 1.5rem;
        }
        
        /* Card Styles */
        .card {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: 1px solid #e3e6f0;
        }
        
        .card .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
        }
        
        /* Border Colors */
        .border-left-primary {
            border-left: 0.25rem solid var(--primary) !important;
        }
        
        .border-left-success {
            border-left: 0.25rem solid var(--success) !important;
        }
        
        .border-left-info {
            border-left: 0.25rem solid var(--info) !important;
        }
        
        .border-left-warning {
            border-left: 0.25rem solid var(--warning) !important;
        }
        
        /* Text Colors */
        .text-xs {
            font-size: 0.7rem;
        }
        
        .text-gray-100 { color: #f8f9fc !important; }
        .text-gray-200 { color: #eaecf4 !important; }
        .text-gray-300 { color: #dddfeb !important; }
        .text-gray-400 { color: #d1d3e2 !important; }
        .text-gray-500 { color: #b7b9cc !important; }
        .text-gray-600 { color: #858796 !important; }
        .text-gray-700 { color: #6e707e !important; }
        .text-gray-800 { color: #5a5c69 !important; }
        .text-gray-900 { color: #3a3b45 !important; }
        
        /* Background Colors */
        .bg-gradient-primary {
            background-color: var(--primary);
            background-image: linear-gradient(180deg, var(--primary) 10%, #224abe 100%);
        }
        
        /* Progress Bar */
        .progress-sm {
            height: 0.5rem;
        }
        
        /* Chart Container */
        .chart-area {
            position: relative;
            height: 10rem;
            width: 100%;
        }
        
        .chart-pie {
            position: relative;
            height: 15rem;
            width: 100%;
        }
        
        /* Scroll to top button */
        .scroll-to-top {
            position: fixed;
            right: 1rem;
            bottom: 1rem;
            display: none;
            width: 2.75rem;
            height: 2.75rem;
            text-align: center;
            color: #fff;
            background: rgba(52, 58, 64, 0.5);
            line-height: 46px;
        }
        
        .scroll-to-top:focus, .scroll-to-top:hover {
            color: white;
        }
        
        .scroll-to-top:hover {
            background: #343a40;
        }
        
        .scroll-to-top i {
            font-weight: 800;
        }
        
        /* Footer */
        .sticky-footer {
            flex-shrink: 0;
        }
        
        /* Responsive */
        @media (min-width: 768px) {
            .sidebar.toggled {
                margin-left: -14rem;
            }
            
            #content-wrapper {
                min-width: 0;
                width: 100%;
            }
        }
        
        @media (max-width: 767.98px) {
            .sidebar {
                margin-left: -14rem;
            }
            
            .sidebar.toggled {
                margin-left: 0;
            }
        }
        
        /* Custom Hidrosistemas Styles */
        .sidebar {
            background: linear-gradient(135deg, #2c3e50, #34495e);
        }
        
        .sidebar .nav-item .nav-link {
            border-radius: 0.35rem;
            margin: 0.2rem 0.5rem;
            transition: all 0.3s;
        }
        
        .sidebar .nav-item .nav-link:hover {
            transform: translateX(5px);
        }
    </style>
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="inicio.php">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-tools"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Hidrosistemas</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Items -->
            <li class="nav-item active">
                <a class="nav-link" href="inicio.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Gestión
            </div>

            <!-- Productos -->
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-box"></i>
                    <span>Productos</span>
                </a>
            </li>

            <!-- Categorías -->
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-tags"></i>
                    <span>Categorías</span>
                </a>
            </li>

            <!-- Clientes -->
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Clientes</span>
                </a>
            </li>

            <!-- Usuarios -->
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-user-cog"></i>
                    <span>Usuarios</span>
                </a>
            </li>

            <!-- Compras -->
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-shopping-cart"></i>
                    <span>Compras</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <i class="fas fa-user-shield me-1"></i>
                                    <?php echo htmlspecialchars($_SESSION['admin_name']); ?>
                                </span>
                                <img class="img-profile rounded-circle" src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['admin_name']); ?>&background=3498db&color=fff" width="32" height="32">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Perfil
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Configuración
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Cerrar Sesión
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">