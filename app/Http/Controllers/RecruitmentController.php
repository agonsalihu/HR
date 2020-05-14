<?php

namespace App\Http\Controllers;

use App\Recruitment;
use Illuminate\Http\Request;

class RecruitmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $recruitments = DB::table('recruitment as r')
        ->join('recruitment_status as rs', 'rs.id', '=', 'r.status_id')
        ->join('applicant as a', 'a.id', '=', 'r.applicant_id')
        ->join('positions as p', 'p.id', '=', 'a.position_id')
        ->select('r.*', 'rs.name as status', 'a.first_name', 'a.last_name', 'a.personal_email as email','p.name as position')
        ->orderBy('updated_at', 'desc')
        ->get();
        
        return view('recruitment.index', [
            'recruitments' => $recruitments
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $statuses = DB::table('recruitment_status')->get();
        $positions = DB::table('positions')->get();
        return view('recruitment.create', [
            'statuses' => $statuses,
            'positions' => $positions
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
        $applicant = new Applicant();
        $applicant->first_name = $request->input('first_name');
        $applicant->last_name = $request->input('last_name');
        $applicant->position_id = $request->input('position_id');
        $applicant->personal_email = $request->input('email');
        $applicant->save();

        $recruitment = new Recruitment();
        $recruitment->status_id = $request->input('status_id');
        $recruitment->applicant_id = $applicant->id;
        $recruitment->notes = $request->input('notes');
        $recruitment->save();
        
        return redirect('/recruitments');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Recruitment  $recruitment
     * @return \Illuminate\Http\Response
     */
    public function show(Recruitment $recruitment)
    {
        $recruitment = DB::table('recruitment as r')
            ->join('applicant as a', 'r.applicant_id', '=', 'a.id')   
            ->join('positions as p', 'p.id', '=', 'a.position_id')     
            ->select('r.*', 'a.first_name', 'a.last_name', 'p.id as position_id')
            ->where('r.id', $id)
            ->first();
        
        $positions = DB::table('positions')->get();
        $statuses = DB::table('recruitment_status')->get();

        return view('recruitment.edit', [
            'recruitment' => $recruitment,
            'positions' => $positions,
            'statuses' => $statuses
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Recruitment  $recruitment
     * @return \Illuminate\Http\Response
     */
    public function edit(Recruitment $recruitment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Recruitment  $recruitment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recruitment $recruitment)
    {
        if (RecruitmentStatus::isStatusFinished($request->input('status_id'))) {
            $position = DB::table('positions')->find($request->input('position_id'));            
            User::updateOrCreate(
                ['name' => $request->input('name'), 'email' => $request->input('name'). '@company.com',],
                [
                    'name' => $request->input('name'),
                    'email' => $request->input('name'). '@company.com',
                    'password' => Hash::make("test123"),
                    'role_id' => 3,
                    'department_id' => $position->department_id,
            ]);
            // and then we send the email and password to his inbox email for reset
        }

        DB::table('recruitment')->where('id', $id)
        ->update([
            "status_id" => $request->input('status_id'),
            "notes" => $request->input('notes'),            
        ]);

        return redirect('/recruitments');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Recruitment  $recruitment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recruitment $recruitment)
    {
        $recruitment = DB::table('recruitment')->where('id', $id);

        if (Auth::user()->role_id == Role::$HIGHER_MANAGEMENT or Auth::user()->role_id == Role::$MID_MANAGEMENT) {        
            $recruitment->delete();
            return back();
        }
        if (Auth::user()->role_id == Role::$EMPLOYEE){
            Session::flash('message', 'You cannot delete, you are an employee!'); 
            Session::flash('alert-class', 'alert-danger');
        }
        return back();
    }
}
