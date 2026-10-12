<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use Illuminate\Support\Facades\DB;

class Post extends Controller
{
    public function index()
    {
          return view('livewire.posts');
    }
    
      public function post_tab()
    {
          return view('livewire.post_tab');
    }
}
