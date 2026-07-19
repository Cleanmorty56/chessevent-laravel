<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function quick()
    {
        $user = Auth::user();
        
        // Проверяем, что пользователь авторизован
        if (!$user) {
            return redirect()->route('login')->with('error', 'Для игры необходимо авторизоваться');
        }
        
        return view('game.quick', compact('user'));
    }
}