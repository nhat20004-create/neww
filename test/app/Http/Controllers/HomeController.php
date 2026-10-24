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
    public function blog()
    {
        $posts = [
            [
                'title' => 'Xu hướng công nghệ nổi bật hiện nay',
                'category' => 'Công nghệ',
                'date' => '24/10/2026',
                'description' => 'Khám phá những xu hướng công nghệ mới và tác động của chúng đến cuộc sống.',
                'image' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=800'
            ],
            [
                'title' => 'Phương pháp học tập hiệu quả cho sinh viên',
                'category' => 'Học tập',
                'date' => '22/10/2026',
                'description' => 'Một số phương pháp giúp quản lý thời gian và nâng cao hiệu quả học tập.',
                'image' => 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=800'
            ],
            [
                'title' => 'Khám phá những điều thú vị trong cuộc sống',
                'category' => 'Đời sống',
                'date' => '20/10/2026',
                'description' => 'Những câu chuyện nhỏ mang đến góc nhìn mới về cuộc sống hằng ngày.',
                'image' => 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=800'
            ]
        ];

        return view('blog', compact('posts'));    }
}