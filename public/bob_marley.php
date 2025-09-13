<?php
session_start();
require_once('../src/Auth.php');
require_once('../database/Database.php');

$db = Database::getInstance()->getConnection();
$auth = new Auth($db);
$auth->checkLogin();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A Lenda: Bob Marley</title>
    <link rel="icon" type="image/png" href="assets/peace-icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/bob_marley.css">
</head>
<body>
    <div class="bio-container">
        <a href="index.php" class="back-button" title="Voltar para as Missões">
            <i class="fa-solid fa-arrow-left"></i> Voltar
        </a>
        <header class="bio-header">
            <img src="assets/bob.jpeg" alt="Foto de Bob Marley" class="bio-image">
            <h1>Bob Marley</h1>
            <p class="subtitle">O Rei do Reggae e a Voz de Jah</p>
        </header>

        <section class="bio-content">
            <h2>O Mensageiro do Reggae</h2>
            <p>
                Robert Nesta Marley, nascido em 6 de fevereiro de 1945, na Jamaica, transcendeu a música para se tornar um ícone global de paz, amor e resistência. Com sua banda, The Wailers, ele popularizou o reggae, um ritmo que mistura ska, rocksteady e influências africanas, levando a mensagem e a vibração da Jamaica para o mundo inteiro.
            </p>

            <h2>Voz da Cultura Rasta</h2>
            <p>
                Bob Marley foi o mais famoso embaixador da fé Rastafári. Suas letras eram sermões musicados, falando sobre espiritualidade, a divindade de Haile Selassie I (Jah), o êxodo para a África (Zion) e a crítica ao sistema opressor ("Babilônia"). Seus dreadlocks, sua dieta Ital e sua linguagem eram manifestações de sua fé profunda, inspirando milhões a buscar uma conexão mais profunda com o divino e com suas raízes.
            </p>

            <h2>Legado Imortal</h2>
            <p>
                A influência de Marley vai muito além de seus discos. Ele se tornou um símbolo de luta pela liberdade e pelos direitos humanos. Sua música uniu pessoas de diferentes culturas e classes sociais, e sua mensagem de "One Love" continua a ecoar como um hino universal pela união e fraternidade. Mesmo após sua morte prematura em 1981, seu espírito vive, e sua música continua a ser a trilha sonora da esperança para novas gerações.
            </p>
        </section>
    </div>
</body>
</html>