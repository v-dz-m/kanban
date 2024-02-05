<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::select(['id', 'title', 'author_user_id', 'created_at'])->where('status', 6)->get();

        return $tasks;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $title = $request->get('title');
        $createdStatus = 1;
        $maxOrder = Task::where('status', $createdStatus)->max('order');

        $task = Task::create([
            'board_id' => 1,
            'title' => $title,
            'description' => '',
            'status' => $createdStatus,
            'order' => $maxOrder + 1,
            'author_user_id' => auth()->id(),
            'executor_user_id' => auth()->id()
        ]);

        return $task->id;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $task = Task::with('author', 'executor')->find($id);

        $info = [
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status,
            'author' => $task->author->name,
            'time' => $task->created_at->translatedFormat('l, d.m.Y, H:m'),
        ];

        return compact('info');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if ($request->has('status')) {
            $oldStatus = $task->status;
            $newStatus = $request->get('status');
            $order = $task->order;

            $oldStatusTasks = Task::where('status', $oldStatus);
            $oldMaxOrder = $oldStatusTasks->max('order');

            $oldStatusTasks->where('order', '>', $order)->decrement('order');

            if ($oldStatus == $newStatus) {
                $task->update([
                    'order' => $oldMaxOrder
                ]);
            } else {
                $task->update([
                    'status' => $newStatus,
                    'order' => Task::where('status', $newStatus)->max('order') + 1
                ]);
            }

            return "Изменена успешно";
        }

        return "Удаление ещё не реализовано";
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return "Удалена успешно";
    }
}
