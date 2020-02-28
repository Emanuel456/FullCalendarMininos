<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\evento;

class EventosController extends Controller
{

    public function index()
    {
        return view('eventos.index');
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $datosEvento = $request->except(['_token', '_method']);
        evento::insert($datosEvento);
        print_r($datosEvento);
    }


    public function show()
    {
        $data['eventos'] = evento::all();

        return response()->json($data['eventos']);
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $datosEvento = $request->except(['_token', '_method']);
        $repuesta = evento::where('id', '=', $id)->update($datosEvento);

        return response()->json($repuesta);
    }


    public function destroy($id)
    {
        $eventos = evento::findOrFail($id);
        $eventos->destroy($id);
        return response()->json($id);
    }
}
