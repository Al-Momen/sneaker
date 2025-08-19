<?php

namespace App\Http\Controllers\Admin;

use App\Models\Shipping;
use App\Constants\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShippingController extends Controller
{
    public function index($status = 'all')
    {

        $admin = auth()->guard('admin')->user();
        $query = Shipping::searchable(['title'])->latest();

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

        $shippings = $query->paginate(getPaginate());

        if (request()->ajax()) {
            return response()->json([
                'html' => view('Admin::components.tables.shipping_data', compact('shippings'))->render(),
                'pagination' => $shippings->hasPages() ? view('Admin::components.pagination', ['items' => $shippings])->render() : '',
            ]);
        }

        $pageTitle = ucfirst($status) . ' Shipping';
        return view('Admin::shippings.index', compact('shippings', 'pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'location' => 'required|string',
            'charge' => 'required|numeric|min:0'
        ]);

        $shipping = new Shipping();
        $shipping->location = $request->location;
        $shipping->charge = $request->charge;
        $shipping->status = isset($request->status) ? 1 : 0;
        $shipping->save();

        $notify[] = ['success', 'Shipping has been created successfully'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'location' => 'required|max:60',
            'charge' => 'required|numeric|min:0'
        ]);

        $shipping = Shipping::findOrFail($id);
        $shipping->location = $request->location;
        $shipping->charge = $request->charge;
        $shipping->status = isset($request->shippingStatus) ? 1 : 0;
        $shipping->save();
        $notify[] = ['success', 'Shipping has been updated successfully'];
        return back()->withNotify($notify);
    }
}
