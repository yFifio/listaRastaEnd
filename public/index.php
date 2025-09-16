<?php
session_start();

require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../src/Auth.php';
require_once __DIR__ . '/../src/Task.php';

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
    $difficultyId = filter_input(INPUT_POST, 'difficulty_id', FILTER_VALIDATE_INT);

    if ($action === 'create' && $difficultyId) {
        $description = trim($_POST['description'] ?? '');
        if (!empty($description)) {
            $taskManager->createTask($description, $difficultyId);
        }
    } elseif ($action === 'delete') {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id) {
            $taskManager->deleteTask($id);
        }
    } elseif ($action === 'toggle') {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $completed = isset($_POST['completed']) && $_POST['completed'] == '1' ? 1 : 0;
        if ($id) {
            $taskManager->updateTaskProgress($id, $completed);
        }
    } elseif ($action === 'update' && $difficultyId) {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $description = trim($_POST['description'] ?? '');
        if ($id && !empty($description)) {
            $taskManager->updateTask($id, $description, $difficultyId);
        }
    }
    header('Location: index.php');
    exit;
}

$tasks = $taskManager->getAllTasks();
$difficulties = $taskManager->getAllDifficulties();

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JahBless</title>
    <link rel="icon" type="image/png" href="assets/peace-icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles/style.css">
    <style>
        .hidden { display: none; }
        .difficulty-fácil { background-color: #22c55e; color: #fff; }
        .difficulty-normal { background-color: #ffe600; color: #111; }
        .difficulty-difícil { background-color: #ff0000; color: #fff; }
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
            <select name="difficulty_id">
                <?php foreach ($difficulties as $difficulty): ?>
                    <option value="<?php echo $difficulty['id']; ?>" <?php echo $difficulty['level'] === 'normal' ? 'selected' : ''; ?>>
                        <?php echo ucfirst($difficulty['level']); ?>
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
                        <form method="POST" action="index.php" style="display:inline;">
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
                        <span class="task-difficulty difficulty-<?php echo htmlspecialchars($task['difficulty_level']); ?>">
                            <?php echo htmlspecialchars(ucfirst($task['difficulty_level'])); ?>
                        </span>
                        <div class="task-actions">
                            <button class="action-button edit-btn" title="Editar"><i class="fa-solid fa-pencil"></i></button>
                            <form method="POST" action="index.php" onsubmit="return confirm('Tem certeza que quer apagar esta missão?');" style="display:inline;">
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
                        <select name="difficulty_id">
                           <?php foreach ($difficulties as $difficulty): ?>
                                <option value="<?php echo $difficulty['id']; ?>" <?php echo $task['difficulty_id'] == $difficulty['id'] ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($difficulty['level']); ?>
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