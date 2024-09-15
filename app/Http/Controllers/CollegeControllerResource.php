<?php

namespace App\Http\Controllers;

use App\Actions\HandleDataBeforeSaveAction;
use App\Filter\EndDateFilter;
use App\Filter\GovernmentIdFilter;
use App\Filter\NameFilter;
use App\Filter\StartDateFilter;
use App\Filter\SubjectIdFilter;
use App\Http\Requests\CollegeFormRequest;
use App\Http\Resources\CollegeResource;
use App\Models\College;
use App\Services\Messages;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class CollegeControllerResource extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('store','update');
    }

    public function index()
    {
        $data = College::query()->with('government');
        $result = app(Pipeline::class)
            ->send($data)
            ->through([
                NameFilter::class,
                StartDateFilter::class,
                EndDateFilter::class,
                SubjectIdFilter::class,
                GovernmentIdFilter::class,
            ])
            ->thenReturn()
            ->get();
//        return $result;
        return CollegeResource::collection($result);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function save($data)
    {
        $output = College::query()->updateOrCreate([
            'id'=> $data['id'] ?? null
        ],$data);
        $output->load('government');
//        return Messages::success($output,__('messages.saved_successfully'));
        return Messages::success(CollegeResource::make($output),__('messages.saved_successfully'));
    }
    public function store(CollegeFormRequest $request)
    {
        $data = $request->validated();
        $handled_data = HandleDataBeforeSaveAction::handle($data);
//        return $handled_data;
//        College::query()->create($handled_data);
        return $this->save($handled_data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = College::query()->findOrFail($id);
        return $item;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CollegeFormRequest $request, string $id)
    {
        $data = $request->validated();
        $handled_data = HandleDataBeforeSaveAction::handle($data);
        $handled_data['id'] = $id;
        return $this->save($handled_data);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
