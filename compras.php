<?php
session_start();

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['user_cliente'])) {
    header('Location: login.php');
    exit;
}

require_once 'config/database.php';
require_once 'clases/cliente_funciones.php';

$db = new Database();
$con = $db->conectar();

// OBTENER CATEGORÍAS PARA EL MENÚ
$sql_todas_categorias = $con->prepare("SELECT id, nombre, slug FROM categorias WHERE activo = 1 ORDER BY id ASC");
$sql_todas_categorias->execute();
$todas_categorias = $sql_todas_categorias->fetchAll(PDO::FETCH_ASSOC);

// Obtener las compras del cliente
$id_cliente = $_SESSION['user_cliente'];
$sql_compras = $con->prepare("SELECT id, fecha, total, estado, medio_pago FROM pedidos WHERE id_cliente = ? ORDER BY fecha DESC");
$sql_compras->execute([$id_cliente]);
$compras = $sql_compras->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Compras - Hidrosistemas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans:wght@400;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .compras-container {
            max-width: 800px;
            margin: 2rem auto;
        }
        .compra-card {
            border-left: 4px solid #3498db;
            transition: all 0.3s ease;
        }
        .compra-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .estado-completado {
            border-left-color: #28a745;
        }
        .estado-pendiente {
            border-left-color: #ffc107;
        }
        .estado-cancelado {
            border-left-color: #dc3545;
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>

    <!-- Contenido Principal -->
    <main class="container my-5">
        <div class="compras-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="fas fa-shopping-bag me-2"></i>Mis Compras</h1>
                <a href="index.php" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Seguir comprando
                </a>
            </div>

            <?php if (empty($compras)): ?>
                <div class="empty-state">
                    <i class="fas fa-shopping-bag"></i>
                    <h3>No tienes compras realizadas</h3>
                    <p class="lead">Cuando realices tu primera compra, aparecerá aquí.</p>
                    <a href="index.php" class="btn btn-primary mt-3">
                        <i class="fas fa-shopping-cart me-2"></i>Realizar mi primera compra
                    </a>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($compras as $compra): 
                        // Determinar clase de estado para el borde
                        $clase_estado = '';
                        if ($compra['estado'] === 'completado') {
                            $clase_estado = 'estado-completado';
                        } elseif ($compra['estado'] === 'pendiente') {
                            $clase_estado = 'estado-pendiente';
                        } elseif ($compra['estado'] === 'cancelado') {
                            $clase_estado = 'estado-cancelado';
                        }
                    ?>
                    <div class="col-12 mb-4">
                        <div class="card compra-card <?php echo $clase_estado; ?>">
                            <div class="card-header bg-transparent">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?php echo date('d/m/Y H:i', strtotime($compra['fecha'])); ?>
                                    </small>
                                    <span class="badge 
                                        <?php 
                                        if ($compra['estado'] === 'completado') echo 'bg-success';
                                        elseif ($compra['estado'] === 'pendiente') echo 'bg-warning';
                                        else echo 'bg-danger';
                                        ?>">
                                        <?php echo ucfirst($compra['estado']); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <h5 class="card-title">Folio: #<?php echo $compra['id']; ?></h5>
                                        <p class="card-text mb-1">
                                            <small class="text-muted">
                                                <i class="fas fa-credit-card me-1"></i>
                                                <?php echo $compra['medio_pago'] ?: 'No especificado'; ?>
                                            </small>
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <h4 class="text-primary mb-0">
                                            <?php 
                                            if (defined('MONEDA')) {
                                                echo MONEDA . number_format($compra['total'], 2, '.', ',');
                                            } else {
                                                echo '$' . number_format($compra['total'], 2, '.', ',');
                                            }
                                            ?>
                                        </h4>
                                        <small class="text-muted">Total</small>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <a href="compra_detalle.php?id=<?php echo $compra['id']; ?>" 
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-eye me-2"></i>Ver compra
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Estadísticas simples -->
                <div class="row mt-5">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="text-primary"><?php echo count($compras); ?></h3>
                                <p class="text-muted mb-0">Compras totales</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="text-success">
                                    <?php 
                                    $completadas = array_filter($compras, function($compra) {
                                        return $compra['estado'] === 'completado';
                                    });
                                    echo count($completadas);
                                    ?>
                                </h3>
                                <p class="text-muted mb-0">Completadas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="text-primary">
                                    <?php 
                                    $total_gastado = array_sum(array_column($compras, 'total'));
                                    if (defined('MONEDA')) {
                                        echo MONEDA . number_format($total_gastado, 2, '.', ',');
                                    } else {
                                        echo '$' . number_format($total_gastado, 2, '.', ',');
                                    }
                                    ?>
                                </h3>
                                <p class="text-muted mb-0">Total gastado</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer id="contacto">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h4>HIDROSISTEMAS</h4>
                    <p>Tenemos las mejores marcas, con calidad que buscas.</p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/HidrosistemasHGO/?fref=ts" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://api.whatsapp.com/send/?phone=527712167150&text&type=phone_number&app_absent=0" target="_blank"><i class="fab fa-whatsapp"></i></a>
                        <a href="https://www.instagram.com/hidrosistemas_mx?igsh=MXZ6YmsydjgxZmN6NA==" target="_blank"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="footer-col">
                    <h4>Servicios</h4>
                    <ul>
                        <li><a href="asesoria.html">Asesoría</a></li>
                        <li><a href="capacitacion.html">Capacitación</a></li>
                        <li><a href="supervision.html">Supervisión</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Enlaces Rápidos</h4>
                    <ul>
                        <li><a href="index.php">Inicio</a></li>
                        <li><a href="Nosotros.html">Nosotros</a></li>
                        <li><a href="productos.html">Productos</a></li>
                        <li><a href="#contacto">Contacto</a></li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2025 Hidrosistemas. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>