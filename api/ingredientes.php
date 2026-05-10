<?php
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();
$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        if(isset($_GET['id'])) {
            $stmt = $db->prepare("SELECT * FROM ingredientes WHERE ID_Ingrediente = ?");
            $stmt->execute([$_GET['id']]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            $stmt = $db->query("SELECT * FROM ingredientes ORDER BY ID_Ingrediente DESC");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        break;
        
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        $stmt = $db->prepare("
            INSERT INTO ingredientes (Nombre, Descripcion, Fecha_ingreso, Fecha_Vencimiento) 
            VALUES (?, ?, ?, ?)
        ");
        if($stmt->execute([$data->Nombre, $data->Descripcion, $data->Fecha_ingreso, $data->Fecha_Vencimiento])) {
            echo json_encode(["success" => true, "id" => $db->lastInsertId()]);
        } else {
            echo json_encode(["success" => false, "error" => "Error al insertar"]);
        }
        break;
        
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        $stmt = $db->prepare("
            UPDATE ingredientes 
            SET Nombre = ?, Descripcion = ?, Fecha_ingreso = ?, Fecha_Vencimiento = ? 
            WHERE ID_Ingrediente = ?
        ");
        if($stmt->execute([$data->Nombre, $data->Descripcion, $data->Fecha_ingreso, $data->Fecha_Vencimiento, $data->ID_Ingrediente])) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false]);
        }
        break;
        
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        $stmt = $db->prepare("DELETE FROM ingredientes WHERE ID_Ingrediente = ?");
        if($stmt->execute([$data->id])) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false]);
        }
        break;
}
?>