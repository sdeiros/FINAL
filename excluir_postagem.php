<?php
// Verificar se o ID da postagem foi fornecido
if (!isset($_GET['id'])) {
    echo "ID da postagem não fornecido.";
    exit;
}

$postagemID = $_GET['id'];

// Conectar ao banco de dados
$conexao = new mysqli('localhost', 'root', '', 'user');
if ($conexao->connect_error) {
    die('Erro de conexão: ' . $conexao->connect_error);
}

// Verificar se a postagem existe
$query = "SELECT * FROM postagens WHERE id = $postagemID";
$result = $conexao->query($query);

if ($result->num_rows == 0) {
    echo "Postagem não encontrada.";
    exit;
}

// Excluir a postagem do banco de dados
$deleteQuery = "DELETE FROM postagens WHERE id = $postagemID";

if ($conexao->query($deleteQuery) === TRUE) {
    $mensagem = "Postagem excluída com sucesso.";
    echo "<script>alert('$mensagem'); window.location.href = 'postagens.php';</script>";
} else {
    echo "Erro ao excluir postagem: " . $conexao->error;
}

$conexao->close();
?>
