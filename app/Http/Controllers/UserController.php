<?php

namespace App\Http\Controllers;

use Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function user(Request $request)
    {
        return response([
            'header' => $request->header(),
            'user' => auth()->user(),
        ]);
    }

    public function index(Request $request)
    {
        $id = auth()->user()->id;
        $users = User::where('id', '!=', $id)->orderBy('id')->orderBy('id', 'DESC')->paginate(10);
        return response([
            'data' => $users,
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
        //
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
        $user = User::find($request->id);
        // $data = array(
            //     'name' => $request->input('name'),
            //     'lastname' => $request->input('lastname'),
            //     'email' => $request->input('email'),
            //     'telephone' => $request->input('telephone'),
            //     'username' => $request->input('username'),
            //     'password' => $request->input('password'),
            //     'avatar' => $request->input('avatar'),
            // );
        $image = $request->file('avatar');
        if(!empty($image)){
            //ตั้งชื่อรูป
            $file_name = "user_" . time() . "." . $image->getClientOriginalExtension();
            //กำหนดขนาดความกว้างและสูงของภาพ
            $imgWidth     = 250;
            $imgHeigh     = 300;
            $folderupload = "images/avatar/thumbnail";
            $path         = $folderupload . "/" . $file_name;

            //อัพโหลดเข้าสู่ folder thumbnail
            $img = Image::make($image->getRealPath());
            $img->orientate()->fit($imgWidth, $imgHeigh, function ($constraint)
            {
                $constraint->upsize();
            });
            $img->save($path, 100);

            //อัพโหลดภาพต้นฉบับเข้า folder original
            $destinationPath = "images/avatar/original";
            $image->move($destinationPath, $file_name);

            //กำหนด path รูปเพื่อใส่ในตารางในฐานข้อมูล
            // $data['avatar'] = url('/') . '/images/avatar/thumbnail/' . $file_name;    
            $user->avatar = url('/') . '/images/avatar/thumbnail/' . $file_name;    
        }else{
            // $data['avatar'] = $this->faker->imageUrl(400, 400);
        }

        if($request->input('password') != ""){
            // $data['password'] = Hash::make($request->password);
            $user->password = Hash::make($request->password);
        }else{
            
        }
        
        $user->name = $request->input('name');
        $user->lastname = $request->input('lastname');
        $user->email = $request->input('email');
        $user->telephone = $request->input('telephone');
        $user->username = $request->input('username');
        $user->save();

        return response([
            'message' => $user,
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

    public function search(Request $request)
    {
        $users = User::where('name', 'LIKE', '%'.$request->q.'%')
            ->orWhere('lastname', 'LIKE', '%'.$request->q.'%')
            ->paginate(10);
        return response([
            'data' => $users,
        ]);
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
            $user = User::find($request->user_id);
            $user->delete();
        }else{
            throw ValidationException::withMessages([
                'confirming_password' => 'รหัสผ่านไม่ถูกต้อง'
            ]);
        }
        return response([
            'message' => 'deleted',
            'response' => $request->all(),
            'hashedPassword' => $hasshedPassword,
        ]);
    }
}
