<?php
session_start();
include '../library/configServer.php';
include '../library/consulSQL.php';

$nombre = consultasSQL::clean_string($_POST['nombre-login']);
$clave = consultasSQL::clean_string(md5($_POST['clave-login']));

if ($nombre != "" && $clave != "") {
    // Autenticación para el administrador
    $verAdmin = ejecutarSQL::consultar("SELECT * FROM administrador WHERE Nombre='$nombre' AND Clave='$clave'");
    $AdminC = mysqli_num_rows($verAdmin);

    // Autenticación para el usuario normal
    $verUser = ejecutarSQL::consultar("SELECT * FROM cliente WHERE Nombre='$nombre' AND Clave='$clave'");
    $UserC = mysqli_num_rows($verUser);

    if ($AdminC > 0) {
        // El usuario es un administrador
        $filaU = mysqli_fetch_array($verAdmin, MYSQLI_ASSOC);
        $_SESSION['nombreAdmin'] = $nombre;
        $_SESSION['claveAdmin'] = $clave;
        $_SESSION['UserType'] = "Admin";
        $_SESSION['adminID'] = $filaU['id'];
        echo '<script> location.href="index.php"; </script>';
    } elseif ($UserC > 0) {
        // El usuario es un usuario normal
        $filaU = mysqli_fetch_array($verUser, MYSQLI_ASSOC);
        $_SESSION['nombreUser'] = $nombre;
        $_SESSION['claveUser'] = $clave;
        $_SESSION['UserType'] = "User";
        $_SESSION['UserNIT'] = $filaU['NIT'];
        echo '<script> location.href="index.php"; </script>';
    } else {
        // Mensaje de error si la autenticación falla
        echo 'Error nombre o contraseña inválido';
    }
} else {
    // Mensaje de error si algún campo está vacío
    echo 'Error campo vacío<br>Intente nuevamente';
}
?>
