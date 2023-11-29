<?php
session_start();

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Configurações do banco de dados
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $dbname = "user";
    $usertable = "cadastros";

    // Conexão com o banco de dados
    $conn = new mysqli($hostname, $username, $password, $dbname);

    // Verifica se houve algum erro na conexão
    if ($conn->connect_error) {
        die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
    }

    // Obtém os valores enviados pelo formulário
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Consulta o banco de dados para verificar se o usuário está registrado como usuário normal
    $sqlUsuario = "SELECT * FROM $usertable WHERE email = '$email' AND senha = '$senha' AND tipo = 'usuario'";
    $resultUsuario = $conn->query($sqlUsuario);

    // Consulta o banco de dados para verificar se o usuário está registrado como advogado
    $sqlAdvogado = "SELECT * FROM advogados WHERE email = '$email' AND senha = '$senha'";
    $resultAdvogado = $conn->query($sqlAdvogado);

    if ($resultUsuario->num_rows > 0) {
        // Usuário encontrado como usuário normal
        $rowUsuario = $resultUsuario->fetch_assoc();
        $nomeUsuario = $rowUsuario['nome'];

        $_SESSION['usuario'] = array(
            'nome' => $nomeUsuario
        );

        // Redirecionar para a página de postagens
        header("Location: postagens.php");
        exit();
    } elseif ($resultAdvogado->num_rows > 0) {
        // Usuário encontrado como advogado
        $rowAdvogado = $resultAdvogado->fetch_assoc();
        $nomeAdvogado = $rowAdvogado['nome'];

        $_SESSION['advogado'] = array(
            'nome' => $nomeAdvogado
        );

        // Redirecionar para a página de perfil do advogado
        header("Location: advogado.php");
        exit();
    } else {
        // Usuário não foi encontrado, exibir mensagem de erro
        header("Location: Erro.html");
        exit();
    }

    // Fecha a conexão com o banco de dados
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/stylelogin.css" />
    <link rel="stylesheet" type="text/css" href="css/responsivologin.css" />
    <link rel="stylesheet" type="text/css" href="css/reset.css" />
    <title>Novas Raízes - Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@600&family=Quando&display=swap" rel="stylesheet">

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
    <div class="fundo"></div>
    <h1>Acesso Rápido</h1>
    <p class="ou">ou</p>
    <img class="linhas-main" src="imagens/Linhas.png" />

    <div class="image-slideshow">
        <img class="image fade" src="./Imagens/login-cad/imagens L2.png" alt="Obra X de Candido Portinari"
            title="NOMEOBRA">
        <img class="image fade" src="./Imagens/login-cad/imagens L3.png" alt="Obra X de Candido Portinari"
            title="NOMEOBRA">
        <img class="image fade" src="./Imagens/login-cad/imagens L4.png" alt="Obra X de Candido Portinari"
            title="NOMEOBRA">
        <img class="image fade" src="./Imagens/login-cad/imagens L5.png" alt="Obra X de Candido Portinari"
            title="NOMEOBRA">
        <img class="image fade" src="./Imagens/login-cad/imagens L6.png" alt="Obra X de Candido Portinari"
            title="NOMEOBRA">
    </div>
    <script src="main.js"></script>
    <img class="logo" src="Imagens/Logo.png" alt="logo novas raízes">
    <div id="img-main">
        <a href="https://www.google.com/" target="_blank"><img class="google-icon-main" src="Imagens/Google.png"
                alt="LoginGoogle" title="Login com Google"></a>
        <a href="" target="_blank"><img class="facebook-icon-main" src="Imagens/Facebook.png" alt="LoginFacebook"
                title="Login com Facebook"></a>
        <a href="" target="_blank"><img class="twitter-icon-main" src="Imagens/Twitter.png" alt="LoginTwitter"
                title="Login com Twitter"></a>
        <a href="" target="_blank"><img class="apple-icon-main" src="Imagens/Apple.png" alt="LoginApple"
                title="Login com iCloud"></a>
    </div>

    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input name="email" type="text" id="login" placeholder="E-mail"><br>
        <input name="senha" type="password" id="senha" placeholder="Senha"><br>
        <input type="submit" value="Entrar" id="cadastrar" name="cadastrar">
    </form>

    <a href="esquecisenha.html">
        <h6 class="esqueci-senha">Esqueci minha senha</h6>
    </a>

    <button onclick="window.location.href='cadastro.php';"
        style="border-radius: 10px; width: 14%; top: 90%; height: 5%; position:absolute; border: none; font-family: Inter; color: #FFF; font-weight: 700; text-shadow: -2px 6px 40px rgba(0, 0, 0, 0.50); font-size: 18px; background: none; left: 86%; cursor: pointer;">
        Cadastrar
    </button>
    <?php
    if (isset($_SESSION["error"])) {
        echo "<div class='alert alert-danger' role='alert'>
        <h3>E-mail e/ou senha inválido(s)!</h3>
        </div>";
        $_SESSION["error"] = null;
    }
    ?>
</body>

</html>