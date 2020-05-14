<?php

namespace App\Http\Controllers;

use App\UserRequest;
use Illuminate\Http\Request;

class UserRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->role_id == Role::$HIGHER_MANAGEMENT) {
            $userRequests = DB::table('user_request as ur')
            ->join('users as u', 'ur.user_id', '=', 'u.id')
            ->join('user_request_type as urt', 'urt.id', '=', 'ur.type_id')
            ->join('user_request_status as urs', 'urs.id', '=', 'ur.status_id')
            ->select('ur.*', 'u.name as user', 'urt.name as type', 'urs.name as status')
            ->get();
        }        

        if (Auth::user()->role_id == Role::$MID_MANAGEMENT) {
            $userRequests = DB::table('user_request as ur')
            ->join('users as u', 'ur.user_id', '=', 'u.id')
            ->join('user_request_type as urt', 'urt.id', '=', 'ur.type_id')
            ->join('user_request_status as urs', 'urs.id', '=', 'ur.status_id')
            ->select('ur.*', 'u.name as user', 'urt.name as type', 'u.role_id', 'urs.name as status')
            ->where("u.role_id", '!=', Role::$HIGHER_MANAGEMENT)
            ->get();
        }

        if (Auth::user()->role_id == Role::$EMPLOYEE) {
            $userRequests = DB::table('user_request as ur')
            ->join('users as u', 'ur.user_id', '=', 'u.id')
            ->join('user_request_type as urt', 'urt.id', '=', 'ur.type_id')
            ->join('user_request_status as urs', 'urs.id', '=', 'ur.status_id')
            ->select('ur.*', 'u.name as user', 'urt.name as type', 'u.role_id', 'urs.name as status')
            ->where('ur.user_id', '=', Auth::user()->id)
            ->get();
        }

        return view('user_requests.index', [
            'user_requests' => $userRequests
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $requestTypes = DB::table('user_request_type')->get();
        return view('user_requests.create', [
            'request_types' => $requestTypes
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
        $pendingRequest = DB::table('user_request_status')->where('id', 1)->first();
    
        UserRequest::create([
            'user_id' => Auth::user()->id,
            'type_id' => $request->input('type_id'),
            'details' => $request->input('details'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'status_id' => $pendingRequest->id
        ]);

        return redirect('/user_requests');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserRequest  $userRequest
     * @return \Illuminate\Http\Response
     */
    public function show(UserRequest $userRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserRequest  $userRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(UserRequest $userRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserRequest  $userRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserRequest $userRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserRequest  $userRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserRequest $userRequest)
    {
        //
    }

    public function approve($id)
    {
        DB::table('user_request')->where('id', $id)
        ->update([          
            "status_id" => UserRequestStatus::$APPROVED
        ]);
        return redirect('/user_requests');
    }

    public function deny($id)
    {
        DB::table('user_request')->where('id', $id)
        ->update([          
            "status_id" => UserRequestStatus::$DENIED
        ]);
        return redirect('/user_requests');
    }
}
