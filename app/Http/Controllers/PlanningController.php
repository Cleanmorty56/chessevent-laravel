<?php

namespace App\Http\Controllers;

use App\Models\Planning;
use Illuminate\Http\Request;

class PlanningController extends Controller
{
    public function store(Request $request)
    {
    
        if (!auth()->check()) {
        return redirect()->route('login')->with('error', 'Для отправки заявки необходимо авторизоваться.');
        }

        // Валидация данных
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:255'],
            'organizer' => ['nullable', 'string', 'max:85'],
            'gamemode_id' => ['required', 'exists:gamemodes,id'],
            'quantity_rounds' => ['required', 'integer', 'min:1'],
            'imageFile' => ['nullable', 'image', 'max:2048'], // 2MB
        ]);

        // Обработка изображения
        $imagePath = null;
        if ($request->hasFile('imageFile')) {
            $imagePath = $request->file('imageFile')->store('planning_images', 'public');
        }

        // Сохранение заявки
        $planning = Planning::create([
            'content' => $validated['content'],
            'organizer' => $validated['organizer'] ?? null,
            'user_id' => auth()->id() ?? null,
            'gamemode_id' => $validated['gamemode_id'],
            'imageFile' => $imagePath,
            'quantity_rounds' => $validated['quantity_rounds'],
            'status' => 0, // 0 - на рассмотрении
        ]);

        return redirect()->back()->with('success', 'Заявка отправлена администратору!');
    }
}