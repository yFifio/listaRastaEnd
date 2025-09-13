<?php
// FICHEIRO NOVO: src/Difficulty.php
// Este enum define os únicos valores possíveis para a dificuldade de uma tarefa.
// Usar um "backed enum" (com 'string') permite que cada caso tenha um valor
// correspondente que pode ser facilmente guardado na base de dados.

enum Difficulty: string
{
    case FACIL = 'fácil';
    case NORMAL = 'normal';
    case DIFICIL = 'dificil';

    // (Opcional) Podemos adicionar um método para retornar a classe CSS correspondente,
    // centralizando a lógica de apresentação aqui.
    public function getCssClass(): string
    {
        return match ($this) {
            self::FACIL => 'difficulty-fácil',
            self::NORMAL => 'difficulty-normal',
            self::DIFICIL => 'difficulty-dificil',
        };
    }
}
