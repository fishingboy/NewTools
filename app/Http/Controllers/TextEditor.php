<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;

class TextEditor extends Controller
{
    public function index(Request $request)
    {
        $input = $request->input('input');
        $output = json_encode(json_decode($input), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return view("text_editor", [
            "input" => $input,
            "output" => $output,
        ]);
    }
}
