<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\Console\Input\Input;
class Test extends Controller
{
    public function index(Request $request)
    {
        $name = $request->post('name');
        return [
            'name' => $name
        ];
    }
}