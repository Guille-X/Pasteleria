<?php
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();
$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        if(isset($_GET['id'])) {
            $stmt = $db->prepare("SELECT * FROM pasteles WHERE ID_pastel = ?");
            $stmt->execute([$_GET['id']]);
            $pastel = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $stmt2 = $db->prepare("
                SELECT i.ID_Ingrediente, i.Nombre, i.Descripcion, i.Fecha_ingreso, i.Fecha_Vencimiento
                FROM pastel_ingredientes pi
                JOIN ingredientes i ON pi.ID_Ingrediente = i.ID_Ingrediente
                WHERE pi.ID_pastel = ?
            ");
            $stmt2->execute([$_GET['id']]);
            $pastel['ingredientes'] = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode($pastel);
        } else {
            $stmt = $db->query("SELECT * FROM pasteles ORDER BY ID_pastel DESC");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        break;
        
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        try {
            $db->beginTransaction();
            
            $stmt = $db->prepare("
                INSERT INTO pasteles (Nombre, Descripcion, Preparado_por, Fecha_creacion, Fecha_Vencimiento) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $data->Nombre, 
                $data->Descripcion, 
                $data->Preparado_por,
                $data->Fecha_creacion,
                $data->Fecha_Vencimiento
            ]);
            $pastel_id = $db->lastInsertId();
            
            foreach($data->ingredientes as $ing) {
                $stmt2 = $db->prepare("INSERT INTO pastel_ingredientes (ID_pastel, ID_Ingrediente) VALUES (?, ?)");
                $stmt2->execute([$pastel_id, $ing->ID_Ingrediente]);
            }
            
            $db->commit();
            echo json_encode(["success" => true, "id" => $pastel_id]);
        } catch(Exception $e) {
            $db->rollBack();
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
        break;
        
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        try {
            $db->beginTransaction();
            
            $stmt = $db->prepare("
                UPDATE pasteles 
                SET Nombre = ?, Descripcion = ?, Preparado_por = ?, Fecha_creacion = ?, Fecha_Vencimiento = ? 
                WHERE ID_pastel = ?
            ");
            $stmt->execute([
                $data->Nombre, 
                $data->Descripcion, 
                $data->Preparado_por,
                $data->Fecha_creacion,
                $data->Fecha_Vencimiento,
                $data->ID_pastel
            ]);
            
            $stmt2 = $db->prepare("DELETE FROM pastel_ingredientes WHERE ID_pastel = ?");
            $stmt2->execute([$data->ID_pastel]);
            
            foreach($data->ingredientes as $ing) {
                $stmt3 = $db->prepare("INSERT INTO pastel_ingredientes (ID_pastel, ID_Ingrediente) VALUES (?, ?)");
                $stmt3->execute([$data->ID_pastel, $ing->ID_Ingrediente]);
            }
            
            $db->commit();
            echo json_encode(["success" => true]);
        } catch(Exception $e) {
            $db->rollBack();
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
        break;
        
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        $stmt = $db->prepare("DELETE FROM pasteles WHERE ID_pastel = ?");
        if($stmt->execute([$data->id])) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false]);
        }
        break;
}
?>