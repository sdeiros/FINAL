<?php
session_start();

$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "user";

$advogadoLogado = true; // Defina como verdadeiro se o usuário for advogado

$conn = new mysqli($hostname, $username, $password, $dbname);

if ($conn->connect_error) {
    die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
}

if (!$advogadoLogado) {
    header("Location: login.php"); // Redirecionar para a página de login
    exit();
}

$nomeAdvogado = $_SESSION['advogado']['nome']; // Obtém o nome do advogado logado

// Modificando a consulta para incluir apenas casos não ocultos
$sql_casos = "SELECT * FROM pedidos_refugio WHERE advogado_nome = ? AND oculto = 0";
$stmt_casos = $conn->prepare($sql_casos);
$stmt_casos->bind_param("s", $nomeAdvogado);
$stmt_casos->execute();
$result_casos = $stmt_casos->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <link rel="stylesheet" type="text/css" href="css/reset.css" />
    <link rel="stylesheet" type="text/css" href="css/casos.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <title>Postagens</title>

    <!-- FavIcon -->
    <link rel="apple-touch-icon" sizes="60x60" href="./Imagens/FavIcon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./Imagens/FavIcon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./Imagens/FavIcon/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="./Imagens/FavIcon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#00a300">
    <meta name="theme-color" content="#ffffff">
</head>

<body>
    <h1>Meus Casos</h1>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['atualizar'])) {
        $caso_id = $_POST['caso_id'];
        $descricao = $_POST['descricao'];
        $status = $_POST['status'];
        $concluido = isset($_POST['concluido']) ? 1 : 0;

        // Atualiza o caso na tabela pedidos_refugio
        $sql_update = "UPDATE pedidos_refugio SET descricao = ?, status = ?, concluido = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);

        if ($stmt_update) {
            $stmt_update->bind_param("ssii", $descricao, $status, $concluido, $caso_id);
            if ($stmt_update->execute()) {
                echo "Caso atualizado com sucesso!";
            } else {
                echo "Erro ao atualizar o caso: " . $stmt_update->error;
            }
        } else {
            echo "Erro ao preparar a atualização do caso: " . $conn->error;
        }
    }

    if ($result_casos->num_rows > 0) {
        while ($caso = $result_casos->fetch_assoc()) {
            echo '<div>';
            echo '<h3>Caso #' . $caso['id'] . '</h3>';
            echo '<p>Usuário: ' . $caso['usuario'] . '</p>'; // Exibe o nome do usuário
    
            // Exibe a foto
            echo '<img src="' . $caso['foto'] . '" alt="Foto do caso">';

            // Exibe o botão "Baixar Documento" apenas se houver um documento
            if (!empty($caso['documento'])) {
                echo '<a href="' . $caso['documento'] . '">Baixar Documento</a>';
            }

            echo '<form method="POST" enctype="multipart/form-data">';
            echo '<input type="hidden" name="caso_id" value="' . $caso['id'] . '">';
            echo '<textarea name="descricao">' . $caso['descricao'] . '</textarea>';
            echo '<input type="text" name="status" value="' . $caso['status'] . '">';
            echo '<label><input type="checkbox" name="concluido" value="1" ' . ($caso['concluido'] ? 'checked' : '') . '> Concluído</label>';
            echo '<input type="submit" name="atualizar" value="Atualizar">';

            // Função para marcar um caso como oculto
            function marcarComoOculto($caso_id, $conn)
            {
                $sql_ocultar = "UPDATE pedidos_refugio SET oculto = 1 WHERE id = ?";
                $stmt_ocultar = $conn->prepare($sql_ocultar);

                if ($stmt_ocultar) {
                    $stmt_ocultar->bind_param("i", $caso_id);
                    if ($stmt_ocultar->execute()) {
                        echo "Caso marcado como oculto com sucesso!";
                    } else {
                        echo "Erro ao marcar o caso como oculto: " . $stmt_ocultar->error;
                    }
                } else {
                    echo "Erro ao preparar a marcação como oculto: " . $conn->error;
                }
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['abandonar'])) {
                    $caso_id = $_POST['abandonar'];
                    marcarComoOculto($caso_id, $conn);
                } elseif (isset($_POST['finalizar'])) {
                    $caso_id = $_POST['finalizar'];
                    marcarComoOculto($caso_id, $conn);
                }
            }

            // Adiciona botões de Abandonar e Finalizar
            echo '<button type="submit" name="abandonar" value="' . $caso['id'] . '">Abandonar</button>';
            echo '<button type="submit" name="finalizar" value="' . $caso['id'] . '">Finalizar</button>';

            echo '</form><hr>';

            echo '</div>';
        }
    } else {
        echo 'Nenhum caso encontrado.';
    }

    $conn->close();
    ?>


</body>

</html>