<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina principal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="./../styles/styles.css">
</head>
<body>
    <h2 class="text-center">Pagina principal</h2>
    <div class="container">
        <div class="logo_btns_row">
            <div class="logo_col">
                <img src="./../img/logo1.png" alt="logo" class="logo">
            </div>
            <div class="btns_col">
                <h3 class="mb-4 text-center">Bienvenido</h3>
                <form action="./login.php" method="POST" id="formProducto">
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
                <br>
                <form action="./registro.php" method="POST" id="formProducto">
                    <div class="d-grid">
                        <button type="submit" class="btn btn-secondary">Registrate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>