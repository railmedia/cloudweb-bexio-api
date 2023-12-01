<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
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

        $task = new Task;
        $task->project_id  = $request->projectId;
        $task->description = $request->description;
        $task->time_spent  = $request->hours . ':' . sprintf( "%02d", $request->minutes );
        $task->minutes     = (int) $request->hours * 60 + (int) $request->minutes;
        $task->date        = $request->date;

        if( ! $task->save() ) {
            return 'There was an error saving the task. Please try again.';
        }
        
        return 'Saved task!';
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $task = Task::where('id', $id)->first();
        $task->project_id  = $request->projectId;
        $task->description = $request->description;
        $task->time_spent  = $request->hours . ':' . sprintf( "%02d", $request->minutes );
        $task->minutes     = (int) $request->hours * 60 + (int) $request->minutes;
        $task->date        = $request->date;

        if( ! $task->save() ) {
            return 'There was an error saving the task. Please try again.';
        }
        
        return 'Saved task!';
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Task::destroy( $id );
    }

    public function getSingleTask( Request $request ) {

        return Task::where('id', $request->task_id)->first();

    }
}
