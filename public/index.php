<?php

// FICHEIRO ATUALIZADO: public/index.php
// Agora incluímos o enum e convertemos a string do formulário para o tipo Difficulty
// antes de chamar os métodos do TaskManager.

session_start();

require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../src/Auth.php';
require_once __DIR__ . '/../src/Task.php';
require_once __DIR__ . '/../src/Difficulty.php'; // ALTERAÇÃO: Incluir o novo ficheiro do enum

try {
    $dbConnection = Database::getInstance()->getConnection();
} catch (Exception $e) {
    error_log("Erro de conexão no index.php: " . $e->getMessage());
    die("Ocorreu um problema ao conectar com o servidor. Tente novamente mais tarde.");
}

$auth = new Auth($dbConnection);
$auth->checkLogin();

$taskManager = new Task($dbConnection);
$action = $_POST['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ALTERAÇÃO: Centralizamos a conversão da string do formulário para o enum
    $difficultyEnum = null;
    if (isset($_POST['difficulty'])) {
        // Difficulty::tryFrom() tenta encontrar um caso do enum com o valor correspondente.
        // Se não encontrar, retorna null.
        $difficultyEnum = Difficulty::tryFrom($_POST['difficulty']);
    }

    if ($action === 'create' && $difficultyEnum) {
        $description = trim($_POST['description'] ?? '');
        if (!empty($description)) {
            $taskManager->createTask($description, $difficultyEnum);
        }
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? 0;
        if ($id > 0) {
            $taskManager->deleteTask($id);
        }
    } elseif ($action === 'toggle') {
        $id = $_POST['id'] ?? 0;
        $completed = isset($_POST['completed']) && $_POST['completed'] == '1' ? 1 : 0;
        if ($id > 0) {
            $taskManager->updateTaskProgress($id, $completed);
        }
    } elseif ($action === 'update' && $difficultyEnum) {
        $id = $_POST['id'] ?? 0;
        $description = trim($_POST['description'] ?? '');
        if ($id > 0 && !empty($description)) {
            $taskManager->updateTask($id, $description, $difficultyEnum);
        }
    }
    header('Location: index.php');
    exit;
}

$tasks = $taskManager->getAllTasks();

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JahBless</title>
    <link rel="icon" type="image/png" href="assets/peace-icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" xintegrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles/style.css">
    <style>
        .hidden { display: none; }
    </style>
</head>
<body>

    <div id="to_do">
        <h1>Lista Rasta</h1>
        <p class="user-greeting">
            Missões de <?php echo htmlspecialchars($_SESSION['username'] ?? 'Guerreiro'); ?>
        </p>

        <form class="to-do-form" method="POST" action="index.php">
            <input type="hidden" name="action" value="create">
            <input type="text" name="description" placeholder="Qual a missão de hoje?" required>
            <select name="difficulty">
                <!-- ALTERAÇÃO: Geramos as opções dinamicamente a partir do enum -->
                <?php foreach (Difficulty::cases() as $difficulty): ?>
                    <option value="<?php echo $difficulty->value; ?>" <?php echo $difficulty === Difficulty::NORMAL ? 'selected' : ''; ?>>
                        <?php echo ucfirst($difficulty->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="form-button" title="Adicionar Tarefa">
                <i class="fa-solid fa-plus"></i>
            </button>
        </form>

        <div id="tasks">
            <?php $i = 0; ?>
            <?php foreach ($tasks as $task): ?>
                <div class="task" data-task-id="<?php echo $task['id']; ?>" style="--i: <?php echo $i++; ?>;">
                    <div class="task-view">
                        <form method="POST" action="index.php">
                            <input type="hidden" name="action" value="toggle">
                            <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                            <input type="hidden" name="completed" value="<?php echo $task['completed'] ? '0' : '1'; ?>">
                            <input
                                type="checkbox"
                                class="progress"
                                onchange="this.form.submit()"
                                <?php echo $task['completed'] ? 'checked' : ''; ?>
                                title="<?php echo $task['completed'] ? 'Desmarcar' : 'Concluir'; ?>"
                            >
                        </form>
                        <span class="task-description <?php echo $task['completed'] ? 'done' : ''; ?>"><?php echo htmlspecialchars($task['description']); ?></span>
                        <span class="task-difficulty <?php echo Difficulty::from($task['difficulty'])->getCssClass(); ?>"><?php echo htmlspecialchars(ucfirst($task['difficulty'])); ?></span>
                        <div class="task-actions">
                            <button class="action-button edit-btn" title="Editar"><i class="fa-solid fa-pencil"></i></button>
                            <form method="POST" action="index.php" onsubmit="return confirm('Tem certeza que quer apagar esta missão?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                                <button type="submit" class="action-button delete-button" title="Excluir"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </div>
                    </div>

                    <form class="edit-task hidden" method="POST" action="index.php">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                        <input type="text" name="description" value="<?php echo htmlspecialchars($task['description']); ?>" required>
                        <select name="difficulty">
                           <!-- ALTERAÇÃO: Geramos as opções dinamicamente também no formulário de edição -->
                           <?php foreach (Difficulty::cases() as $difficulty): ?>
                                <option value="<?php echo $difficulty->value; ?>" <?php echo $task['difficulty'] == $difficulty->value ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($difficulty->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="action-button confirm-button" title="Salvar"><i class="fa-solid fa-check"></i></button>
                        <button type="button" class="action-button cancel-button" title="Cancelar"><i class="fa-solid fa-xmark"></i></button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

    <a href="logout.php" class="logout-button" title="Sair">
        <i class="fa-solid fa-right-from-bracket"></i> Sair
    </a>

    <a href="momento_jah.php" class="jah-moment-button" title="Momento com Jah">
        <i class="fa-solid fa-peace"></i> Momento com Jah
    </a>

    <!-- Coluna de Curiosidades Rasta -->
    <div id="rasta-facts-sidebar">
        <div id="facts-handle" title="Curiosidades Rasta">
            <i class="fa-solid fa-leaf"></i>
        </div>
        <div id="facts-content">
            <h2>Curiosidades Rasta</h2>
            <ul>
                <li><strong>Ital:</strong> A dieta "Ital" é uma parte importante da cultura, focada em alimentos naturais e puros, geralmente vegetarianos, para aumentar a energia vital (Livity).</li>
                <li><strong>Cores:</strong> O verde, amarelo e vermelho simbolizam a lealdade à bandeira da Etiópia. Verde para a natureza da África, amarelo para a riqueza e vermelho para o sangue dos mártires.</li>
                <li><strong>Dreadlocks:</strong> Os dreadlocks são um símbolo da aliança com Jah (Deus) e representam a juba do Leão de Judá, um dos títulos do Imperador Haile Selassie I.</li>
                <li><strong>Música Reggae:</strong> O Reggae não é apenas música, mas um veículo para mensagens de consciência social, espiritual e política. Bob Marley é seu maior embaixador mundial.</li>
                <li><strong>Linguagem:</strong> Rastas usam um dialeto chamado "Iyaric", que modifica palavras em inglês para remover conotações negativas. Por exemplo, "understand" (entender) se torna "overstand".</li>
                <li><strong>Leão de Judá:</strong> É um símbolo central que representa Haile Selassie I, considerado a reencarnação de Cristo pelos Rastafáris, e também simboliza força e realeza africana.</li>
            </ul>
            <div class="sidebar-footer">
                <a href="bob_marley.php" class="bio-link">
                    <i class="fa-solid fa-star"></i> Conheça a Lenda
                </a>
            </div>
        </div>
    </div>

    <script src="js/main.js"></script>

</body>
</html>
