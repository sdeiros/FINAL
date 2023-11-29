<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/stylecad.css" />
    <link rel="stylesheet" type="text/css" href="css/reesponsive.css" />
    <link rel="stylesheet" type="text/css" href="css/reset.css" />
    <title>Novas Raízes - Cadastro</title>
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
    <img class="linhas-main" src="Imagens/Linhas.png" />

    <div class="image-slideshow">
        <img class="image fade" src="./Imagens/login-cad/imagens L2.png" alt="Obra X de Candido Portinari" title="NOMEOBRA">
        <img class="image fade" src="./Imagens/login-cad/imagens L3.png" alt="Obra X de Candido Portinari" title="NOMEOBRA">
        <img class="image fade" src="./Imagens/login-cad/imagens L4.png" alt="Obra X de Candido Portinari" title="NOMEOBRA">
        <img class="image fade" src="./Imagens/login-cad/imagens L5.png" alt="Obra X de Candido Portinari" title="NOMEOBRA">
        <img class="image fade" src="./Imagens/login-cad/imagens L6.png" alt="Obra X de Candido Portinari" title="NOMEOBRA">
    </div>
    <script src="main.js"></script>
    <img class="logo" src="./Imagens/Logo.png" alt="logo novas raízes">

    <div id="img-main">
        <a href="https://www.google.com/" target="_blank"><img class="google-icon-main" src="Imagens/Google.png" alt="LoginGoogle" title="Login com Google"></a>
        <a href="" target="_blank"><img class="facebook-icon-main" src="Imagens/Facebook.png" alt="LoginFacebook" title="Login com Facebook"></a>
        <a href="" target="_blank"><img class="twitter-icon-main" src="Imagens/Twitter.png" alt="LoginTwitter" title="Login com Twitter"></a>
        <a href="" target="_blank"><img class="apple-icon-main" src="Imagens/Apple.png" alt="LoginApple" title="Login com iCloud"></a>
    </div>

    <?php
    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $hostname = "localhost";
        $username = "root";
        $password = "";
        $dbname = "user";
        $usertable = "cadastros";

        $conn = new mysqli($hostname, $username, $password, $dbname);

        if ($conn->connect_error) {
            die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
        }

        $onde = $_POST['onde'];
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        $tipo = $_POST['tipo'];

        if ($tipo === 'advogado') {
            $oab = $_POST['oab'];

            $sqlAdvogado = "INSERT INTO advogados (nome, email, senha, oab) VALUES (?, ?, ?, ?)";
            $stmtAdvogado = $conn->prepare($sqlAdvogado);
            $stmtAdvogado->bind_param("ssss", $nome, $email, $senha, $oab);
            $stmtAdvogado->execute();
        } else {
            $sql = "INSERT INTO $usertable (naturalidade, nome, email, senha, tipo) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $onde, $nome, $email, $senha, $tipo);
            $stmt->execute();
        }

        $conn->close();
        header('Location: login.php');
        exit;
    }
    ?>

    <form method="POST" action="cadastro.php" class="advogado-form">
        <select name="onde">
            <option value="" default selected>De onde você é?</option>
            <optgroup label="América do Norte">
                <option>Canadá</option>
                <option>Estados Unidos</option>
                <option>México</option>
            </optgroup>
            <optgroup label="América Central">
                <option>Belize</option>
                <option>Costa Rica</option>
                <option>El Salvador</option>
                <option>Guatemala</option>
                <option>Honduras</option>
                <option>Nicarágua</option>
                <option>Panamá</option>
            </optgroup>
            <optgroup label="América do Sul">
                <option>Argentina</option>
                <option>Bolívia</option>
                <option>Brasil</option>
                <option>Chile</option>
                <option>Colômbia</option>
                <option>Equador</option>
                <option>Guiana</option>
                <option>Paraguai</option>
                <option>Peru</option>
                <option>Suriname</option>
                <option>Uruguai</option>
                <option>Venezuela</option>
            </optgroup>
        </select>

        <input name="nome" type="text" id="nome" placeholder="Nome"><br>
        <input name="email" type="email" id="email" placeholder="E-mail"><br>
        <input name="senha" type="password" id="senha" placeholder="Senha"><br>

        <!-- Caixas de seleção para escolher Usuário ou Advogado -->

        <div class="radio-container">
            <style>
                /* Estilo para centralizar o radio-container */
                .radio-container {
                    position: absolute;
                    display: flex;
                    justify-content: center;
                    margin-top: 40px;
                    /* Ajuste conforme necessário */
                    left: 71%;
                    color: #ffffff;
                    top: 20%;
                }

                .radio-container input[type="radio"] {
                    margin: 0 10px;
                }
            </style>

            <label class="radio-label">
                <input type="radio" name="tipo" value="usuario" checked> Usuário
            </label>
            <label class="radio-label">
                <input type="radio" name="tipo" value="advogado"> Advogado
            </label>
        </div>

        <div id="advogado-fields" class="select-container" style="display: none;">
            <style>
                /* Estilo para o campo "Número da OAB" */
                #oab {
                    box-sizing: border-box;

                    position: absolute;
                    left: 59%;
                    right: 7%;
                    top: 68.38%;
                    bottom: 24.77%;

                    background: #FFFFFF;
                    border: 2px solid #F58008;
                    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.4);
                    border-radius: 14px;

                    font-family: 'Inter';
                    font-style: normal;
                    font-weight: 600;
                    font-size: 24px;
                    line-height: 29px;
                    color: #717171;
                    padding-left: 2rem;

                    transition: 1s;
                }
            </style>
            <input name="oab" type="text" id="oab" placeholder="Digite o número da OAB"><br>
        </div>

        <input type="submit" value="Criar Conta" id="criar" name="cadastrar">

    </form>

    <script>
        // Obtém a referência para os radio buttons
        const usuarioRadio = document.querySelector('input[name="tipo"][value="usuario"]');
        const advogadoRadio = document.querySelector('input[name="tipo"][value="advogado"]');

        // Obtém a referência para o div que envolve os campos do advogado
        const advogadoFieldsDiv = document.getElementById('advogado-fields');

        // Obtém a referência para o botão "Realizar Cadastro"
        const criarButton = document.getElementById('criar');

        // Adiciona um ouvinte de evento aos radio buttons
        usuarioRadio.addEventListener('change', toggleAdvogadoFields);
        advogadoRadio.addEventListener('change', toggleAdvogadoFields);

        // Função para alternar a exibição dos campos do advogado
        function toggleAdvogadoFields() {
            if (advogadoRadio.checked) {
                advogadoFieldsDiv.style.display = 'block';
                criarButton.classList.add('slide-down'); // Adicione a classe para iniciar a animação
            } else {
                advogadoFieldsDiv.style.display = 'none';
                criarButton.classList.remove('slide-down'); // Remova a classe para interromper a animação
            }
        }
    </script>
</body>

</html>