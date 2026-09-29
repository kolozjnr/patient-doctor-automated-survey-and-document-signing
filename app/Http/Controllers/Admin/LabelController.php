<?php

namespace App\Http\Controllers\Admin;

use App\Models\Label;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Admin\LabelRepository;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct(private LabelRepository $labelRepository)
    {
        $this->labelRepository = $labelRepository;
    }
    public function index()
    {
         $labels = Label::select('id', 'label', 'color')->get();
        return view('admin.labels.index', compact('labels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
          \Log::info('Labels data received:', $request->all());
          
        $validator = Validator::make($request->all(), [
            'labels' => 'required|array',
            'labels.*.id' => 'nullable|exists:labels,id',
            'labels.*.label' => 'required|string|max:255',
            'labels.*.color' => 'required|string',
            'labels.*.category' => 'required|string|max:255',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
         $this->labelRepository->storeOrUpdate($request->labels);

        return response()->json([
            'message' => 'Labels processed successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
