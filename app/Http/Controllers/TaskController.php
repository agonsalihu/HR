<?php

namespace App\Http\Controllers;

use App\Task;
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
        if (Auth::user()->role_id == Role::$HIGHER_MANAGEMENT) {
            $tasks = DB::table('task')
                ->leftJoin('users', 'task.user_id', '=', 'users.id')
                ->join('department', 'task.department_id', '=', 'department.id')
                ->select('task.*', 'department.name as department', 'users.email as email')
                ->get();
        }        

        if (Auth::user()->role_id == Role::$MID_MANAGEMENT) {
            $tasks = DB::table('task')
                ->leftJoin('users', 'task.user_id', '=', 'users.id')
                ->join('department', 'task.department_id', '=', 'department.id')
                ->select('task.*', 'department.name as department', 'users.email as email')
                ->where('users.role_id', '!=', Role::$HIGHER_MANAGEMENT)
                ->get();
        }        

        if (Auth::user()->role_id == Role::$EMPLOYEE) {
            $tasks = DB::table('task')
                ->leftJoin('users', 'task.user_id', '=', 'users.id')
                ->join('department', 'task.department_id', '=', 'department.id')
                ->select('task.*', 'department.name as department', 'users.email as email')
                ->where('task.user_id', '=', Auth::user()->id)
                ->get();
        }        
        
        return view('tasks.index', ['tasks' => $tasks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = DB::table('department')->get();
        $users = DB::table('users')->get();

        return view('tasks.create', [            
            'departments' => $departments,
            'users' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Task::updateOrCreate([
            "name" => $request->input('name'),
            "description" => $request->input('description'),
            "department_id" => $request->input('department'),
            "user_id" => $request->input('user'),
            
        ]);
        return redirect('/tasks');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        $task = DB::table('task')
            ->leftJoin('users', 'task.user_id', '=', 'users.id')
            ->join('department', 'task.department_id', '=', 'department.id')
            ->select('task.*', 'department.name as department', 'users.email as email')
            ->where('task.id', $id)
            ->first();

        $departments = DB::table('department')->get();
        $users = DB::table('users')->get();

        if(Auth::user()->role_id != 3) {
            return view('tasks.edit', [
                'task' => $task,
                'departments' => $departments,
                'users' => $users
            ]);
        }
        if(Auth::user()->role_id == 3 and user()->id == $task->user_id){
            return view('tasks.edit', [
                'task' => $task,
                'departments' => $departments,
                'users' => $users
            ]);
        }
        else {
            Session::flash('message', 'You cannot delete, you are an employee!'); 
            Session::flash('alert-class', 'alert-warning');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        DB::table('task')->where('id', $id)
        ->update([
            "name" => $request->input('name'),
            "description" => $request->input('description'),
            "department_id" => $request->input('department'),
            "user_id" => $request->input('user')
        ]);

        return redirect()->action('TaskController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        if (Auth::user()->role_id == Role::$HIGHER_MANAGEMENT or Auth::user()->role_id == Role::$MID_MANAGEMENT) {
            $task = DB::table('task')->where('id', $id);
            $task->delete();
        }
        
        return back();
    }
}
