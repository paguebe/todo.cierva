<?php
require "todo.php";
require "DB.php";

function return_response($status, $statusMessage, $data) {
    header("HTTP/1.1 $status $statusMessage");
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($data);
}

$bodyRequest = file_get_contents("php://input");

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $db = new DB();
        $new_todo = new Todo;

        // Inicializar el objeto a partir del cuerpo del POST en JSON
        $new_todo->jsonConstruct($bodyRequest);

        // Debug: Verificar el contenido que se va a insertar
        if (empty($new_todo->getContent())) {
            return_response(400, "Bad Request: Content is empty", null);
        }

        // Insertar en la base de datos
        $new_todo->insert($db->connection);

        // Obtener la última tarea insertada
        $last_todo = Todo::DB_selectAll($db->connection);
        $last_todo = end($last_todo); // Obtener el último elemento

        // Convertir la última tarea a JSON y devolverla en la respuesta
        return_response(200, "OK", $last_todo);
        break;

    default:
        return_response(405, "Method Not Allowed", null);
        break;
}
?>
