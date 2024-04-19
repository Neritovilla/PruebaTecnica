<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
// Incluir el archivo de configuración de la conexión a la base de datos
include 'conexion.php';

// Verificar que la solicitud sea de tipo GET
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['list'])) {
    // Consulta SQL para obtener todas las provincias
    $sql = "SELECT id_provincia , nombre_provincia  FROM provincia ";
    $resultado = $conn->query($sql);

    // Verificar si se encontraron resultados
    if ($resultado->num_rows > 0) {
        // Crear un array para almacenar las provincias
        $provincias = array();

        // Iterar sobre los resultados y almacenar cada provincia en el array
        while ($row = $resultado->fetch_assoc()) {
            $provincia = array(
                "id_provincia" => $row["id_provincia"],
                "nombre_provincia" => $row["nombre_provincia"]
            );
            // Agregar la provincia al array de provincias
            $provincias[] = $provincia;
        }

        // Devolver las provincias en formato JSON
        echo json_encode($provincias);
    } else {
        // Si no se encontraron resultados, devolver un mensaje de error
        echo "No se encontraron provincias.";
    }
} else {
    // Si la solicitud no es de tipo GET, devolver un mensaje de error
    http_response_code(405);
    echo "Método no permitido. Se esperaba una solicitud GET.";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
