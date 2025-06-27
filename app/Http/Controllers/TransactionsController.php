<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function index()
    {
        return view('transacciones');
    }

    public function create()
    {
        return view('transacciones');
    }

    public function store(Request $request)
    {
        // Lógica para guardar en localStorage vía JS
        return redirect()->route('transacciones.index');
    }

    public function show($id)
    {
        return view('transacciones');
    }

    public function edit($id)
    {
        return view('transacciones');
    }

    public function update(Request $request, $id)
    {
        // Lógica para actualizar en localStorage vía JS
        return redirect()->route('transacciones.index');
    }

    public function destroy($id)
    {
        // Lógica para eliminar en localStorage vía JS
        return redirect()->route('transacciones.index');
    }
}