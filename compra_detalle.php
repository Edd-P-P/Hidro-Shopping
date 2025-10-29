<?php
session_start();

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['user_cliente'])) {
    header('Location: login.php');
    exit;
}

require_once 'config/database.php';

$db = new Database();
$con = $db->conectar();

// Verificar que se reciba el ID de la compra
if (!isset($_GET['id'])) {
    header('Location: compras.php');
    exit;
}

$id_compra = $_GET['id'];
$id_cliente = $_SESSION['user_cliente'];

// Obtener los detalles de la compra, verificando que pertenezca al cliente
$sql_compra = $con->prepare("SELECT * FROM pedidos WHERE id = ? AND id_cliente = ?");
$sql_compra->execute([$id_compra, $id_cliente]);
$compra = $sql_compra->fetch(PDO::FETCH_ASSOC);

// Si no se encuentra la compra o no pertenece al cliente, redirigir
if (!$compra) {
    header('Location: compras.php');
    exit;
}

// Decodificar los productos del carrito
$productos = json_decode($compra['productos'], true);

// OBTENER CATEGORÍAS PARA EL MENÚ
$sql_todas_categorias = $con->prepare("SELECT id, nombre, slug FROM categorias WHERE activo = 1 ORDER BY id ASC");
$sql_todas_categorias->execute();
$todas_categorias = $sql_todas_categorias->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Compra #<?php echo $compra['id']; ?> - Hidrosistemas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans:wght@400;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'menu.php'; ?>

    <!-- Contenido Principal -->
    <main class="container my-5">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1><i class="fas fa-file-invoice me-2"></i>Detalle de Compra</h1>
                    <a href="compras.php" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Volver a mis compras
                    </a>
                </div>

                <!-- Resumen de la compra -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Resumen de la Compra #<?php echo $compra['id']; ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($compra['fecha'])); ?></p>
                                <p><strong>Estado:</strong> 
                                    <span class="badge 
                                        <?php 
                                        if ($compra['estado'] === 'completado') echo 'bg-success';
                                        elseif ($compra['estado'] === 'pendiente') echo 'bg-warning';
                                        else echo 'bg-danger';
                                        ?>">
                                        <?php echo ucfirst($compra['estado']); ?>
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Método de pago:</strong> <?php echo $compra['medio_pago'] ?: 'No especificado'; ?></p>
                                <p><strong>Total:</strong> 
                                    <span class="h5 text-primary">
                                        <?php 
                                        if (defined('MONEDA')) {
                                            echo MONEDA . number_format($compra['total'], 2, '.', ',');
                                        } else {
                                            echo '$' . number_format($compra['total'], 2, '.', ',');
                                        }
                                        ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Productos de la compra -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Productos comprados</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($productos) && is_array($productos)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Precio unitario</th>
                                            <th>Cantidad</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($productos as $producto): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php
                                                    $rutaImg = "imagenes/productos/" . $producto['categoria_id'] . '/' . $producto['id'] . ".png";
                                                    if (!file_exists($rutaImg)) {
                                                        $rutaImg = "imagenes/productos/default.png";
                                                    }
                                                    ?>
                                                    <img src="<?php echo htmlspecialchars($rutaImg); ?>" 
                                                         alt="<?php echo htmlspecialchars($producto['nombre']); ?>" 
                                                         width="50" 
                                                         class="me-3 rounded"
                                                         onerror="this.src='imagenes/productos/default.png'">
                                                    <div>
                                                        <strong><?php echo htmlspecialchars($producto['nombre']); ?></strong>
                                                        <?php if (!empty($producto['medida']) && $producto['medida'] !== 'No aplica'): ?>
                                                            <br><small class="text-muted">Medida: <?php echo htmlspecialchars($producto['medida']); ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php 
                                                if (defined('MONEDA')) {
                                                    echo MONEDA . number_format($producto['precio_final'], 2, '.', ',');
                                                } else {
                                                    echo '$' . number_format($producto['precio_final'], 2, '.', ',');
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $producto['cantidad']; ?></td>
                                            <td>
                                                <strong>
                                                    <?php 
                                                    if (defined('MONEDA')) {
                                                        echo MONEDA . number_format($producto['subtotal'], 2, '.', ',');
                                                    } else {
                                                        echo '$' . number_format($producto['subtotal'], 2, '.', ',');
                                                    }
                                                    ?>
                                                </strong>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                            <td><strong>
                                                <?php 
                                                if (defined('MONEDA')) {
                                                    echo MONEDA . number_format($compra['total'], 2, '.', ',');
                                                } else {
                                                    echo '$' . number_format($compra['total'], 2, '.', ',');
                                                }
                                                ?>
                                            </strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No se encontraron detalles de productos para esta compra.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>