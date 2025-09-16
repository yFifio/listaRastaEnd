Lista Rasta
📝 Descrição
Lista Rasta é uma aplicação web de lista de tarefas (to-do list) com uma temática inspirada na cultura Rastafári. O sistema permite que os usuários se cadastrem, façam login e gerenciem suas "missões" diárias. Cada tarefa pode ser criada com uma descrição e um nível de dificuldade (Fácil, Normal, Difícil).

Além do gerenciamento de tarefas, a plataforma conta com uma área de relaxamento ("Momento com Jah"), uma página com a biografia da lenda Bob Marley e uma coluna interativa com curiosidades sobre a cultura Rasta.

🛠️ Tecnologias Utilizadas
Back-end: PHP Orientado a Objetos

Front-end: HTML5, CSS3, JavaScript

Banco de Dados: MySQL

Servidor: Apache (utilizado com XAMPP, WAMP ou MAMP)

🚀 Instalação e Execução
Siga os passos abaixo para configurar e executar o projeto em seu ambiente de desenvolvimento local.

Pré-requisitos
Um ambiente de servidor local como XAMPP (que inclua Apache, MySQL e PHP).

Um cliente de banco de dados, como o phpMyAdmin (incluído no XAMPP).

Git instalado em sua máquina.

Passo a Passo
1. Clonar o Repositório

Abra o terminal na pasta htdocs do seu XAMPP (ex: C:/xampp/htdocs) e execute o comando:

Bash

git clone [URL_DO_SEU_REPOSITORIO_AQUI]
2. Configurar o Banco de Dados

Inicie os módulos Apache e MySQL no seu painel de controle do XAMPP.

Acesse o phpMyAdmin em http://localhost/phpmyadmin.

Crie um novo banco de dados com o nome to_do, utilizando o collation utf8mb4_general_ci.

Selecione o banco to_do e vá para a aba SQL. Copie e cole o script abaixo para criar e popular as tabelas necessárias:

SQL

-- Cria a tabela de usuários
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Cria a tabela de dificuldades
CREATE TABLE `difficulty` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `level` VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insere os níveis de dificuldade padrão
INSERT INTO `difficulty` (`id`, `level`) VALUES
(1, 'fácil'),
(2, 'normal'),
(3, 'difícil');

-- Cria a tabela de tarefas (missões)
CREATE TABLE `tasks` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `description` TEXT NOT NULL,
  `completed` BOOLEAN DEFAULT 0,
  `difficulty_id` INT NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`difficulty_id`) REFERENCES `difficulty`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
3. Configurar as Credenciais do Banco

Abra o arquivo database/Database.php e ajuste as variáveis com as suas credenciais do MySQL (a senha pode variar dependendo da sua configuração):

PHP

private $hostname = 'localhost';
private $database = 'to_do';
private $username = 'root';
private $password = ''; // Coloque sua senha do MySQL aqui, se houver
private $port = 3306;
4. Executar a Aplicação

Com o Apache rodando, abra seu navegador e acesse o link para a pasta do projeto. O caminho correto deve incluir a pasta public.

Exemplo: http://localhost/listaRastaEnd/public/

Você será direcionado para a página de login. Crie uma conta e comece a usar!

👥 Integrantes
Lucas de Fiori Viudes

Lucas Gozer Lopes

Vitto Lorenzo Barboza Legnani

🔗 Diagrama Entidade-Relacionamento (DER)
O DER do projeto está disponível na pasta /DER do repositório.
