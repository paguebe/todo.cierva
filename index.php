<?php
require "DB.php";
require "todo.php";

try {
    $db = new DB;
    $todo_list = Todo::DB_selectAll($db->connection);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List actualizable mediante botón</title>
</head>
<body>
    <label for="content">Nueva tarea</label>
    <input type="text" id="content" placeholder="Ingresa una tarea"><br>
    <button id="guardar">Guardar</button>

    <h2 id="lista">TODO</h2>
    <ul id="todo-list">
        <?php foreach ($todo_list as $row): ?>
            <li id="item-<?= $row->getItem_id() ?>"><?= $row->getItem_id() . ". " . $row->getContent() ?></li>
        <?php endforeach; ?>
    </ul>

    <script>
        document.getElementById('guardar').addEventListener('click', function () {
            const content = document.getElementById('content').value;

            if (!content) {
                alert('Por favor, introduce un valor.');
                return;
            }

            const url = 'http://desarrollo.cierva.asir11.lan/controller.php';

            const postData = {
                content: content
            };

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(postData)
            })
            .then(response => response.json())
            .then(data => {
                // Limpiar el campo de entrada
                document.getElementById('content').value = '';

                // Crear un nuevo elemento de lista
                const newItem = document.createElement("li");
                newItem.id = `item-${data.item_id}`; // Asignar un ID basado en el nuevo item_id
                newItem.textContent = `${data.item_id}. ${data.content}`;

                // Añadir el nuevo elemento a la lista
                document.getElementById('todo-list').appendChild(newItem);
            })
            .catch(error => console.error('Error en la solicitud POST:', error));
        });
    </script>
</body>
</html>
