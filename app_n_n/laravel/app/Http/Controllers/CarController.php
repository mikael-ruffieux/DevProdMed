<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\User;
use App\Http\Requests\CarRequest;

class CarController extends Controller
{
    protected $carsPerPage = 10;

    public function __construct() {
        $this->middleware('auth', ['except'=>'index']);
        $this->middleware('admin', ['only'=>'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cars=Car::with('user')
                ->orderBy('cars.created_at','desc')
                ->paginate($this->carsPerPage);
        $links=$cars->render();
        return view('view_voitures', compact('cars','links'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        return view('view_ajoute_voiture', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CarRequest $request) {
        if(!isset($request->user_id)) {
            $inputs=array_merge($request->all(), ['user_id'=>$request->user()->id]);
        } else {
            $inputs = $request->all();
        }
        
        Car::create($inputs);
        return redirect(route('cars.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        Car::findOrFail($id)->delete();  
        return redirect()->back();
    }
}
