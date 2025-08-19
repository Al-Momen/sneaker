<?php

namespace App\Http\Controllers\Admin;

use App\Models\Color;
use App\Constants\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ColorController extends Controller
{
    public function index($status = 'all')
    {
        
        $admin = auth()->guard('admin')->user();

        $query = Color::searchable(['name'])->latest();

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

        $colors = $query->paginate(getPaginate());

        if (request()->ajax()) {
            return response()->json([
                'html' => view('Admin::components.tables.color_data', compact('colors'))->render(),
                'pagination' => $colors->hasPages() ? view('Admin::components.pagination', ['items' => $colors])->render() : '',
            ]);
        }

        $pageTitle = ucfirst($status) . ' Colors';
        return view('Admin::color.index', compact('colors', 'pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:60',
            'code' => 'required|max:60',
            'status' => 'nullable|in:0,1',
        ]);

        $color = new Color();
        $color->name = $request->name;
        $color->code = $request->code;
        $color->status = isset($request->status) ? 1 : 0;
        $color->save();

        $notify[] = ['success', 'Color has been created successfully'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:60',
            'code' => 'required|max:60',
             'status' => 'nullable|in:0,1',
        ]);

        $color = Color::findOrFail($id);
        $color->name = $request->name;
        $color->code = $request->code;
        $color->status = isset($request->color_status) ? 1 : 0;
        $color->save();
        $notify[] = ['success', 'Color has been updated successfully'];
        return back()->withNotify($notify);
    }
}
