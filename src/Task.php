<?php

class Task {
    private $pdo;
    private $userId;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        if (!isset($_SESSION['user_id'])) {
            throw new LogicException("ID do utilizador não encontrado na sessão. A verificação de login foi realizada?");
        }
        $this->userId = $_SESSION['user_id'];
    }

    public function getAllDifficulties() {
        $stmt = $this->pdo->query("SELECT * FROM difficulty ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllTasks() {
        $sql = "SELECT
                    t.id,
                    t.user_id,
                    t.description,
                    t.completed,
                    t.difficulty_id,
                    d.level as difficulty_level
                FROM tasks t
                LEFT JOIN difficulty d ON t.difficulty_id = d.id
                WHERE t.user_id = :user_id
                ORDER BY t.id ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $this->userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createTask(string $description, int $difficultyId) {
        $stmt = $this->pdo->prepare("INSERT INTO tasks (user_id, description, difficulty_id) VALUES (:user_id, :description, :difficulty_id)");
        $stmt->bindParam(':user_id', $this->userId, PDO::PARAM_INT);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':difficulty_id', $difficultyId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updateTask(int $id, string $description, int $difficultyId) {
        $stmt = $this->pdo->prepare("UPDATE tasks SET description = :description, difficulty_id = :difficulty_id WHERE id = :id AND user_id = :user_id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $this->userId, PDO::PARAM_INT);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':difficulty_id', $difficultyId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updateTaskProgress($id, $completed) {
        $stmt = $this->pdo->prepare("UPDATE tasks SET completed = :completed WHERE id = :id AND user_id = :user_id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $this->userId, PDO::PARAM_INT);
        $stmt->bindParam(':completed', $completed, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteTask($id) {
        $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE id = :id AND user_id = :user_id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $this->userId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}