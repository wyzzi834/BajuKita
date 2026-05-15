<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\ProductRepository;
use App\Services\Backend\Product\ProductDeleteService;
use App\Services\Backend\Product\ProductIndexService;
use App\Services\Backend\Product\ProductStoreService;
use App\Services\Backend\Product\ProductUpdateService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // call service product index
        $response = (new ProductIndexService)->handle();
        // dd($response);
        return view('backend.product.index', $response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->ajax()) {
            # code...
            return response()->json((new ProductRepository)->searchKategori($request));
        }
        return view('backend.product.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return (new ProductStoreService)->handle($request);
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
        $data = (new ProductRepository)->getById($id);
        if (empty($data)) {
            # code...
            return redirect()->route('admin.product.index')->with('galat', 'Product Not Found');
        }
        return view('backend.product.edit', compact('data'));
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
        return (new ProductUpdateService)->handle($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return (new ProductDeleteService)->handle($id);
    }
}
