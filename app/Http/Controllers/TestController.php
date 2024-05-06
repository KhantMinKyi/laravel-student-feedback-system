<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sentiment\Analyzer;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $text = $request->text;
        $analyzer = new Analyzer();
        $output = $analyzer->getSentiment($text);
        return $output;
        return view('test', compact('output'));
    }
}
