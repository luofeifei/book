<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;


class MyController extends Controller
{
    public function getAbout(){

        $userdetail= new app\Userdetail();

        return	$userdetail->find(1);
    }
}
