<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UnauthController extends Controller {
    public function index() {
        return response()
            ->view("index")
            ->header("Biggest-Thing", "Your-Mom");
    }

    public function about() {
        return "about page";
    }

    public function contact() {
        return "contact page";
    }
}
