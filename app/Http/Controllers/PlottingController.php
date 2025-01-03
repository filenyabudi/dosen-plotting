<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlottingStoreRequest;
use App\Http\Requests\PlottingUpdateRequest;
use App\Models\Plotting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlottingController extends Controller
{
    public function index(Request $request): View
    {
        $plottings = Plotting::all();

        return view('plotting.index', compact('plottings'));
    }

    public function create(Request $request): View
    {
        return view('plotting.create');
    }

    public function store(PlottingStoreRequest $request): RedirectResponse
    {
        $plotting = Plotting::create($request->validated());

        return redirect()->route('plotting.index');
    }

    public function show(Request $request, Plotting $plotting): View
    {
        return view('plotting.show', compact('plotting'));
    }

    public function edit(Request $request, Plotting $plotting): View
    {
        return view('plotting.edit', compact('plotting'));
    }

    public function update(PlottingUpdateRequest $request, Plotting $plotting): RedirectResponse
    {
        $plotting->update($request->validated());

        return redirect()->route('plotting.index');
    }
}
