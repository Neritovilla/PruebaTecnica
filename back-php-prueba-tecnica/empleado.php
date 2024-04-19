<?php
// Permitir solicitudes desde cualquier origen
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT");
header("Access-Control-Allow-Headers: Content-Type");

// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

// Verificar si se está solicitando la lista de empleados
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['list'])) {
    // Consulta SQL para obtener la lista de empleados
    $sql = "SELECT id_empleado, nombres, apellidos, codigo, estado FROM Empleado";
    
    // Ejecutar la consulta
    $result = $conn->query($sql);

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        // Crear un array para almacenar los datos de los empleados
        $empleados = array();

        // Recorrer los resultados y almacenarlos en el array
        while ($row = $result->fetch_assoc()) {
            $empleados[] = $row;
        }

        // Devolver los datos en formato JSON
        echo json_encode($empleados);
    } else {
        // Si no hay empleados, devolver un mensaje de error
        echo json_encode(array("message" => "No se encontraron empleados."));
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}
// Verificar si se está solicitando la lista de empleados
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['all'])) {
    // Consulta SQL para obtener la lista de empleados
    $sql = "SELECT * FROM Empleado";
    
    // Ejecutar la consulta
    $result = $conn->query($sql);

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        // Crear un array para almacenar los datos de los empleados
        $empleados = array();

        // Recorrer los resultados y almacenarlos en el array
        while ($row = $result->fetch_assoc()) {
            $empleados[] = $row;
        }

        // Devolver los datos en formato JSON
        echo json_encode($empleados);
    } else {
        // Si no hay empleados, devolver un mensaje de error
        echo json_encode(array("message" => "No se encontraron empleados."));
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}
// Verificar si se está solicitando la lista de empleados
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    // Consulta SQL para obtener la lista de empleados
    $id = $_GET["id"];
    $sql = "SELECT * FROM Empleado where id_empleado = $id";
    
    // Ejecutar la consulta
    $result = $conn->query($sql);

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        // Crear un array para almacenar los datos de los empleados
        $empleados = array();

        // Recorrer los resultados y almacenarlos en el array
        while ($row = $result->fetch_assoc()) {
            $empleados[] = $row;
        }

        // Devolver los datos en formato JSON
        echo json_encode($empleados);
    } else {
        // Si no hay empleados, devolver un mensaje de error
        echo json_encode(array("message" => "No se encontraron empleados."));
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}

// Verificar si se reciben datos del formulario por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del cuerpo de la solicitud
    $data = json_decode(file_get_contents("php://input"), true);

    // Verificar si los datos están presentes y son válidos
    if(isset($data['nombres']) && isset($data['apellidos'])) {
        // Procesar los datos recibidos
        $nombres = $data['nombres'];
        $apellidos = $data['apellidos'];
        $cedula = $data['cedula'];
        $id_provincia = $data['provinciaPersonal'];
        $fecha_nacimiento = $data['fechaNacimiento'];
        $email = $data['email'];
        $observacion = $data['observacionesPersonales'];
        $fecha_ingreso = $data['fechaIngreso'];
        $cargo = $data['cargo'];
        $departamento = $data['departamento'];
        $id_provincia_laboral = $data['provinciaLaboral'];
        $sueldo = $data['sueldo'];
        $jornada_presencial = intval($data['jornadaPresencial']);
        $observaciones_laboral = $data['observacionesLaborales'];

        
        // Preparar la consulta SQL para insertar un nuevo empleado
        $stmt = $conn->prepare("INSERT INTO Empleado (nombres, apellidos, cedula, id_provincia, fecha_nacimiento, email, observacion, fecha_ingreso, cargo, departamento, id_provincia_laboral, sueldo, jornada_presencial, observaciones_laboral) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // Vincular los parámetros
        $stmt->bind_param("sssissssssisds", $nombres, $apellidos, $cedula, $id_provincia, $fecha_nacimiento, $email, $observacion, $fecha_ingreso, $cargo, $departamento, $id_provincia_laboral, $sueldo, $jornada_presencial, $observaciones_laboral);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Si la consulta se ejecutó correctamente
            // Obtener el ID del nuevo empleado insertado
            $id_empleado = $stmt->insert_id;

            // Actualizar la columna "codigo" con el valor del "id_empleado" y los ceros adicionales
            $codigo = str_pad($id_empleado, 6, '0', STR_PAD_LEFT);
            $update_sql = "UPDATE Empleado SET codigo = ?, estado = 'Activo' WHERE id_empleado = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("si", $codigo, $id_empleado);
            $update_stmt->execute();
            $update_stmt->close();

            // Devolver la respuesta
            echo json_encode(array("message" => "Empleado creado correctamente"));
        } else {
            // Si ocurrió un error en la consulta
            echo json_encode(array("message" => "Error al crear el empleado"));
        }

        // Cerrar la declaración y la conexión
        $stmt->close();
        $conn->close();
    } else {
        // Enviar un mensaje de error si los datos no son válidos o están incompletos
        echo json_encode(array("message" => "Error al crear el empleado: Datos incompletos"));
    }
}



// Verificar si se reciben datos del formulario por PUT
if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    // Recibir los datos del cuerpo de la solicitud
    $data = json_decode(file_get_contents("php://input"), true);

    // Verificar si los datos están presentes y son válidos
    if(isset($data['empleadoId'])) {
        // Procesar los datos recibidos
        $id_empleado = $data['empleadoId'];
        $nombres = $data['nombres'];
        $apellidos = $data['apellidos'];
        $cedula = $data['cedula'];
        $id_provincia = $data['provinciaPersonal'];
        $fecha_nacimiento = $data['fechaNacimiento'];
        $email = $data['email'];
        $observacion = $data['observacionesPersonales'];
        $fecha_ingreso = $data['fechaIngreso'];
        $cargo = $data['cargo'];
        $departamento = $data['departamento'];
        $id_provincia_laboral = $data['provinciaLaboral'];
        $sueldo = $data['sueldo'];
        $jornada_presencial = intval($data['jornadaPresencial']);
        $observaciones_laboral = $data['observacionesLaborales'];

        // Preparar la consulta SQL para actualizar el empleado
        $stmt = $conn->prepare("UPDATE Empleado SET nombres = ?, apellidos = ?, cedula = ?, id_provincia = ?, fecha_nacimiento = ?, email = ?, observacion = ?, fecha_ingreso = ?, cargo = ?, departamento = ?, id_provincia_laboral = ?, sueldo = ?, jornada_presencial = ?, observaciones_laboral = ? WHERE id_empleado = ?");

        // Vincular los parámetros
        $stmt->bind_param("sssissssssisdsi", $nombres, $apellidos, $cedula, $id_provincia, $fecha_nacimiento, $email, $observacion, $fecha_ingreso, $cargo, $departamento, $id_provincia_laboral, $sueldo, $jornada_presencial, $observaciones_laboral, $id_empleado);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Si la consulta se ejecutó correctamente
            echo json_encode(array("message" => "Empleado actualizado correctamente"));
        } else {
            // Si ocurrió un error en la consulta
            echo json_encode(array("message" => "Error al actualizar el empleado"));
        }

        // Cerrar la declaración y la conexión
        $stmt->close();
        $conn->close();
    } else {
        // Enviar un mensaje de error si los datos no son válidos o están incompletos
        echo json_encode(array("message" => "Error al actualizar el empleado: Datos incompletos"));
    }
}

?>
