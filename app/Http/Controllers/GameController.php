<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameResult;

class GameController extends Controller
{
    public function index()
    {
        return view('game');
    }
    
    public function store(Request $request){
        $game = GameResult::create($request->all());
        return response()->json($game,201);
    }
}
