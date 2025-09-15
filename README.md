Lista Rasta
📝 Descrição
Lista Rasta é uma aplicação web de lista de tarefas (to-do list) com uma temática inspirada na cultura Rastafári. O sistema permite que os usuários se cadastrem, façam login e gerenciem suas "missões" diárias. Cada tarefa pode ser criada com uma descrição e um nível de dificuldade (Fácil, Normal, Difícil).

Além do gerenciamento de tarefas, a plataforma conta com uma área de relaxamento ("Momento com Jah") e uma página com curiosidades e informações sobre a cultura Rasta e a lenda do reggae, Bob Marley.

🛠️ Tecnologias Utilizadas
Back-end: PHP

Front-end: HTML5, CSS3, JavaScript

Banco de Dados: MySQL

Servidor: Apache (geralmente utilizado com XAMPP, WAMP ou MAMP)

🚀 Instalação e Execução
Siga os passos abaixo para configurar e executar o projeto em seu ambiente de desenvolvimento local.

Pré-requisitos
Um ambiente de servidor local como XAMPP ou similar (que inclua Apache, MySQL e PHP).

Um cliente de banco de dados, como o phpMyAdmin (incluído no XAMPP) ou DBeaver.

Git instalado em sua máquina.

Passo a Passo
Clonar o Repositório
Abra o terminal na pasta onde deseja salvar o projeto (por exemplo, C:/xampp/htdocs no Windows) e execute o seguinte comando:

Bash

git clone [URL_DO_SEU_REPOSITORIO_AQUI]
Configurar o Banco de Dados

Inicie os módulos Apache e MySQL no seu painel XAMPP.

Acesse o phpMyAdmin em http://localhost/phpmyadmin.

Crie um novo banco de dados com o nome to_do, conforme especificado no arquivo database/Database.php.

Execute o script SQL abaixo para criar as tabelas users e tasks:

SQL

CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL
);

CREATE TABLE `tasks` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `description` TEXT NOT NULL,
  `difficulty` ENUM('fácil', 'normal', 'dificil') NOT NULL,
  `completed` BOOLEAN DEFAULT 0,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE dificuldade (
id INT AUTO_INCREMENT PRIMARY KEY,
nivel VARCHAR(50) NOT NULL UNIQUE
);

Configurar as Credenciais do Banco

Abra o arquivo database/Database.php.

Ajuste as seguintes variáveis com as suas credenciais do MySQL (a senha pode variar dependendo da sua configuração):

PHP

private $hostname = 'localhost';
private $database = 'to_do';
private $username = 'root';
private $password = ''; // Coloque sua senha do MySQL aqui, se houver
private $port = 3306;
Executar a Aplicação

Com o Apache rodando, abra seu navegador e acesse o link para a pasta do projeto.

Exemplo: http://localhost/listaRastaEnd/public/

Você será direcionado para a página de login. Crie uma conta e comece a usar!

👥 Integrantes
Lucas de Fiori Viudes
Lucas Gozer Lopes
Vitto Lorenzo Barboza Legnani

🔗 Diagrama Entidade-Relacionamento (DER)
Lista Rasta
📝 Descrição
Lista Rasta é uma aplicação web de lista de tarefas (to-do list) com uma temática inspirada na cultura Rastafári. O sistema permite que os usuários se cadastrem, façam login e gerenciem suas "missões" diárias. Cada tarefa pode ser criada com uma descrição e um nível de dificuldade (Fácil, Normal, Difícil).

Além do gerenciamento de tarefas, a plataforma conta com uma área de relaxamento ("Momento com Jah") e uma página com curiosidades e informações sobre a cultura Rasta e a lenda do reggae, Bob Marley.

🛠️ Tecnologias Utilizadas
Back-end: PHP

Front-end: HTML5, CSS3, JavaScript

Banco de Dados: MySQL

Servidor: Apache (geralmente utilizado com XAMPP, WAMP ou MAMP)

🚀 Instalação e Execução
Siga os passos abaixo para configurar e executar o projeto em seu ambiente de desenvolvimento local.

Pré-requisitos
Um ambiente de servidor local como XAMPP ou similar (que inclua Apache, MySQL e PHP).

Um cliente de banco de dados, como o phpMyAdmin (incluído no XAMPP) ou DBeaver.

Git instalado em sua máquina.

Passo a Passo
Clonar o Repositório
Abra o terminal na pasta onde deseja salvar o projeto (por exemplo, C:/xampp/htdocs no Windows) e execute o seguinte comando:

Bash

git clone [URL_DO_SEU_REPOSITORIO_AQUI]
Configurar o Banco de Dados

Inicie os módulos Apache e MySQL no seu painel XAMPP.

Acesse o phpMyAdmin em http://localhost/phpmyadmin.

Crie um novo banco de dados com o nome to_do, conforme especificado no arquivo database/Database.php.

Execute o script SQL abaixo para criar as tabelas users e tasks:

SQL

CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL
);

CREATE TABLE `tasks` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `description` TEXT NOT NULL,
  `difficulty` ENUM('fácil', 'normal', 'dificil') NOT NULL,
  `completed` BOOLEAN DEFAULT 0,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
Configurar as Credenciais do Banco

Abra o arquivo database/Database.php.

Ajuste as seguintes variáveis com as suas credenciais do MySQL (a senha pode variar dependendo da sua configuração):

PHP

private $hostname = 'localhost';
private $database = 'to_do';
private $username = 'root';
private $password = ''; // Coloque sua senha do MySQL aqui, se houver
private $port = 3306;
Executar a Aplicação

Com o Apache rodando, abra seu navegador e acesse o link para a pasta do projeto.

Exemplo: http://localhost/listaRastaEnd/public/

Você será direcionado para a página de login. Crie uma conta e comece a usar!

👥 Integrantes
Lucas de Fiori Viudes 
Lucas Gozer Lopes
Vitto Lorenzo Barboza Legnani

🔗 Diagrama Entidade-Relacionamento (DER)

https://github.com/yFifio/listaRastaEnd/tree/main/DER
