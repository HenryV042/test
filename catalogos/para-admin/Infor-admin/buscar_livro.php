<?php
require_once '../dependencies/config.php';

if (isset($_GET['numero_registro'])) {
    $numero_registro = $_GET['numero_registro'];

    try {
        $sql = "SELECT * FROM livros WHERE numero_registro = :numero_registro";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':numero_registro', $numero_registro);
        $stmt->execute();
        $livro = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($livro) {
            echo json_encode($livro);
        } else {
            echo json_encode(['error' => 'Livro não encontrado']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Erro na consulta: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Número de registro não fornecido']);
}
?>
