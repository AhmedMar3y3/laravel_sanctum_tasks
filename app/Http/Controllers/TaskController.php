<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TasksResource;
use App\Models\Task;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    use HttpResponses;
   
    public function index()
    {
        return TasksResource::collection
        (
            Task::where('user_id',Auth::user()->id)->get()
        );
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $request->validated($request->all());

        $task = Task::create(
            [
                'user_id' => Auth::user()->id,
                'name' => $request->name,
                'description' =>$request->description,
                'priority' =>$request->priority
            ]);
            return new TasksResource($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        if(Auth::user()->id !== $task->user_id ){
            return $this->error('','You are not allowed to make this request', 405);
        }
        return new TasksResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        if(Auth::user()->id !== $task->user_id ){
            return $this->error('','You are not allowed to make this request', 405);
        } 
        $task->update($request->all());
        return new TasksResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if(Auth::user()->id !== $task->user_id ){
            return $this->error('','You are not allowed to make this request', 405);
        }  
        $task->delete();
        return response('you have successfully deleted this task');   
    }
    private function isNotAuthorized($task){
        if(Auth::user()->id !== $task->user_id ){
            return $this->error('','You are not allowed to make this request', 405);
        }  
    }
}
