<?php
  function connectBD() {
    $conexion_bd = mysqli_connect("localhost", "root", "", "Lab14");
    if ($conexion_bd == NULL){
      //Es mejor practica solo poner que estamos trabajando en la solucion.
      die("No se pudo establecer conexion con la base de datos");
    }
    return $conexion_bd;
  }



  function disconnectBD($conexion_bd) {
    mysqli_close($conexion_bd);
  }



//Funcion para desplegar materiales dentro de la base de datos.
//@param $Mat_Clave: Si se ingresa la clave de materiales, buscara dentro de la base de datos con este criterio agregado
//@param $Prov_RFC: Si se ingresa el RFC de Proveedores, buscara dentro de la base de datos con este criterio agregado
//@param $Proy_Numero: Si se ingresa el $Proy_Numero de proyectos, buscara dentro de la base de datos con este criterio agregado
  function consultar_existencia($Mat_Clave = "", $Prov_RFC= "", $Proy_Numero = "") {
    $conexion_bd = connectBD();
    $consulta = 'SELECT M.Clave as m_clave, Pv.RFC as pv_rfc, Py.Numero as py_numero,E.Fecha as e_fecha, E.Cantidad as e_cantidad
                  FROM Materiales as M, Proyectos as Py, Proveedores as Pv, Entregan as E
                  WHERE E.Clave = M.Clave AND E.RFC = Pv.RFC AND E.Numero = Py.Numero';

    if ($Mat_Clave != "") {
      $consulta .= " AND E.Clave =".$Mat_Clave;
    }

    if ($Prov_RFC != "") {
      $consulta .= " AND E.RFC='".$Prov_RFC."'";
    }

    if ($Proy_Numero != "") {
      $consulta .= " AND E.numero =".$Proy_Numero;
    }


    $resultados = $conexion_bd->query($consulta);
    $tabla = '<table class="table table-responsive">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">Clave</th>
                    <th scope="col">RFC</th>
                    <th scope="col">Numero</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Cantidad</th>
                  </tr>
                </thead>
                <tbody>';

    while ($row = mysqli_fetch_array($resultados, MYSQLI_BOTH)) {
      $tabla .= '<tr>';
      $tabla .= '<td>'.$row['m_clave'].'</td>';
      $tabla .= '<td>'.$row['pv_rfc'].'</td>';
      $tabla .= '<td>'.$row['py_numero'].'</td>';
      $tabla .= '<td>'.$row['e_fecha'].'</td>';
      $tabla .= '<td>'.$row['e_cantidad'].'</td>';
      $tabla .= '</tr>';
    }
    $tabla .= '</tbody></table>';

    mysqli_free_result($resultados); #Liberar espacio de memoria
    disconnectBD($conexion_bd); #Desconectarme de BD

    return $tabla;
  }


//Funcion que busca dentro de la tabla dependiendo de los valores seleccionados
  function select_buscar($tabla, $id, $col_descripcion) {
    $conexion_bd = connectBD();

    $consulta = "SELECT $id, $col_descripcion FROM $tabla";
    $resultados = $conexion_bd->query($consulta);

    $resultado = '<label>'.$tabla.'...</label>
                  <select class="form-control-sm form-control" name='.$tabla.'>
                    <option value="" disabled selected>Selecciona una opción</option>';
    while ($row = mysqli_fetch_array($resultados, MYSQLI_BOTH)) {
        $resultado .= '<option value="'.$row["$id"].'">'.$row["$col_descripcion"].'</option>';
    }

    $resultado .=  '</select>';
    mysqli_free_result($resultados); #Liberar espacio de memoria
    disconnectBD($conexion_bd); #Desconectarme de BD
    return $resultado;
  }


//Funcion que selecciona de la tabla de entregan el parametro a agregar dentro de la tabla
  // function select_modificar($tabla, $id, $col_descripcion) {
  //   $conexion_bd = connectBD();
  //
  //   $consulta = "SELECT $id, $col_descripcion FROM $tabla";
  //   $resultados = $conexion_bd->query($consulta);
  //
  //   $resultado = '<label>'.$tabla.'...</label>
  //                 <select class="form-control-sm form-control" name='.$tabla.'>
  //                   <option value="" disabled selected>Selecciona una opción</option>';
  //   while ($row = mysqli_fetch_array($resultados, MYSQLI_BOTH)) {
  //       $resultado .= '<option value="'.$row["$id"].'">'.$row["$col_descripcion"].'</option>';
  //   }
  //
  //
  //   $resultado .=  '</select>';
  //   mysqli_free_result($resultados); #Liberar espacio de memoria
  //   disconnectBD($conexion_bd); #Desconectarme de BD
  //   return $resultado;
  // }


//Funcion para insertar en la BD Entregan. Es necesario hacer un select para insertar en $Clave $RFC y $Numero
  function insertar_entrega($Clave = "", $RFC ="", $Numero = "", $Cantidad = "") {
    $conexion_bd = connectBD();

    //Preparar la consultar
    $dml = 'INSERT INTO Entregan (lugar_id) VALUES (?) ';
    if ( !($statement = $conexion_bd->prepare($dml)) ) {
        die("Error: (" . $conexion_bd->errno . ") " . $conexion_bd->error);
    }


    mysqli_free_result($resultados);
    disconnectBD($conexion_bd);

  }

  function eliminar_entrega($Clave, $RFC, $Numero, $Fecha) {

  }




 ?>