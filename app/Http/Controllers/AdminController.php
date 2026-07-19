<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    private function checkAdmin()
    {
        if (auth()->user()->role != 1) {
            abort(403, 'Доступ запрещён');
        }
    }

    public function dashboard()
    {
        $this->checkAdmin();
        return view('admin.dashboard');
    }
}