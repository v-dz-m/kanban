<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Task;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index()
    {
        $board = Board::find(1);

        $boardName = $board->name;
        $tasks = Task::where('board_id', 1)->orderBy('order')->get()->groupBy('status');

        return view('dashboard.index', compact('boardName', 'tasks'));
    }
}
