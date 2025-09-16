<?php
session_start();

require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../src/Auth.php';
require_once __DIR__ . '/../src/Task.php';
// REMOVIDO: A linha require_once __DIR__ . '/../src/Difficulty.php';

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
    // ALTERAÇÃO: Agora pegamos o ID da dificuldade diretamente do formulário
    $difficultyId = filter_input(INPUT_POST, 'difficulty_id', FILTER_VALIDATE_INT);

    if ($action === 'create' && $difficultyId) {
        $description = trim($_POST['description'] ?? '');
        if (!empty($description)) {
            $taskManager->createTask($description, $difficultyId);
        }
    } elseif ($action === 'delete') {
        // ... (código sem alteração)
    } elseif ($action === 'toggle') {
        // ... (código sem alteração)
    } elseif ($action === 'update' && $difficultyId) {
        $id = $_POST['id'] ?? 0;
        $description = trim($_POST['description'] ?? '');
        if ($id > 0 && !empty($description)) {
            $taskManager->updateTask($id, $description, $difficultyId);
        }
    }
    header('Location: index.php');
    exit;
}

$tasks = $taskManager->getAllTasks();
// ALTERAÇÃO: Buscamos as dificuldades para usar nos formulários
$difficulties = $taskManager->getAllDifficulties();

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <style>
        /* Adicione estas classes ao seu style.css se quiser manter as cores */
        .difficulty-fácil { background-color: #22c55e; color: #fff; }
        .difficulty-normal { background-color: #ffe600; color: #111; }
        .difficulty-difícil { background-color: #ff0000; color: #fff; }
    </style>
</head>
<body>
    <div id="to_do">
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
            <?php foreach ($tasks as $task): ?>
                <div class="task" data-task-id="<?php echo $task['id']; ?>">
                    <div class="task-view">
                        <span class="task-description <?php echo $task['completed'] ? 'done' : ''; ?>"><?php echo htmlspecialchars($task['description']); ?></span>
                        <span class="task-difficulty difficulty-<?php echo htmlspecialchars($task['difficulty_level']); ?>">
                            <?php echo htmlspecialchars(ucfirst($task['difficulty_level'])); ?>
                        </span>
                        <div class="task-actions">
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
    </body>
</html>