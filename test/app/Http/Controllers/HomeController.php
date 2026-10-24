<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index()
    {
        return view('home');
    }
    public function new($new)
    {
        $new = 'tabn';
        return view('new', ['new' => $new]);
    }
    public function bootstrap(Request $request)
    {
        return view('bootstrap');
    }
    public function create(Request $request){
        $title = $request->input('title');
        $slug = $request->input('slug');
        $description = $request->input('description');

        if (is_null($title) || is_null($slug) || is_null($description)) {
            return back()->withInput()->with('message', 'All fields are required.');
        }
        return view("product_view",[
            'title' => $title,
            'slug' => $slug,
            'description' => $description
        ]);
    }
}