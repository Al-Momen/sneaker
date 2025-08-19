<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Constants\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index($status = 'all')
    {

        $admin = auth()->guard('admin')->user();

        $query = Category::searchable(['name'])->latest();

        switch ($status) {
            case 'disable':
                $query->where('status', Status::DISABLE);
                break;
            case 'enable':
                $query->where('status', Status::ENABLE);
                break;
            case 'all':
                $query->whereIn('status', [Status::ENABLE, Status::DISABLE]);
                break;
            default:
                break;
        }

        $categories = $query->paginate(getPaginate());

        if (request()->ajax()) {
            return response()->json([
                'html' => view('Admin::components.tables.category_data', compact('categories'))->render(),
                'pagination' => $categories->hasPages() ? view('Admin::components.pagination', compact('categories'))->render() : '',
            ]);
        }

        $pageTitle = ucfirst($status) . ' Categories';
        return view('Admin::category.index', compact('categories', 'pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:60',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->status = isset($request->status) ? 1 : 0;
        $category->save();

        $notify[] = ['success', 'Category has been created successfully'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:60'
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->status = isset($request->catstatus) ? 1 : 0;
        $category->save();
        $notify[] = ['success', 'Category has been updated successfully'];
        return back()->withNotify($notify);
    }
}
