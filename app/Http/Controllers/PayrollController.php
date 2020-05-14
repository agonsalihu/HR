<?php

namespace App\Http\Controllers;

use App\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->role_id == Role::$HIGHER_MANAGEMENT) {
            $payrolls = DB::table('payroll as p')
            ->join('users as u1', 'u1.id', '=', 'p.manager_id')
            ->join('users as u2', 'u2.id', '=', 'p.user_id')
            ->select('p.*', 'u1.name as manager', 'u2.name as user')
            ->get();        
        } else {
            $payrolls = DB::table('payroll as p')
            ->join('users as u1', 'u1.id', '=', 'p.manager_id')
            ->join('users as u2', 'u2.id', '=', 'p.user_id')
            ->select('p.*', 'u1.name as manager', 'u2.name as user')
            ->where('p.user_id', '=', Auth::user()->id)
            ->get();
        }

        return view('payrolls.index', [
            'payrolls' => $payrolls
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('payrolls.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bonusUsers = $request->input('data');
        $users = DB::table('users')->get();          
        $currUserId = Auth::user()->id;
        foreach($users as $user) {
            $bono = 0;
            if ($bonusUsers != null) {
                foreach($bonusUsers as $bonus) {
                    if ((int)$bonus["user"] == $user->id) {
                        $bono = $bonus['bonus'];
                    }
                }
            }            

            DB::table('payroll')->insert([
                'manager_id' => $currUserId,
                'user_id' => $user->id,
                'sum' => $user->salary,
                'bonus' => $bono
            ]);
        }
        
        return redirect('/payrolls');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payroll  $payroll
     * @return \Illuminate\Http\Response
     */
    public function show(Payroll $payroll)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payroll  $payroll
     * @return \Illuminate\Http\Response
     */
    public function edit(Payroll $payroll)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payroll  $payroll
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payroll $payroll)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payroll  $payroll
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payroll $payroll)
    {
        //
    }
}
