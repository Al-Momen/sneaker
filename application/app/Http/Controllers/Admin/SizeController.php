<?php

namespace App\Http\Controllers\Admin;

use App\Models\Size;
use App\Constants\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SizeController extends Controller
{
    public function index($status = 'all')
    {
        
        $admin = auth()->guard('admin')->user();

        $query = Size::searchable(['size'])->latest();

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

        $sizes = $query->paginate(getPaginate());

        if (request()->ajax()) {
            return response()->json([
                'html' => view('Admin::components.tables.size_data', compact('sizes'))->render(),
                'pagination' => $sizes->hasPages() ? view('Admin::components.pagination', ['items' => $sizes])->render() : '',
            ]);
        }

        $pageTitle = ucfirst($status) . ' Sizes';
        return view('Admin::size.index', compact('sizes', 'pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'size' => 'required|max:60'
        ]);
        $size = new Size();
        $size->size = $request->size;
        $size->status = isset($request->status) ? 1 : 0;
        $size->save();

        $notify[] = ['success', 'Size has been created successfully'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'size' => 'required|max:60'
        ]);

        $size = Size::findOrFail($id);
        $size->size = $request->size;
        $size->status = isset($request->sizeStatus) ? 1 : 0;

        $size->save();
        $notify[] = ['success', 'Size has been updated successfully'];
        return back()->withNotify($notify);
    }
}
