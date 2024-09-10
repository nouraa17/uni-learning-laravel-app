<?php

namespace App\Http\Controllers;

use App\Filter\EndDateFilter;
use App\Filter\NameFilter;
use App\Filter\StartDateFilter;
use App\Models\Government;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class GovernmentControllerResource extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
//        $data = Government::query();
//        if(request()->filled('filter_name')){
//            $data->where('name', 'like', '%'.request('filter_name').'%');
//        }
//        if(request()->filled('filter_start_date')){
//            $data->where('created_at', '>=',request('filter_start_date'));
//        }
//        if(request()->filled('filter_end_date')){
//            $data->where('created_at', '<=',request('filter_end_date'));
//        }
//        return $data->get();
////////////////////////////////instead of repeat ->
        $data = Government::query();
        $result = app(Pipeline::class)
            ->send($data)
            ->through([
                NameFilter::class,
                StartDateFilter::class,
                EndDateFilter::class,
            ])
            ->thenReturn()
            ->get();
        return $result;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Government::query()->findOrFail($id);
        return $item;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
