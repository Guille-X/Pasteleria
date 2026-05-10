<?php
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();
$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    // OBTENER todas las relaciones o una específica
    case 'GET':
        if(isset($_GET['id'])) {
            // Obtener una relación específica por ID_Pastel_ingrediente
            $stmt = $db->prepare("
                SELECT 
                    pi.ID_Pastel_ingrediente,
                    pi.ID_pastel,
                    pi.ID_Ingrediente,
                    p.Nombre as pastel_nombre,
                    i.Nombre as ingrediente_nombre,
                    i.Descripcion as ingrediente_descripcion,
                    i.Fecha_ingreso,
                    i.Fecha_Vencimiento as ingrediente_vencimiento
                FROM pastel_ingredientes pi
                JOIN pasteles p ON pi.ID_pastel = p.ID_pastel
                JOIN ingredientes i ON pi.ID_Ingrediente = i.ID_Ingrediente
                WHERE pi.ID_Pastel_ingrediente = ?
            ");
            $stmt->execute([$_GET['id']]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        } 
        elseif(isset($_GET['pastel_id'])) {
            // Obtener todos los ingredientes de un pastel específico
            $stmt = $db->prepare("
                SELECT 
                    pi.ID_Pastel_ingrediente,
                    pi.ID_pastel,
                    pi.ID_Ingrediente,
                    i.Nombre as ingrediente_nombre,
                    i.Descripcion as ingrediente_descripcion,
                    i.Fecha_ingreso,
                    i.Fecha_Vencimiento as ingrediente_vencimiento
                FROM pastel_ingredientes pi
                JOIN ingredientes i ON pi.ID_Ingrediente = i.ID_Ingrediente
                WHERE pi.ID_pastel = ?
                ORDER BY i.Nombre
            ");
            $stmt->execute([$_GET['pastel_id']]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        elseif(isset($_GET['ingrediente_id'])) {
            // Obtener todos los pasteles que contienen un ingrediente específico
            $stmt = $db->prepare("
                SELECT 
                    pi.ID_Pastel_ingrediente,
                    pi.ID_pastel,
                    pi.ID_Ingrediente,
                    p.Nombre as pastel_nombre,
                    p.Descripcion as pastel_descripcion,
                    p.Preparado_por,
                    p.Fecha_creacion,
                    p.Fecha_Vencimiento
                FROM pastel_ingredientes pi
                JOIN pasteles p ON pi.ID_pastel = p.ID_pastel
                WHERE pi.ID_Ingrediente = ?
                ORDER BY p.Nombre
            ");
            $stmt->execute([$_GET['ingrediente_id']]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        else {
            // Obtener todas las relaciones
            $stmt = $db->query("
                SELECT 
                    pi.ID_Pastel_ingrediente,
                    pi.ID_pastel,
                    pi.ID_Ingrediente,
                    p.Nombre as pastel_nombre,
                    i.Nombre as ingrediente_nombre
                FROM pastel_ingredientes pi
                JOIN pasteles p ON pi.ID_pastel = p.ID_pastel
                JOIN ingredientes i ON pi.ID_Ingrediente = i.ID_Ingrediente
                ORDER BY pi.ID_pastel, i.Nombre
            ");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        break;
    
    // CREAR una nueva relación (asignar ingrediente a pastel)
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        
        // Validar que no exista ya la relación
        $check = $db->prepare("
            SELECT COUNT(*) FROM pastel_ingredientes 
            WHERE ID_pastel = ? AND ID_Ingrediente = ?
        ");
        $check->execute([$data->ID_pastel, $data->ID_Ingrediente]);
        $existe = $check->fetchColumn();
        
        if($existe > 0) {
            echo json_encode([
                "success" => false, 
                "error" => "Este ingrediente ya está asignado a este pastel"
            ]);
            exit();
        }
        
        $stmt = $db->prepare("
            INSERT INTO pastel_ingredientes (ID_pastel, ID_Ingrediente) 
            VALUES (?, ?)
        ");
        
        if($stmt->execute([$data->ID_pastel, $data->ID_Ingrediente])) {
            echo json_encode([
                "success" => true, 
                "id" => $db->lastInsertId(),
                "message" => "Ingrediente asignado al pastel correctamente"
            ]);
        } else {
            echo json_encode([
                "success" => false, 
                "error" => "Error al asignar ingrediente al pastel"
            ]);
        }
        break;
    
    // ELIMINAR una relación (quitar ingrediente de pastel)
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        
        // Soporte para eliminar por ID de relación o por combinación pastel-ingrediente
        if(isset($data->ID_Pastel_ingrediente)) {
            $stmt = $db->prepare("DELETE FROM pastel_ingredientes WHERE ID_Pastel_ingrediente = ?");
            $result = $stmt->execute([$data->ID_Pastel_ingrediente]);
        } 
        elseif(isset($data->ID_pastel) && isset($data->ID_Ingrediente)) {
            $stmt = $db->prepare("
                DELETE FROM pastel_ingredientes 
                WHERE ID_pastel = ? AND ID_Ingrediente = ?
            ");
            $result = $stmt->execute([$data->ID_pastel, $data->ID_Ingrediente]);
        }
        else {
            echo json_encode([
                "success" => false, 
                "error" => "Se requiere ID_Pastel_ingrediente o (ID_pastel + ID_Ingrediente)"
            ]);
            exit();
        }
        
        if($result) {
            echo json_encode([
                "success" => true, 
                "message" => "Relación eliminada correctamente"
            ]);
        } else {
            echo json_encode([
                "success" => false, 
                "error" => "Error al eliminar la relación"
            ]);
        }
        break;
    
    // ACTUALIZAR una relación (cambiar ingrediente a otro en un pastel)
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        
        try {
            $db->beginTransaction();
            
            // Verificar que la nueva combinación no exista ya
            $check = $db->prepare("
                SELECT COUNT(*) FROM pastel_ingredientes 
                WHERE ID_pastel = ? AND ID_Ingrediente = ? 
                AND ID_Pastel_ingrediente != ?
            ");
            $check->execute([$data->ID_pastel, $data->ID_Ingrediente, $data->ID_Pastel_ingrediente]);
            $existe = $check->fetchColumn();
            
            if($existe > 0) {
                $db->rollBack();
                echo json_encode([
                    "success" => false, 
                    "error" => "Este ingrediente ya está asignado a este pastel"
                ]);
                exit();
            }
            
            $stmt = $db->prepare("
                UPDATE pastel_ingredientes 
                SET ID_pastel = ?, ID_Ingrediente = ? 
                WHERE ID_Pastel_ingrediente = ?
            ");
            
            if($stmt->execute([$data->ID_pastel, $data->ID_Ingrediente, $data->ID_Pastel_ingrediente])) {
                $db->commit();
                echo json_encode([
                    "success" => true, 
                    "message" => "Relación actualizada correctamente"
                ]);
            } else {
                $db->rollBack();
                echo json_encode([
                    "success" => false, 
                    "error" => "Error al actualizar la relación"
                ]);
            }
        } catch(Exception $e) {
            $db->rollBack();
            echo json_encode([
                "success" => false, 
                "error" => $e->getMessage()
            ]);
        }
        break;
}
?>