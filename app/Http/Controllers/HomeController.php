<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Image;
use App\Models\Message;

use App\Models\Reserve;
use App\Models\Review;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{

    public static function getsetting()
    {
        return Setting::first();
    }
    public function index(){
        $setting=Setting::first();
        $slider=Car::select('id','title','image','slug','price','category_id')->limit(4)->get();
        $daily=Car::select('id','title','image','slug','price','category_id')->limit(4)->inRandomOrder()->get();
        $last=Car::select('id','title','image','slug','price','category_id')->orderByDesc('id')->get();
        $picked=Car::select('id','title','image','slug','price','category_id')->inRandomOrder()->get();
        $konforlu=Car::select('id','title','image','slug','price','capasity','category_id')->where('category_id','=','2')->inRandomOrder()->get();
        $data=[
            'setting'=>$setting,
            'daily'=>$daily,
            'last'=>$last,
            'picked'=>$picked,
            'slider'=>$slider,
            'page'=>'home',
            'konforlu'=>$konforlu

        ];
        return view('home.index',$data);
    }
    public static function categoryList()
    {
        return Category::where('parent_id','=',0)->with('children')->get();
    }


    public function car($id,$slug){
        $setting=Setting::first();
        $data=Car::find($id);
        $images=Image::where('car_id',$id)->get();
        $reviews=Review::where('car_id',$id)->get();


        return view('home.car_detail',['setting'=>$setting,'data'=>$data,'images'=>$images,'reviews'=>$reviews]);

    }
    public function categorycars($id,$slug){
        $setting=Setting::first();
        $datalist=Car::where('category_id',$id)->get();
        $data=Category::find($id);

        return view('home.category_cars',['data'=>$data,'datalist'=>$datalist,'setting'=>$setting]);

    }

    public function aboutus(){
        return view('home.about');
    }
    public function contact(){
        $setting=Setting::first();
        return view('home.contact',['setting'=>$setting,'page'=>'home']);
    }

    public function getcar(Request $request)
    {
        $search=$request->input('search');
        $count=Car::where('title','like','%'.$search.'%')->get()->count();
        if($count==1)
        {
            $data=Car::where('title','like','%'.$search.'%')->first();
            return redirect()->route('car',['id'=>$data->id,'slug'=>$data->slug]);
        }
        else
        {
            return redirect()->route('carlist',['search'=>$search]);
        }


    }
    public function carlist($search){
        $datalist=Car::where('title','like','%'.$search.'%')->get();

        return view('home.search_cars',['search'=>$search,'datalist'=>$datalist]);

    }
    public function references(){
        $setting=Setting::first();
        return view('home.references',['setting'=>$setting,'page'=>'home']);
    }


    public function sendmessage(Request $request)
    {
        $data = new Message();

        $data->name = $request->input('name');
        $data->email = $request->input('email');
        $data->phone = $request->input('phone');
        $data->subject = $request->input('subject');
        $data->message = $request->input('message');


        $data->save();

        return redirect()->route('contact')->with('success','Mesajınız kaydedilmiştir');
    }


    public function sendreview(Request $request,$id)
    {
        $data = new Review;

        $data->user_id = Auth::id();
        $car = Car::find($id);
        $data->car_id=$id;
        $data->subject = $request->input('subject');
        $data->review = $request->input('review');
        $data->IP = $_SERVER['REMOTE_ADDR'];

        $data->save();

        return redirect()->route('car',['id'=>$car->id,'slug'=>$car->slug])->with('success','Mesajınız kaydedilmiştir');
    }
    public function sendreserve(Request $request,$id)
    {
        $data = new Reserve;
        $data->user_id = Auth::id();
        $car = Car::find($id);
        $data->returnDate = $request->input('returnDate');
        $data->rezDate = $request->input('rezDate');
        $data->rezTime = $request->input('rezTime');
        $data->returnTime = $request->input('returnTime');
        $data->car_id = $id;
        $data->price = $car->price;
        $datetime1 = strtotime($data->rezDate); // convert to timestamps
        $datetime2 = strtotime($data->returnDate); // convert to timestamps
        $data->days = (int)(($datetime2 - $datetime1)/86400);

        $data->amount = $data->price * $data->days;



        $data->IP = $_SERVER['REMOTE_ADDR'];
        $data->save();

        return redirect()->route('car',['id'=>$car->id,'slug'=>$car->slug]);
    }


    public function faq(){
        $setting=Setting::first();
        $datalist=Faq::all()->sortBy('position');
        return view('home.faq',['datalist'=>$datalist,'setting'=>$setting]);
    }

    public function login(){
        return view('admin.login');
    }
    public function logincheck(Request $request)
    {
        if($request->isMethod('post'))
        {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                return redirect()->intended('admin');
            }

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }
        else
        {
            return view('admin.login');
        }
    }
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
