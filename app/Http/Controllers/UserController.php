<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->role_id == Role::$HIGHER_MANAGEMENT){
            $users = DB::table('users')
            ->join('department', 'users.department_id', '=', 'department.id')
            ->select('users.*', 'department.name as department')
            ->get();
        }

        if(Auth::user()->role_id == Role::$MID_MANAGEMENT){
            $users = DB::table('users')
            ->join('department', 'users.department_id', '=', 'department.id')
            ->select('users.*', 'department.name as department')
            ->where('users.role_id', '!=', Role::$HIGHER_MANAGEMENT)
            ->get();
        }

        if(Auth::user()->role_id == Role::$EMPLOYEE){
            $users = DB::table('users')
            ->join('department', 'users.department_id', '=', 'department.id')
            ->select('users.*', 'department.name as department')
            ->where('users.id', '=', Auth::user()->id)
            ->get();
        }

        return view('users.index', ['users' => $users]);
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $users = DB::table('users')
        ->join('department', 'users.department_id', '=', 'department.id')
        ->select('users.*', 'department.name as department')
        ->where('users.id', $id)
        ->first();

        $departments = DB::table('department')->get();

        if(Auth::user()->role_id != 3 or (Auth::user()->role_id == 3 and Auth::user()->id == $users->id)) {
            return view('users.edit', [
                'users' => $users,
                'departments' => $departments
            ]);
        }
        if(Auth::user()->role_id == 3 and Auth::user()->id != $users->id) {
            Session::flash('message', 'You cannot edit other users!'); 
            Session::flash('alert-class', 'alert-danger');
            return back();
        }
        else {
            return back;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        DB::table('users')->where('id', $id)
        ->update([
            "name" => $request->input('name'),
            "email" => $request->input('email'),
            "department_id" => $request->input('department'),
            // "user_id" => $request->input('user')
        ]);
        return redirect()->action('UserController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user = DB::table('users')->where('id', $id);

        if (Auth::user()->role_id == Role::$HIGHER_MANAGEMENT) {        
            $user->delete();
            return back();
        }
        if (Auth::user()->role_id == Role::$MID_MANAGEMENT and $user->role_id == Role::$EMPLOYEE){
            $user->delete();
            return back();
        }
        if (Auth::user()->role_id == Role::$EMPLOYEE){
            Session::flash('message', 'You cannot delete, you are an employee!'); 
            Session::flash('alert-class', 'alert-danger');
            return back();
        }
        return back();
    }
}
