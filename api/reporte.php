<?php
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$query = "
    SELECT 
        p.ID_pastel,
        p.Nombre as pastel_nombre,
        p.Descripcion as pastel_descripcion,
        p.Preparado_por,
        p.Fecha_creacion,
        p.Fecha_Vencimiento as pastel_vencimiento,
        i.ID_Ingrediente,
        i.Nombre as ingrediente_nombre,
        i.Descripcion as ingrediente_descripcion,
        i.Fecha_ingreso,
        i.Fecha_Vencimiento as ingrediente_vencimiento
    FROM pasteles p
    LEFT JOIN pastel_ingredientes pi ON p.ID_pastel = pi.ID_pastel
    LEFT JOIN ingredientes i ON pi.ID_Ingrediente = i.ID_Ingrediente
    ORDER BY p.ID_pastel, i.ID_Ingrediente
";

$stmt = $db->query($query);
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

$reporte = [];
foreach($resultados as $row) {
    if(!isset($reporte[$row['ID_pastel']])) {
        $reporte[$row['ID_pastel']] = [
            'id' => $row['ID_pastel'],
            'nombre' => $row['pastel_nombre'],
            'descripcion' => $row['pastel_descripcion'],
            'preparado_por' => $row['Preparado_por'],
            'fecha_creacion' => $row['Fecha_creacion'],
            'fecha_vencimiento' => $row['pastel_vencimiento'],
            'ingredientes' => []
        ];
    }
    if($row['ID_Ingrediente']) {
        $reporte[$row['ID_pastel']]['ingredientes'][] = [
            'id' => $row['ID_Ingrediente'],
            'nombre' => $row['ingrediente_nombre'],
            'descripcion' => $row['ingrediente_descripcion'],
            'fecha_ingreso' => $row['Fecha_ingreso'],
            'fecha_vencimiento' => $row['ingrediente_vencimiento']
        ];
    }
}

echo json_encode(array_values($reporte));
?>