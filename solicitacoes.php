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

if ($advogadoLogado && isset($_GET['id'])) {
    $caso_id = $_GET['id'];

    // Consulta o caso na tabela solicitacoes
    $sql_select = "SELECT * FROM solicitacoes WHERE id = ?";
    $stmt_select = $conn->prepare($sql_select);

    if ($stmt_select) {
        $stmt_select->bind_param("i", $caso_id);
        $stmt_select->execute();
        $resultado_select = $stmt_select->get_result();

        if ($resultado_select->num_rows > 0) {
            $caso = $resultado_select->fetch_assoc();

            // Obtém o nome do advogado logado
            $nomeAdvogado = $_SESSION['advogado']['nome'];

            $sql_insert = "INSERT INTO pedidos_refugio (usuario, descricao, foto, documento, status, advogado_nome) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);

            if ($stmt_insert) {
                $status = "Em Andamento";
                $stmt_insert->bind_param("ssssss", $caso['usuario'], $caso['conteudo'], $caso['foto'], $caso['documento'], $status, $nomeAdvogado);

                if ($stmt_insert->execute()) {
                    // Remova o caso da tabela solicitacoes
                    $sql_delete = "DELETE FROM solicitacoes WHERE id = ?";
                    $stmt_delete = $conn->prepare($sql_delete);
                    $stmt_delete->bind_param("i", $caso_id);

                    if ($stmt_delete->execute()) {
                        echo "Caso foi pego com sucesso!";
                    } else {
                        echo "Erro ao remover o caso da lista de solicitações.";
                    }
                } else {
                    echo "Erro ao pegar o caso: " . $stmt_insert->error;
                }
            } else {
                echo "Erro ao preparar a inserção do caso: " . $conn->error;
            }

        } else {
            echo "Caso não encontrado.";
        }
    } else {
        echo "Erro ao preparar a consulta do caso.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <!-- Cabeçalho e metatags aqui -->
</head>

<style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }

        h1, h2, h3 {
            width: 80%;
            position: relative;
            color: #333;
            left: 1%;
        }

        a {
            text-decoration: none;
            color: #007BFF;
            position: relative;
            left: 1%;
        }

        button {
            background-color: #007BFF;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        div {
            background-color: #fff;
            margin: 20px;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        img {
            width: 14%;
            height: auto;
            margin-top: 10px;
        }
    </style>

<body>
    <h1>Solicitações</h1>

    <?php
    if ($advogadoLogado) {
        echo '<a href="casos.php"><button>Ver Meus Casos</button></a>';
    }
    ?>

    <h2>Lista de Solicitações</h2>

    <?php
    $conn = new mysqli($hostname, $username, $password, $dbname);

    if ($conn->connect_error) {
        die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
    }

    $sql = "SELECT * FROM solicitacoes";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div>';
            echo '<p>Nome do Usuário: ' . $row['usuario'] . '</p>';
            echo '<h3>' . $row['titulo'] . '</h3>';
            echo '<p>' . $row['conteudo'] . '</p>';
            echo '<img src="' . $row['foto'] . '" alt="Foto">';
    
            // Verifica se há um documento associado à solicitação
            if (!empty($row['documento'])) {
                echo '<a href="' . $row['documento'] . '">Baixar Documento</a><br>';
            }
    
            if ($advogadoLogado) {
                echo '<a href="solicitacoes.php?id=' . $row['id'] . '">Pegar Caso</a>';
            }
    
            echo '</div>';
        }
    } else {
        echo 'Nenhuma solicitação encontrada.';
    }
    
    $conn->close();
    ?>
</body>

</html>