<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index()
    {
        return view(view: 'home.index');
    }

    //
    public function test($id,$name)
    {
        return view('home.test',['id'=>$id],['name'=>$name]);
        /*
        echo "ID NUMBER  :", $id;
        echo "<br>";
        echo "NAME  :", $name;
        for($i = 1;$i<$id;$i++)
        {
            echo"<br>$i - $name";
        }
        */
    }


}
