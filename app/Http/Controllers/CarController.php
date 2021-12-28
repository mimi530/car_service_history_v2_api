<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarRequest;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index()
    {
        return response()->json(['cars' => Car::all()]);
    }

    public function show(Car $car)
    {
        return response()->json(['car' => $car]);
    }

    public function store(CarRequest $request)
    {
        $car = User::first()->cars()->create($request->validated());
        return response()->json([
            'msg' => 'ok',
            'car' => $car
        ], 201);
    }

    public function update(Car $car, CarRequest $request)
    {
        $car->update($request->validated());
        return response()->json([
            'msg' => 'ok',
            'car' => $car
        ]);
    }

    public function destroy(Car $car)
    {
        $car->delete();
        return response()->json([
            'msg' => 'ok'
        ]);
    }
}
