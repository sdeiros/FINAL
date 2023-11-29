<?php
// Verificar se o ID da postagem foi fornecido
if (!isset($_GET['postagem_id'])) {
    echo "ID da postagem não fornecido.";
    exit;
}

// Obter o ID da postagem da query string
$postagemID = $_GET['postagem_id'];

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

// Obter os detalhes da postagem
$postagem = $result->fetch_assoc();

// Lidar com o envio do formulário de edição
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $autor = $_POST['autor'];
    $conteudo = $_POST['conteudo'];

    // Verificar se a ação é excluir a postagem
    if (isset($_POST['excluir'])) {
        // Excluir a postagem e redirecionar para a página de postagens
        $query = "DELETE FROM postagens WHERE id = $postagemID";
        if ($conexao->query($query) === TRUE) {
            echo "Postagem excluída com sucesso.";
            header("Location: postagens.php");
            exit;
        } else {
            echo "Erro ao excluir postagem: " . $conexao->error;
        }
    } else {
        // Verificar se arquivos de imagens foram enviados
        if (!empty($_FILES['imagens']['name'][0])) {
            $imagensArray = $_FILES['imagens'];
            $imagensExistentes = json_decode($postagem['imagens'], true) ?? [];

            // Diretório permanente para armazenar as imagens
            $diretorioPermanente = 'caminho/do/diretorio/permanente/';

            // Percorrer as imagens enviadas e movê-las para o diretório permanente
            foreach ($imagensArray['tmp_name'] as $key => $tmp_name) {
                $imagemNome = $imagensArray['name'][$key];
                $imagemDestino = $diretorioPermanente . $imagemNome;

                if (move_uploaded_file($tmp_name, $imagemDestino)) {
                    $imagensExistentes[] = $imagemDestino;
                } else {
                    echo "Erro ao fazer upload do arquivo.";
                    exit;
                }
            }

            // Converter o array de imagens em uma string JSON
            $imagensString = json_encode($imagensExistentes);

            // Atualizar as informações da postagem no banco de dados
            $query = "UPDATE postagens SET autor = '$autor', conteudo = '$conteudo', imagens = '$imagensString' WHERE id = $postagemID";
            if ($conexao->query($query) === TRUE) {
                echo "Postagem atualizada com sucesso.";
                header("Location: postagens.php");
                exit;
            } else {
                echo "Erro ao atualizar postagem: " . $conexao->error;
            }
        } else {
            // Atualizar as informações da postagem no banco de dados (sem alterar as imagens)
            $query = "UPDATE postagens SET autor = '$autor', conteudo = '$conteudo' WHERE id = $postagemID";
            if ($conexao->query($query) === TRUE) {
                echo "Postagem atualizada com sucesso.";
                header("Location: postagens.php");
                exit;
            } else {
                echo "Erro ao atualizar postagem: " . $conexao->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Editar Postagem</title>
</head>
<style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
        }

        h1 {
            color: #333;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f4f4f4;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        input[type="submit"],
        button {
            background-color: #4caf50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover,
        button:hover {
            background-color: #45a049;
        }

        input[type="file"] {
            margin-top: 10px;
        }

        img {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
<body>
    <h1>Editar Postagem</h1>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?postagem_id=' . $postagemID; ?>" method="post"
        enctype="multipart/form-data">
        <label for="autor">Autor:</label>
        <input type="text" name="autor" id="autor" value="<?php echo $postagem['autor']; ?>" readonly><br><br>

        <label for="conteudo">Conteúdo:</label><br>
        <textarea name="conteudo" id="conteudo" rows="4" cols="50"
            required><?php echo $postagem['conteudo']; ?></textarea><br><br>

        <?php if (!empty($postagem['imagens'])): ?>
            <h3>Imagens Anexadas</h3>
            <?php
            $imagens = json_decode($postagem['imagens'], true);
            foreach ($imagens as $index => $imagem):
                ?>
                <div>
                    <img src="<?php echo $imagem; ?>" alt="Imagem da postagem" width="200"><br>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <br>
        <input type="submit" value="Salvar Alterações">
        <button type="submit" name="excluir"
            onclick="return confirm('Tem certeza que deseja excluir a postagem?')">Excluir Postagem</button>
    </form>
</body>

</html>

