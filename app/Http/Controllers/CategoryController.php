<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Db;

class CategoryController extends Controller
{
    //index
    public function index()
    {
        $categories = Category::paginate(10);
        return view('pages.categories.index', compact('categories'));
    }

    // create
    public function create()
    {
        return view('pages.categories.create');
    }

    //store
    public function store(Request $request)
    {
        // validate the request
        $request->validate([
            'name'=> 'required',
            'image' => 'required|image|mimes:png,jpg,jpeg,giv,svg|max:2048',
        ]);

        // store the request
        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        // save image
        if ($request->hasFile('image')){
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $image->storeAs('public/categories', $category->id . '.' . $extension);
            $category->image = 'storage/categories/' . $category->id . '.' . $extension;
            $category->save();
        }

        return redirect()->route('categories.index')->with('success','Product Created Successfully');
    }

    // show
    public function show($id)
    {
        return view('pages.categories.show');
    }

    // edit
    public function edit($id)
    {
        $category = Category::find($id);
        return view('pages.categories.edit', compact('category'));
    }

    // update
    public function update(Request $request, $id)
    {
        // validate the request
        $request->validate([
            'name'=> 'required',
            // 'image' => 'required|image|mimes:png,jpg,jpeg,giv,svg|max:2048',
        ]);

        // update the request
        $category = Category::find($id);
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();


         // save image
         if ($request->hasFile('image')){
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $image->storeAs('public/categories', $category->id . '.' . $extension);
            $category->image = 'storage/categories/' . $category->id . '.' . $extension;
            $category->save();
        }

        return redirect()->route('categories.index')->with('success','Products Update Successfully');

    }

    // destroy
    public function destroy($id)
    {
        // delete the request
        $category = Category::find($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success','Products Delete Successfully');
    }
}
