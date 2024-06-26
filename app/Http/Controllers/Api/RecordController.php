<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Record;

class RecordController extends Controller
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
        $request->validate([
            'today' => 'required',
            'res_id' => 'required',
            'uid' => 'required',
            'name' => 'required',
            'surname' => 'required'
        ]);

        $data = new Record();
        $data->date = $request['today'];
        $data->res_id = $request['res_id'];
        $data->uid = $request['uid'];
        $data->name = $request['name'];
        $data->surname = $request['surname'];

        $data->save();

        $res = Record::where('date', $request['today'])->where('res_id', $request['res_id'])->get();

        return response()->json($res);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Record::where('res_id', $id)->get();

        return response()->json($data);
    }

    public function chkRecord(Request $request)
    {
        // $data = Record::where('')
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $code, string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Record::find($id)->delete();
        return response()->json();
    }
}
