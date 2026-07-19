<?php

namespace App\Services;

class EloCalculator
{
    const K_FACTOR = 32;

    public static function calculateNewRatings($whiteElo, $blackElo, $result)
    {
        $whiteExpected = 1 / (1 + pow(10, ($blackElo - $whiteElo) / 400));
        $blackExpected = 1 / (1 + pow(10, ($whiteElo - $blackElo) / 400));

        switch ($result) {
            case 'white_win':
                $whiteScore = 1;
                $blackScore = 0;
                break;
            case 'black_win':
                $whiteScore = 0;
                $blackScore = 1;
                break;
            case 'draw':
                $whiteScore = 0.5;
                $blackScore = 0.5;
                break;
            default:
                $whiteScore = 0.5;
                $blackScore = 0.5;
        }

        $whiteChange = round(self::K_FACTOR * ($whiteScore - $whiteExpected));
        $blackChange = round(self::K_FACTOR * ($blackScore - $blackExpected));

        return [
            'white_new_elo' => round($whiteElo + $whiteChange),
            'black_new_elo' => round($blackElo + $blackChange),
            'white_change' => $whiteChange,
            'black_change' => $blackChange,
        ];
    }
}