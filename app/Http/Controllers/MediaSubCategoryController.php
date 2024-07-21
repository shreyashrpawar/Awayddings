<?php

namespace App\Http\Controllers;

use App\Models\MediaSubCategory;
use Illuminate\Http\Request;

class MediaSubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MediaSubCategory  $mediaSubCategory
     * @return \Illuminate\Http\Response
     */
    public function show(MediaSubCategory $mediaSubCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MediaSubCategory  $mediaSubCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(MediaSubCategory $mediaSubCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MediaSubCategory  $mediaSubCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MediaSubCategory $mediaSubCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MediaSubCategory  $mediaSubCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(MediaSubCategory $mediaSubCategory)
    {
        //
    }

    public function getAllImageCategory(Request  $request){
                $image_categories = MediaSubCategory::where('media_category_id',1)->where('status',1)->get();

                return response()->json([
                   'success' => true,
                   'message' => 'Success',
                   'data' => $image_categories
                ]);
    }
    public function getAllVideoCategory(Request  $request){
        $video_categories = MediaSubCategory::where('media_category_id',2)->where('status',1)->get();

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'data' => $video_categories
        ]);
    }
    public function getAllMenuCategory(Request  $request){
        $menu_categories = MediaSubCategory::where('media_category_id',3)->where('status',1)->get();

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'data' => $menu_categories
        ]);
    }
}
