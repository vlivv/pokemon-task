<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BannedPokemon;
use Illuminate\Support\Facades\Http;

class BannedPokemonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BannedPokemon::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:banned_pokemon,name'
        ]);

        $pokemon = BannedPokemon::create($data);
        return response()->json($pokemon, 201);
    }

 

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BannedPokemon $banned)
    {
        $banned->delete();
        return response()->json(null, 204);
    }


      public function getPokemon($name)
    {
        
        if (BannedPokemon::where('name', $name)->exists()) {
            return response()->json(['error' => 'This pokemon is banned!'], 403);
        }

        $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$name}");

        if ($response->failed()) {
            return response()->json(['error' => 'Pokemon not found'], 404);
        }

        return $response->json();
    }

    public function getPokemons(Request $request)
    {
        $data = $request->validate([
            'pokemons' => 'required|array|min:1',
            'pokemons.*' => 'required|string'
        ]);

        $allowedPokemons = [];
        $bannedPokemons = [];

        foreach ($data['pokemons'] as $name) {
            if (BannedPokemon::where('name', $name)->exists()) {
                $bannedPokemons[] = $name;
                continue;
            }

            $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$name}");

            if ($response->successful()) {
                $allowedPokemons[] = $response->json();
            }
        }

        return response()->json([
            'allowed' => $allowedPokemons,
            'banned' => $bannedPokemons
        ]);
    }
}
