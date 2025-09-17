<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HidroBuy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Reset y estilos generales */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }
        
        a {
            text-decoration: none;
            color: inherit;
        }
        
        ul {
            list-style: none;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        /* Header */
        header {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-top {
            background-color: #2c3e50;
            color: white;
            padding: 8px 0;
            font-size: 14px;
        }
        
        .header-top-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
</style>
</head>
<body>
    <header>
        <div class="header-top">
            <div class="container header-top-content">
                <div class="contact-info">
                    <span><i class="fas fa-phone"></i> (123) 456-7890</span>
                    <span><i class="fas fa-envelope"></i> info@fixferreterias.com</span>
                </div>
                <div class="user-actions">
                    <a href="#"><i class="fas fa-user"></i> Mi Cuenta</a>
                    <a href="#"><i class="fas fa-heart"></i> Favoritos</a>
                </div>
            </div>
        </div>
    </header> 
</body>
</html>