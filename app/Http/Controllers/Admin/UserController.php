<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\UserRequestEdit;
use App\Http\Requests\Admin\UserRequest;

class UserController extends Controller
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
            dd("ok");
            
            $query = User::query();

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
                                <a class="dropdown-item" href="'. route('user.edit', $item->id)  .'">Sunting</a>
                                <form action="'. route('user.destroy', $item->id) .'" method="POST">
                                '. method_field('delete'). csrf_field() .'
                                <button type="submit" class="dropdown-item text-danger"> Hapus </button>
                                </form>
                                </div>
                                
                            </div>
                        </div>
                    ';
                })
                ->rawColumns(['action'])
                ->make();
        }

        return view('pages.admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $data = $request->all();

        // encriypt password
        $data['password'] = bcrypt($request->password);
        
        // simpan ke database
        User::create($data);

        return redirect()->route('user.index');
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
        $item = User::findOrFail($id);


        return view('pages.admin.user.edit',[
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
    public function update(UserRequestEdit $request, $id)
    {
        $data = $request->all();

        // make a slug
        $data['slug'] = Str::slug($request->name);

        // pindahkan kan file foto ke folder assets
        if ($request->file('photo') != null) {
            $data['photo'] = $request->file('photo')->store('assets/user', 'public');
        }

        // simpan ke database
        $item = User::findOrFail($id);

        $item->update($data);

        return redirect()->route('user.index');
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
