<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseCategoryRequest;
use App\Http\Requests\UpdateCourseCategoryRequest;
use App\Models\CourseCategory;

use App\Repositories\CourseCategoryRepository;
use Datatables;
Use App\Services\CommonService;

class CourseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     /**
     * @var courseCategoryRepository
     */

     /**
     * @var commonService
     */
    private $commonService;

    private $courseCategoryRepository;

    public function __construct(CourseCategoryRepository $courseCategoryRepository) {
        $this->courseCategoryRepository = $courseCategoryRepository;
    }



    public function index()
    {

        return 'ttt';
        try {
            if ($request->ajax()) {
                $data = Category::select('*');
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->editColumn('updated_at',function($row){
                            return date('Y-m-d H:i', strtotime($row->updated_at));
                        })
                        ->addColumn('action', function($row){
                                $btn = '<a href="'.route('get.category',$row->id).'">Edit </a>';
                                return $btn;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
            }
            return view('course_category.main-category',['data'=>[]]);
        } catch (\Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
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
     * @param  \App\Http\Requests\StoreCourseCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCourseCategoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CourseCategory  $courseCategory
     * @return \Illuminate\Http\Response
     */
    public function show(CourseCategory $courseCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CourseCategory  $courseCategory
     * @return \Illuminate\Http\Response
     */


    public function edit(CourseCategory $courseCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCourseCategoryRequest  $request
     * @param  \App\Models\CourseCategory  $courseCategory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCourseCategoryRequest $request, CourseCategory $courseCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CourseCategory  $courseCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseCategory $courseCategory)
    {
        //
    }
}
