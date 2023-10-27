<?php
// Incluye tus archivos y configuraciones iniciales aquí
include ("../conexion/conexion.php");

// Variables de entrada
$txtId = (isset($_POST['txtId'])) ? $_POST['txtId'] : '';
$txtTitulo = (isset($_POST['txtTitulo'])) ? $_POST['txtTitulo'] : '';
$txtAutor = (isset($_POST['txtAutor'])) ? $_POST['txtAutor'] : '';
$txtFecha = (isset($_POST['txtFecha'])) ? $_POST['txtFecha'] : '';
$txtGenero = (isset($_POST['txtGenero'])) ? $_POST['txtGenero'] : '';
$txtFoto = (isset($_FILES['txtFoto']["name"])) ? $_FILES['txtFoto']["name"] : '';

$action = (isset($_POST['action'])) ? $_POST['action'] : '';

$actionAgregar = "";
$actionModificar = $actionEliminar = $actionObtener = $actionCancelar = "disabled";
$mostrarModal = false;

// Lógica para crear un nuevo libro
function agregarLibro($pdo, $txtTitulo, $txtAutor, $txtFecha, $txtGenero, $txtFoto) {
    $sentencia = $pdo->prepare("INSERT INTO libros(Titulo, Autor, Fecha_de_publicacion, Genero, Foto) 
        VALUES (:Titulo, :Autor, :Fecha_de_publicacion, :Genero, :Foto)");

    $sentencia->bindParam(":Titulo", $txtTitulo);
    $sentencia->bindParam(":Autor", $txtAutor);
    $sentencia->bindParam(":Fecha_de_publicacion", $txtFecha);
    $sentencia->bindParam(":Genero", $txtGenero);

    $Fecha = new DateTime();
    $nombreArchivo = ($txtFoto != "") ? $Fecha->getTimestamp() . "_" . $_FILES['txtFoto']["name"] : "Default.png";

    $tmpFoto = $_FILES["txtFoto"]["tmp_name"];

    if ($tmpFoto != "") {
        move_uploaded_file($tmpFoto, "../Imagenes/" . $nombreArchivo);
    }

    $sentencia->bindParam(":Foto", $nombreArchivo);
    $sentencia->execute();
    header('Location: index.php');
}

// Lógica para modificar un libro existente
function modificarLibro($pdo, $txtId, $txtTitulo, $txtAutor, $txtFecha, $txtGenero, $txtFoto) {
    $sentencia = $pdo->prepare("UPDATE libros SET 
        Titulo=:Titulo,
        Autor=:Autor, 
        Fecha_de_publicacion=:Fecha_de_publicacion, 
        Genero=:Genero
        WHERE Id=:Id");

    $sentencia->bindParam(':Titulo', $txtTitulo);
    $sentencia->bindParam(':Autor', $txtAutor);
    $sentencia->bindParam(':Fecha_de_publicacion', $txtFecha);
    $sentencia->bindParam(':Genero', $txtGenero);
    $sentencia->bindParam(':Id', $txtId);
    $sentencia->execute();

    $Fecha = new DateTime();
    $nombreArchivo = ($txtFoto != "") ? $Fecha->getTimestamp() . "_" . $_FILES['txtFoto']["name"] : "Default.png";

    $tmpFoto = $_FILES["txtFoto"]["tmp_name"];

    if ($tmpFoto != "") {
        move_uploaded_file($tmpFoto, "../Imagenes/" . $nombreArchivo);

        $sentencia = $pdo->prepare("SELECT Foto FROM libros WHERE Id=:Id");
        $sentencia->bindParam(':Id', $txtId);
        $sentencia->execute();
        $registro = $sentencia->fetch(PDO::FETCH_LAZY);

        if (isset($registro["Foto"])) {
            if (file_exists("../Imagenes/" . $registro["Foto"])) {
                if ($registro['Foto'] != "Default.png") {
                    unlink("../Imagenes/" . $registro["Foto"]);
                }
            }
        }

        $sentencia = $pdo->prepare("UPDATE libros SET Foto=:Foto WHERE Id=:Id");
        $sentencia->bindParam(':Foto', $nombreArchivo);
        $sentencia->bindParam(':Id', $txtId);
        $sentencia->execute();
    }

    header('Location: index.php');
}

// Lógica para eliminar un libro
function eliminarLibro($pdo, $txtId) {
    $sentencia = $pdo->prepare("SELECT Foto FROM libros WHERE Id=:Id");
    $sentencia->bindParam(':Id', $txtId);
    $sentencia->execute();
    $registro = $sentencia->fetch(PDO::FETCH_LAZY);

    if (isset($registro["Foto"]) && ($registro['Foto'] != "Default.png")) {
        if (file_exists("../Imagenes/" . $registro["Foto"])) {
            unlink("../Imagenes/" . $registro["Foto"]);
        }
    }

    $sentencia = $pdo->prepare("DELETE FROM libros WHERE Id=:Id");
    $sentencia->bindParam(':Id', $txtId);
    $sentencia->execute();
    header('Location: index.php');
}

// Lógica para obtener un libro
function obtenerLibro($pdo, $txtId) {
    global $txtTitulo, $txtAutor, $txtFecha, $txtGenero, $txtFoto;
    global $actionAgregar, $actionModificar, $actionEliminar, $actionCancelar, $actionObtener;
    global $mostrarModal;

    $actionAgregar = "disabled";
    $actionModificar = $actionEliminar = $actionCancelar = $actionObtener = "";
    $mostrarModal = true;

    $sentencia = $pdo->prepare("SELECT * FROM libros WHERE Id=:Id");
    $sentencia->bindParam(':Id', $txtId);
    $sentencia->execute();
    $registro = $sentencia->fetch(PDO::FETCH_LAZY);

    $txtTitulo = $registro['Titulo'];
    $txtAutor = $registro['Autor'];
    $txtFecha = $registro['Fecha_de_publicacion'];
    $txtGenero = $registro['Genero'];
    $txtFoto = $registro['Foto'];
}

// Realiza la acción correspondiente
switch ($action) {
    case "btnAgregar":
        agregarLibro($pdo, $txtTitulo, $txtAutor, $txtFecha, $txtGenero, $txtFoto);
        break;
    case "btnModificar":
        modificarLibro($pdo, $txtId, $txtTitulo, $txtAutor, $txtFecha, $txtGenero, $txtFoto);
        break;
    case "btnEliminar":
        eliminarLibro($pdo, $txtId);
        break;
    case "btnCancelar":
        header('Location: index.php');
        break;
    case "Obtener":
        obtenerLibro($pdo, $txtId);
        break;
}

// Obtiene la lista de libros
$sentencia = $pdo->prepare("SELECT * FROM libros");
$sentencia->execute();
$listaLibros = $sentencia->fetchAll(PDO::FETCH_ASSOC);

?>