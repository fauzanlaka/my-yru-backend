<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::latest()->paginate(5);
        return response([
            'data' => $students
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
            'code'        => 'numeric|digits:9|unique:students',
            'name'        => 'required',
            'lastname'    => 'required',
            'room_number' => 'required',
        ];
        $request->validate($rules);
        $student = new Student();
        $student->code = $request->code;
        $student->name = $request->name;
        $student->lastname = $request->lastname;
        $student->room_number = $request->room_number;
        $student->save();

        return response([
            'request' => $request->all(),
            'student' => $student
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
            'code'        => 'numeric|digits:9|unique:students,code,'.$id,
            'name'        => 'required',
            'lastname'    => 'required',
            'room_number' => 'required',
        ];
        $request->validate($rules);
        $student = Student::find($id);
        $student->code = $request->code;
        $student->name = $request->name;
        $student->lastname = $request->lastname;
        $student->room_number = $request->room_number;
        $student->save();
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
            $user = Student::find($request->student_id);
            $user->delete();
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
