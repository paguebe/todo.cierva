<?php

class Todo implements \JsonSerializable {
    private int $item_id;
    private string $content;

    // Inicializa todas las variables del objeto con las pasadas por parámetros
    public function parametersConstruct(int $item_id, string $content){
        $this->item_id = $item_id;
        $this->content = $content;
    }

    // Inicializa todas las variables con el json pasado por parámetro
    public function jsonConstruct($json) {
        foreach (json_decode($json, true) as $key => $value) {
            $this->{$key} = $value;
        }
    }

    // Convierte el objeto a un json (jsonEncode)
    public function jsonSerialize() {
        return get_object_vars($this);
    }

    public function getContent() {
        return $this->content;
    }

    public function getItem_id() {
        return $this->item_id;
    }

    // Insertar una nueva tarea en la base de datos
    public function insert($dbconn) {
        $stmt = $dbconn->prepare("INSERT INTO todo_list (content) VALUES (:content)");
        $stmt->bindParam(':content', $this->content);
        $stmt->execute();
    }

    // Devuelve todos los elementos de la BBDD en forma de array
    public static function DB_selectAll($dbconn) {
        $todo_list = array();
        $stmt = $dbconn->query("SELECT item_id, content FROM todo_list");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $new_todo = new Todo;
            $new_todo->parametersConstruct($row['item_id'], $row['content']);
            $todo_list[] = $new_todo;
        }
        return $todo_list;
    }
}
?>
