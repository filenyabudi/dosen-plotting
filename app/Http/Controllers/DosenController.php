<?php

namespace App\Http\Controllers;

use App\Http\Requests\DosenStoreRequest;
use App\Http\Requests\DosenUpdateRequest;
use App\Models\Dosen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DosenController extends Controller
{
    public function index(Request $request): View
    {
        $dosens = Dosen::all();

        return view('dosen.index', compact('dosens'));
    }

    public function create(Request $request): View
    {
        return view('dosen.create');
    }

    public function store(DosenStoreRequest $request): RedirectResponse
    {
        $dosen = Dosen::create($request->validated());

        return redirect()->route('dosen.index');
    }

    public function show(Request $request, Dosen $dosen): View
    {
        return view('dosen.show', compact('dosen'));
    }

    public function edit(Request $request, Dosen $dosen): View
    {
        return view('dosen.edit', compact('dosen'));
    }

    public function update(DosenUpdateRequest $request, Dosen $dosen): RedirectResponse
    {
        $dosen->update($request->validated());

        return redirect()->route('dosen.index');
    }
}
