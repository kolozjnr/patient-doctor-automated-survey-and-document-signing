<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AdminLoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }
    

    protected function guard()
    {
       $guard = Auth::guard('admin');

    // dump([
    //     'route' => request()->route()->getName(),
    //     'guard_name' => $guard->getName(),
    //     'controller' => static::class,
    // ]);
    // die;
        return Auth::guard('admin');
    }
    

    

}
