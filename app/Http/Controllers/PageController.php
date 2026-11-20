<?php

namespace App\Http\Controllers;

use App\Support\MenuCatalog;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function __construct(private readonly MenuCatalog $catalog)
    {
    }

    public function home(): View
    {
        return view('pawon.home', ['menu' => $this->catalog->all()]);
    }

    public function about(): View
    {
        return view('pawon.about');
    }

    public function menu(): View
    {
        return view('pawon.menu', ['menu' => $this->catalog->all()]);
    }

    public function booking(): View
    {
        return view('pawon.booking', ['menu' => $this->catalog->all()]);
    }

    public function contact(): View
    {
        return view('pawon.contact');
    }

    public function login(): View
    {
        return view('pawon.login');
    }

    public function register(): View
    {
        return view('pawon.register');
    }

    public function profile(): View
    {
        return view('pawon.profile', ['menu' => $this->catalog->all()]);
    }
}
