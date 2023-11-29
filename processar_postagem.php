<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $autor = $_POST['autor'];
    $conteudo = $_POST['conteudo'];

    // Conectar ao banco de dados
    $conexao = new mysqli('localhost', 'root', '', 'user');
    if ($conexao->connect_error) {
        die('Erro de conexão: ' . $conexao->connect_error);
    }

    // Inserir a nova postagem no banco de dados
    $query = "INSERT INTO postagens (autor, conteudo) VALUES ('$autor', '$conteudo')";

    if ($conexao->query($query) === TRUE) {
        echo "Postagem realizada com sucesso.";
    } else {
        echo "Erro ao realizar postagem: " . $conexao->error;
    }

    $conexao->close();
}
?>
