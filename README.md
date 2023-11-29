# Novas Raizes
<p>Este projeto é destinado a Imigrantes e Refugiados que buscam uma vida melhor no Brasil.<Br>
Nele, haverá informações sobre auxilio disponibilizado pelo Governo, ONG's com o objetivo de ajudar estas pessoas, vagas de emprogo voltadas para eles, formas de coneguir a legalização de documntos no Brasil e dentre outras informações.
</p>
<hr>
<h2>Conectando o PHP - Cadastro</h2>
<li> Baixe ou abra caso já esteja intalado na sua máquina o Wamp <a href="https://www.wampserver.com/en/download-wampserver-64bits/" target="_blank" rel="noopener noreferrer">Baixe aqui</a><br>
<li>Com ele já Intalada/Aberto faça a inicialização
<li>Mova seus arquivos PHP para a pasta <i>WWW</i> dentro do DiscoC
<li>Na barra de pesquisa/url Pesquisa por <a href="http://localhost/" target="_blank" rel="noopener noreferrer">LocalHost</a>
<li>Selecione a opção PhPMyAdmin e execute o código abaixo
</li>
<hr>
<h2>Criando o Banco de Dados do PHP - Cadastro</h2>
<p>Criando DATABASE</p>
<code> CREATE SCHEMA `user`;
</code>
<hr>
<p>Dentro da DATABASE digite:</p>
 <code>CREATE TABLE cadastros (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(255),
  email VARCHAR(255),
  senha VARCHAR(255),
  tipo ENUM('usuario', 'advogado') NOT NULL,
  oab VARCHAR(20),
  naturalidade VARCHAR(100)
);
</code>
<hr>
<p>Dentro da DATABASE digite o banco de dados dos Advogados:</p>
<code>CREATE TABLE IF NOT EXISTS `advogados` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cadastro_id` int DEFAULT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `oab` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cadastro_id` (`cadastro_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
</code>
<hr>
<p>Dentro da DATABASE digite o banco de dados do Blog:</p>
<code>CREATE TABLE postagens (
  id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  autor VARCHAR(100) NOT NULL,
  conteudo TEXT NOT NULL,
  imagens TEXT,
  data_publicacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
</code>
<hr>
<p>Dentro da DATABASE digite o banco de dados das Solicitações:</p>
<code>CREATE TABLE solicitacoes (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario` varchar(255) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `conteudo` text NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `documento` varchar(255) DEFAULT NULL,
  `data_solicitacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
COMMIT;
</code>
<hr>
<p>Dentro da DATABASE digite o banco de dados do Pedidos de Refugio:</p>
<code>CREATE TABLE pedidos_refugio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(255) NOT NULL,
    descricao TEXT NOT NULL,
    foto VARCHAR(255) DEFAULT NULL,
    documento VARCHAR(255) DEFAULT NULL,
    status VARCHAR(50) NOT NULL,
    advogado_nome VARCHAR(255) DEFAULT NULL,
    concluido TINYINT(1) NOT NULL DEFAULT 0,
    oculto BOOLEAN DEFAULT 0,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

</code>
