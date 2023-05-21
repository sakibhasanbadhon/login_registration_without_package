<?php

namespace App\Http\Controllers\Auth;


use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;


class AuthController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        if (Session::has('user_id') && Session::get('user_id') != '') {
            return redirect('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registration()
    {

        if (Session::has('user_id') && Session::get('user_id') != '') {
            return redirect('dashboard');
        }
        return view('auth.registration');

    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postLogin(Request $request)
    {
        $all_user = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        // $credentials = $request->only('email', 'password');
        // if (Auth::attempt($credentials)) {
        //     return redirect()->intended('dashboard')
        //                 ->withSuccess('You have Successfully loggedin');
        // }

        // $email = $request->input('email');
        // $pass = $request->input('password');

        $user= User::where('email','=',$all_user['email'])->first();

        if (Hash::check($all_user['password'],$user->password)) {
            Session::put('user_id', $user->id);



            return redirect('/dashboard');
        } else {
            return redirect('/login');
        }



        // return redirect("login")->withSuccess('Oppes! You have entered invalid credentials');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $data = $request->all();
        $check = $this->create($data);

        return redirect("dashboard")->withSuccess('Great! You have Successfully loggedin');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function dashboard()
    {
        // if(Auth::check()){
        //     return view('dashboard');
        // }

        if (Session::has('user_id') && Session::get('user_id') != '') {
            return view('dashboard');
        }




        return redirect("login");
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function logout() {
        Session::flush();

        return Redirect('login');
    }
}
