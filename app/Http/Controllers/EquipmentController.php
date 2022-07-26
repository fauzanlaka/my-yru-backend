<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $e = Equipment::latest()->paginate(5);
        return response([
            'data' => $e,
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
        $rules = [
            'code'        => 'required|unique:equipment',
            'equipment'   => 'required',
            'remaining'    => 'required|numeric',
        ];
        $request->validate($rules);
        $student = new Equipment();
        $student->code = $request->code;
        $student->equipment = $request->equipment;
        $student->remaining = $request->remaining;
        $student->save();

        return response([
            'student' => 'created',
        ]);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'code'        => 'numeric|unique:equipment,code,'.$id,
            'equipment'        => 'required',
            'remaining'    => 'required',
        ];
        $request->validate($rules);
        $equipment = Equipment::find($id);
        $equipment->code = $request->code;
        $equipment->equipment = $request->equipment;
        $equipment->remaining = $request->remaining;
        $equipment->save();
        return response([
            'status' => 'updated',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function delete(Request $request)
    {
        $rules = [
            'confirming_password' => 'required'
        ];
        $messages = [
            'confirming_password.required' => 'กรุณากรอกรหัสผ่าน'
        ];
        $request->validate($rules, $messages);
        $hasshedPassword = auth()->user()->getAuthPassword();
        if(Hash::check($request->confirming_password, $hasshedPassword)){
            $e = Equipment::find($request->equipment_id);
            $e->delete();
        }else{
            throw ValidationException::withMessages([
                'confirming_password' => 'รหัสผ่านไม่ถูกต้อง'
            ]);
        }
        return response([
            'message' => 'deleted',
        ]);
    }
}
