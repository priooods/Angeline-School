<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller as ControllersController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Master\Entities\MAksesTab;
use Modules\Master\Entities\MGenderTab;
use Modules\Master\Entities\MMenuTab;
use Modules\User\Entities\MUserTab;

class UserController extends Controller
{
    protected $user;
    protected $gender;
    protected $akses;
    protected $menu;
    protected $controller;

    public function __construct(
        MUserTab $user, 
        MGenderTab $gender, 
        MAksesTab $akses,
        MMenuTab $menu, 
        ControllersController $controller
    ) {
        $this->user = $user;
        $this->gender = $gender;
        $this->akses = $akses;
        $this->menu = $menu;
        $this->controller = $controller;
    }

    public function login(Request $request){
        if($valid = $this->controller->validating($request,[
            'password' => 'required',
            'email' => 'required',
        ])) {
            return $valid;
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->controller->responses('FAILURE ACCOUNT',401, null, [
                "type" => "error",
                "title" => "Unauthorized",
                "description" => 'Email atau Password anda ada yang salah'
            ]);
        }
        return $this->userLoginState($request);
    }

    public function userLoginState($request){
        $user = $this->user->where('email', $request['email'])
        ->where('is_activated',1)
        ->where('is_deleted',0)->first();
        if(isset($user)){
            $token = $user->createToken('angeline_universe_vlogger');
            return $this->controller->responses('SUCCESS LOGIN',200, ['token' => $token->plainTextToken], [
                "type" => "success",
                "title" => "Selamat Datang",
                "description" => 'Hallo ' . $user->fullname . ' selamat datang kembali'
            ]);
        } else {
            return $this->controller->responses('FAILURE LOGIN',500,[
                "type" => "error",
                "title" => "Not Activated",
                "description" => 'Akun anda belum aktif, lakukan verifikasi untuk mengaktifkan akun'
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return $this->controller->responses("MY PROFILE",200,auth()->user());
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function formRegister()
    {
        return $this->controller->responses('FORM REGISTER',200,[
            [
                "key" => "username",
                "label" => "Fullname",
                "value" => null,
                "className" => "",
                "type" => "string"
            ],
            [
                "key" => "email",
                "label" => "Email",
                "value" => null,
                "className" => "",
                "type" => "email"
            ],
            [
                "key" => "password",
                "label" => "Password",
                "value" => null,
                "className" => "",
                "type" => "password"
            ],
            [
                "key" => "repassword",
                "label" => "Typing Again Password",
                "value" => null,
                "className" => "",
                "type" => "password"
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function register(Request $request)
    {
        if($valid = $this->controller->validating($request,[
            'fullname' => 'required',
            'email' => 'required|unique:m_user_tabs',
            'password' => 'required|string|min:8',
        ])){
            return $valid;
        }

        try {
            DB::beginTransaction();
            $request['repassword'] = encrypt($request->password);
            $request['password'] = Hash::make($request->password);
            $user = $this->user->create($request->all());
            DB::commit();
            return $this->controller->responses('User baru berhasil dibuat', 200,
                ['token' => $user->createToken('angeline_universe_vlogger')->plainTextToken]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->controller->responses($th->getMessage());
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function formLogin()
    {
        return $this->controller->responses('FORM LOGIN',200,[
            [
                "key" => "email",
                "label" => "Email",
                "value" => null,
                "className" => "",
                "type" => "email"
            ],
            [
                "key" => "password",
                "label" => "Password",
                "value" => null,
                "className" => "",
                "type" => "password"
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $user = $this->user->where('id', $id)->with('detail')->first();
        return $this->controller->responses('FORM LOGIN',200,[
            [
                "key" => "username",
                "label" => "Fullname",
                "value" => $user->fullname,
                "className" => "",
                "type" => "string"
            ],
            [
                "key" => "email",
                "label" => "Email",
                "value" => $user->email,
                "className" => "",
                "type" => "email"
            ],
            [
                "key" => "password",
                "label" => "Password",
                "value" => decrypt($user->repassword),
                "className" => "",
                "type" => "password"
            ],
            [
                "key" => "age",
                "label" => "Usia",
                "value" => $user->detail?->age,
                "className" => "",
                "type" => "number"
            ],
            [
                "key" => "m_gender_tab_id",
                "label" => "Gender",
                "value" => $user->detail?->m_gender_tab_id,
                "className" => "",
                "type" => "select",
                "array" => $this->gender->all()
            ],
            [
                "key" => "city",
                "label" => "Kota Asal",
                "value" => $user->detail?->city,
                "className" => "",
                "type" => "string"
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            if(isset($request->password)){
                $request['repassword'] = encrypt($request->password);
                $request['password'] = Hash::make($request->password);
            }
            $this->user->where('id', auth()->user()->id)->update($request->all());
            DB::commit();
            return $this->controller->responses('UPDATED WORK', Auth::user(),[
                "type" => "success",
                "title" => "Update profile berhasil",
                "description" => 'Permintaan update profile berhasil di terima'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(500, $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->controller->responses("Logout Success");
    }
}
