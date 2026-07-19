<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Planning;
use App\Models\Gamemode;
use Illuminate\Http\Request;

class PlanningController extends Controller
{
    private function checkAdmin()
    {
        if (auth()->user()->role != 1) {
            abort(403, 'Доступ запрещён');
        }
    }

    public function index()
    {
        $plannings = Planning::with(['user', 'gamemode'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.planning.index', compact('plannings'));
    }

    public function show(Planning $planning)
    {
        $planning->load(['user', 'gamemode']);
        return view('admin.planning.show', compact('planning'));
    }

    public function approve(Planning $planning)
    {
        $planning->update(['status' => 'approved']);
        
        return redirect()->route('admin.planning.index')
            ->with('success', 'Заявка одобрена!');
    }

    public function reject(Planning $planning)
    {
        $planning->update(['status' => 'rejected']);
        
        return redirect()->route('admin.planning.index')
            ->with('success', 'Заявка отклонена!');
    }

    public function destroy(Planning $planning)
    {
        $planning->delete();
        
        return redirect()->route('admin.planning.index')
            ->with('success', 'Заявка удалена!');
    }
}