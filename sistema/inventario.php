<?php
    require '../php/conexionbd.php';
    session_start();

    $sql = "SELECT * FROM inventario";
                    $result = mysqli_query($conn, $sql);

    if (empty($_SESSION['active']) || $_SESSION['typus'] == "user") {
        header('location: ../');
        exit();
    }

    $filterID = "";
    $result = null;

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $filterID = mysqli_real_escape_string($conn, $_POST['id']);
        $sql = "SELECT * FROM inventario WHERE id = '$filterID'";
    } else {
        $sql = "SELECT * FROM inventario";
    }


    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo "Error en la consulta: " . mysqli_error($conn);
    }

    if (isset($_POST['stock']) && !empty($_POST['stock'])) {
        $cantidadNueva = $_POST['stock'];
        $idr = $_POST['id'];
        $sqlUpdate = "UPDATE inventario SET stock = $cantidadNueva WHERE id = $idr";
        $result = mysqli_query($conn, $sqlUpdate);
        if ($result) {
            header('Location: inventario.php');
            exit();
        } else {
            echo "Error al eliminar el registro: " . mysqli_error($conn);
        }
        mysqli_close($conn);
    }

    if (isset($_POST["commit"])) {
        $idr = intval($_POST['id']); // Asegúrate de que sea un número entero
        $sqlDelete = "DELETE FROM inventario WHERE id = $idr";
        $result = mysqli_query($conn, $sqlDelete);
    
        if (!$result) {
            echo "Error al eliminar: " . mysqli_error($conn); // Imprime el error
        } else {
            if (mysqli_affected_rows($conn) > 0) {  
                header("Location: inventario.php");
                exit();
            } else {
                echo "No se encontró ningún registro con el ID proporcionado.";
            }
        }
    }
    

?>




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"> 
    <title>Control de Ventas</title>
    <link rel="icon" href="../images/cross.png">
    <link rel="stylesheet" href="../styles/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">
</head>
<body style="color: white;"> 
	<div>
		<ul class="nav justify-content-center bg-dark p-3">
		  <li class="nav-item">
		  	<a class="nav-link text-white" href="index.php">Ver</a>
		  </li>
		  <li class="nav-item">
		  	<a class="nav-link text-white" href="registrar.php">Registrar</a>
		  </li>
		  <li class="nav-item">
		  	<a class="nav-link text-white active" href="modificar.php">Modificar</a>
		  </li>
          <li class="nav-item">
                <a class="nav-link text-white" href="inventario.php">Control Inventario</a>
            </li>
		  <li class="nav-item">
		  	<a class="nav-link text-white" href="eliminar.php">Eliminar</a>
		  </li>
		  <li class="nav-item ms-auto">
		  	<a class="nav-link text-white" href="../php/salir.php">Cerrar sesión</a>
		  </li>
		</ul>
	</div>
    <div class="container my-5">
        <div class="text-center mb-4">
            <h2 class="text-primary"><u>Control de inventario</u></h2>
        </div>
        <form action="inventario.php" method="POST">
            <div class="mb-3">
                <label for="id" class="form-label">Buscar producto</label>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                    <input type="text" class="form-control" id="id" name="id" placeholder="ID Producto" autocomplete="0">
                </div>
            </div>
            <button type="submit" class="btn btn-warning btn-sm" onclick="">Buscar</button>
        </form>
    </div>

    <!-- Tabla de ventas -->
    <div class="container my-5">
        <table class="table table-striped table-bordered table-hover table-responsive">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Precio p/unidad</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while ($inventario = mysqli_fetch_array($result)) {
                ?>
                <tr>
                    <td>
                    <input name="stock"  class="form-control" value="<?php echo $inventario['id']; ?>" disabled>
                    </td>
                    <td>
                        <input name="stock"  class="form-control" value="<?php echo $inventario['nombre']; ?>">
                    </td>
                    <td>
                        <input name="stock"  class="form-control" value="<?php echo $inventario['descripcion']; ?>">
                    </td>
                    <td>
                        <input name="stock"  class="form-control" value="<?php echo $inventario['precio_unitario']; ?>">
                    </td>
                    <td>
                        <form action="inventario.php" method="POST" style="display:inline;">
                            <input name="id" type="hidden" value="<?php echo $inventario['id']; ?>">
                            <input name="stock" class="form-control" value="<?php echo $inventario['stock']; ?>">
                            <button type="submit" class="btn btn-outline-success" onclick="return confirm('Modificar inventario?');">
                                Confirmar
                            </button>
                        </form>
                        <form action="inventario.php" method="POST" style="display:inline;">
                            <input name="id" type="hidden" value="<?php echo $inventario['id']; ?>">
                            <button name="commit" type="submit" class="btn btn-outline-danger" onclick="return confirm('Eliminar producto?');">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
