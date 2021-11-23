<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\CategoryRequestEdit;
use App\Http\Requests\Admin\CategoryRequest;

class CategoryControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        // dd(request()->ajax());
        if (request()->ajax()) {
            
            $query = Category::query();

            return DataTables::of($query)
                ->addColumn('action', function($item){
                    return '
                        <div class="btn-group">
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle mr-1 mb-1"
                                        type="button"
                                        id = "action"
                                        data-toggle="dropdown">
                                        Aksi
                                </button>
                                
                                <div class="dropdown-menu">
                                <a class="dropdown-item" href="'. route('category.edit', $item->id)  .'">Sunting</a>
                                <form action="'. route('category.destroy', $item->id) .'" method="POST">
                                '. method_field('delete'). csrf_field() .'
                                <button type="submit" class="dropdown-item text-danger"> Hapus </button>
                                </form>
                                </div>
                                
                            </div>
                        </div>
                    ';
                })
                ->editColumn('photo', function($item){
                    return $item->photo ? '<img src="'. Storage::url($item->photo) .'" style="max-height: 48px;" />' : '';
                })
                ->rawColumns(['action', 'photo'])
                ->make();
        }

        return view('pages.admin.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $data = $request->all();

        // make a slug
        $data['slug'] = Str::slug($request->name);

        // pindahkan kan file foto ke folder assets
        $data['photo'] = $request->file('photo')->store('assets/category', 'public');

        // simpan ke database
        Category::create($data);

        return redirect()->route('category.index');
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
        $item = Category::findOrFail($id);


        return view('pages.admin.category.edit',[
            'item' => $item
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequestEdit $request, $id)
    {
        $data = $request->all();

        // make a slug
        $data['slug'] = Str::slug($request->name);

        // pindahkan kan file foto ke folder assets
        if ($request->file('photo') != null) {
            $data['photo'] = $request->file('photo')->store('assets/category', 'public');
        }

        // simpan ke database
        $item = Category::findOrFail($id);

        $item->update($data);

        return redirect()->route('category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
