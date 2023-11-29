<?php
// Simulando conexão com o banco de dados
$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "user";
$usertable = "cadastros";

// Simulação de autenticação de usuário advogado
$advogadoLogado = true; // Defina como verdadeiro se o usuário for advogado
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" type="text/css" href="css/reset.css" />
    <link rel="stylesheet" type="text/css" href="css/advogado.css" />
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
    <title>Tela do Advogado</title>
</head>

<body>
    <h1>Bem-vindo, Advogado!</h1>
    <a href="solicitacoes.php"><button>Ver Solicitações</button></a>
    <?php
    if ($advogadoLogado) {
        echo '<a href="casos.php"><button>Ver Meus Casos</button></a>';
    }
    ?>

    <?php
    session_start();

    // Verifica se o advogado está logado
    if (isset($_SESSION['advogado'])) {
        // Conexão com o banco de dados
        $hostname = "localhost";
        $username = "root";
        $password = "";
        $dbname = "user";
        $conn = new mysqli($hostname, $username, $password, $dbname);

        // Verifica se houve algum erro na conexão
        if ($conn->connect_error) {
            die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
        }

    } else {
        echo "<p>Você não está logado como advogado.</p>";
    }
    ?>
    <a href="login.php"><button>Logout</button></a>
</body>

</html>