<?php

namespace App\Http\Controllers;

use App\Services\TextEditorService;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;

class TextEditor extends Controller
{
    /**
     * @var TextEditorService
     */
    private $text_editor;

    public function __construct()
    {
        $this->text_editor = new TextEditorService();
    }

    public function index(Request $request)
    {
        $input = $request->input('input');
        $action = $request->input('action');

        $output = $this->text_editor->convert($input, $action);

        return view("text_editor", [
            "input" => $input,
            "output" => $output,
        ]);
    }

}
