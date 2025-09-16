<?php

enum Difficulty: string
{
    case FACIL = 'fácil';
    case NORMAL = 'normal';
    case DIFICIL = 'dificil';

    public function getCssClass(): string
    {
        return match ($this) {
            self::FACIL => 'difficulty-fácil',
            self::NORMAL => 'difficulty-normal',
            self::DIFICIL => 'difficulty-dificil',
        };
    }
}
