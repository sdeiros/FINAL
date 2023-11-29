<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar se o ID da postagem foi fornecido
    if (!isset($_POST['postagem_id'])) {
        echo "ID da postagem não fornecido.";
        exit;
    }

    $postagemID = $_POST['postagem_id'];
    $autor = $_POST['autor'];
    $conteudo = $_POST['conteudo'];
    $imagens = [];

    // Conectar ao banco de dados
    $conexao = new mysqli('localhost', 'root', '', 'user');
    if ($conexao->connect_error) {
        die('Erro de conexão: ' . $conexao->connect_error);
    }

    // Obter informações da postagem original
    $query = "SELECT * FROM postagens WHERE id = $postagemID";
    $result = $conexao->query($query);

    if ($result->num_rows == 0) {
        echo "Postagem não encontrada.";
        exit;
    }

    $postagem = $result->fetch_assoc();

    // Lidar com a exclusão de imagens
    if (isset($_POST['excluir_imagens'])) {
        $imagensExcluir = $_POST['excluir_imagens'];

        foreach ($imagensExcluir as $index) {
            if (isset($postagem['imagens'][$index])) {
                // Obter o caminho da imagem
                $imagemExcluir = $postagem['imagens'][$index];

                // Excluir a imagem do diretório permanente
                if (file_exists($imagemExcluir)) {
                    unlink($imagemExcluir);
                }

                // Remover a imagem do array de imagens
                unset($postagem['imagens'][$index]);
            }
        }

        // Reindexar o array de imagens
        $postagem['imagens'] = array_values($postagem['imagens']);
    }

    // Verificar se arquivos de imagens ou gifs foram enviados
    if (isset($_FILES['imagens'])) {
        $imagensArray = $_FILES['imagens'];

        foreach ($imagensArray['name'] as $key => $name) {
            $imagem = $imagensArray['tmp_name'][$key];

            if ($imagensArray['size'][$key] > 100 * 1024 * 1024) {
                echo "Tamanho do arquivo excede o limite permitido.";
                exit;
            }

            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($imagensArray['type'][$key], $allowedTypes)) {
                echo "Tipo de arquivo inválido. Apenas imagens JPEG, PNG e GIF são permitidas.";
                exit;
            }

            $destino = 'caminho/do/diretorio/permanente/' . $name;
            if (move_uploaded_file($imagem, $destino)) {
                $postagem['imagens'][] = $destino;
            } else {
                echo "Erro ao fazer upload do arquivo.";
                exit;
            }
        }
    }

    // Atualizar as informações da postagem no banco de dados
    $query = "UPDATE postagens SET autor = '$autor', conteudo = '$conteudo', imagens = '" . json_encode($postagem['imagens']) . "' WHERE id = $postagemID";
    if ($conexao->query($query) === TRUE) {
        echo "Postagem atualizada com sucesso.";
        header("Location: postagens.php");
        exit;
    } else {
        echo "Erro ao atualizar postagem: " . $conexao->error;
    }

    $conexao->close();
}
?>