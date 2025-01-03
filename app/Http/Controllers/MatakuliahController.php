<?php

namespace App\Http\Controllers;

use App\Http\Requests\MatakuliahStoreRequest;
use App\Http\Requests\MatakuliahUpdateRequest;
use App\Models\Matakuliah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MatakuliahController extends Controller
{
    public function index(Request $request): View
    {
        $matakuliahs = Matakuliah::all();

        return view('matakuliah.index', compact('matakuliahs'));
    }

    public function create(Request $request): View
    {
        return view('matakuliah.create');
    }

    public function store(MatakuliahStoreRequest $request): RedirectResponse
    {
        $matakuliah = Matakuliah::create($request->validated());

        return redirect()->route('matakuliah.index');
    }

    public function show(Request $request, Matakuliah $matakuliah): View
    {
        return view('matakuliah.show', compact('matakuliah'));
    }

    public function edit(Request $request, Matakuliah $matakuliah): View
    {
        return view('matakuliah.edit', compact('matakuliah'));
    }

    public function update(MatakuliahUpdateRequest $request, Matakuliah $matakuliah): RedirectResponse
    {
        $matakuliah->update($request->validated());

        return redirect()->route('matakuliah.index');
    }
}
