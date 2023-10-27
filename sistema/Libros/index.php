<?php
require 'libreria.php'

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libreria</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <form action="" method="post" enctype="multipart/form-data">

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Libro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <div class="form-row">
                    
                    <input type="hidden" required name="txtId" value="<?php echo $txtId?>" placeholder="" id="txtId" require="">
                

                    <label for="">Titulo</label>
                    <input type="text" name="txtTitulo" class="form-control" required value="<?php echo $txtTitulo?>" placeholder="" id="txtTitulo" require="">
                    <br>

                    <label for="">Autor</label>
                    <input type="text" name="txtAutor" class="form-control" required value="<?php echo $txtAutor?>" placeholder="" id="txtAutor" require="">
                    <br>

                    <label for="">Fecha</label>
                    <input type="text" name="txtFecha" class="form-control" required value="<?php echo $txtFecha?>" placeholder="" id="txtFecha" require="">
                    <br>

                    <label for="">Genero</label>
                    <input type="text" name="txtGenero" class="form-control" required value="<?php echo $txtGenero?>" placeholder="" id="txtGenero" require="">
                    <br>

                    <label for="">Foto</label>
                    <?php if($txtFoto!=""){?>
                            <img class="img-thumbnail mx-auto d-block mb-3 mt-3" width="180px" src="../Imagenes/<?php echo $txtFoto;?>" />
                    <?php }?>
                    <input type="file" accept="image/*" name="txtFoto" class="form-control" value="<?php echo $txtFoto?>" placeholder="" id="txtFoto" require="">
                    <br>
                </div>
                </div>
                <div class="modal-footer">
                    <button value="btnAgregar" <?php echo $actionAgregar;?> class="btn btn-success" type="submit" name="action">Agregar</button>
                    <button value="btnModificar" <?php echo $actionModificar;?> class="btn btn-warning" type="submit" name="action">Modificar</button>
                    <button value="btnEliminar" onclick="return Confirmar('¿Realmente deseas borrar el registro?');" <?php echo $actionEliminar;?> class="btn btn-danger" type="submit" name="action">Eliminar</button>
                    <button value="btnCancelar" <?php echo $actionCancelar;?> class="btn btn-info" type="submit" name="action">Cancelar</button>
                </div>
                </div>
            </div>
            </div>
            <br>
            <br>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" onclick="mostrarModal()" data-toggle="modal" data-target="#exampleModal">
            Agregar registro +
            </button>
            <br>
            <br>

        </form>

        <div class="row">

            <table class="table table-hover table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Titulo</th>
                        <th>Autor</th>
                        <th>Fecha</th>
                        <th>Genero</th>
                        <th>Foto</th>
                        <th>accion</th>
                    </tr>
                </thead>

            <?php foreach($listaLibros as $libros) { ?>
            
                <tr>
                    <td><?php echo $libros['Titulo'] ?></td>
                    <td><?php echo $libros['Autor'] ?></td>
                    <td><?php echo $libros['Fecha_de_publicacion'] ?></td>
                    <td><?php echo $libros['Genero'] ?></td>
                    <td><img class="img-thumbnail" width="100px" src="../Imagenes/<?php echo $libros['Foto']; ?>" /></td>
                    <td>
                    
                        <form action="" method="post">
                            <input type="hidden" name="txtId" value="<?php echo $libros['Id']; ?>">
                            <input type="submit" value="Obtener" class="btn btn-info" name="action" > 
                            <button value="btnEliminar" onclick="return Confirmar('¿Realmente deseas borrar el registro?');" type="submit" class="btn btn-danger" name="action">Eliminar</button>

                        </form>
                
                    </td>
                </tr>

            <?php  } ?>
            </table>

        </div>

        <?php 
            if($mostrarModal){?>
                <script>
                    $('#exampleModal').modal('show');
                </script>
        <?php }?>
        
        <script>
            function Confirmar(Mensaje){
                return (confirm(Mensaje))?true:false;
            }
        </script>


        <script>
            // Función para mostrar el modal y restablecer los campos
            function mostrarModal() {
                $('#exampleModal').modal('show');
                document.getElementById("txtId").value = "";
                document.getElementById("txtTitulo").value = "";
                document.getElementById("txtAutor").value = "";
                document.getElementById("txtFecha").value = "";
                document.getElementById("txtGenero").value = "";
                document.getElementById("txtFoto").src = "";
            }
        </script>

    </div>
</body>
</html>