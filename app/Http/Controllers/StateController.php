<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;
use App\Http\Resources\StateResource;
use App\Http\Requests\State\CreateRequest;
use App\Http\Requests\State\UpdateRequest;

class StateController extends Controller
{
    public function index()
    {
        return StateResource::collection(State::all());
    }

    public function store(CreateRequest $request)
    {
        $state = State::create($request->validated());
        return new StateResource($state);
    }

    public function show(State $state)
    {
        return new StateResource($state);
    }

    public function update(UpdateRequest $request, State $state)
    {
        $state->update($request->validated());
        return new StateResource($state);
    }

    public function destroy(State $state)
    {
        $state->delete();
        return response()->noContent();
    }
}
