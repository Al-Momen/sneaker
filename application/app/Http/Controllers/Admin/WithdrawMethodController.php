<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\WithdrawMethod;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class WithdrawMethodController extends Controller
{
    public function methods(Request $request, $status = 'all')
    {
        $query = WithdrawMethod::latest();

        switch ($status) {
            case '1':
                $query->where('status', 1);
                break;
            case '0':
                $query->where('status', 0);
                break;
            case 'all':
                $query->whereIn('status', [0, 1]);
                break;
            default:
                break;
        }

        $items = $query->searchable(['name'])->latest()->paginate(getPaginate());

        if ($request->ajax()) {
            return response()->json([
                'html' => view('Admin::components.tables.withdraw_method_data', compact('items'))->render(),
                'pagination' => $items->hasPages() ? view('Admin::components.pagination', compact('items'))->render() : '',
            ]);
        }

        $pageTitle = 'Withdrawal Methods';
        return view('Admin::withdraw.index', compact('pageTitle', 'items'));
    }


    public function create()
    {
        $pageTitle = 'New Withdrawal Method';
        return view('Admin::withdraw.create', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $validation = [
            'namedd' => 'required',
            'ratedd' => 'required|numeric|gt:0',
            'currencydd' => 'required',
            'min_limitdd' => 'required|numeric|gt:0',
            'max_limitdd' => 'required|numeric|gt:min_limit',
            'fixed_chargedd' => 'required|numeric|gte:0',
            'percent_chargedd' => 'required|numeric|between:0,100',
            'instructiondd' => 'required',
            'imagedd' => ['required', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ];


        $formProcessor = new FormProcessor();
        $generatorValidation = $formProcessor->generatorValidation();
        $validation = array_merge($validation,$generatorValidation['rules']);
        $request->validate($validation,$generatorValidation['messages']);

        $generate = $formProcessor->generate('withdraw_method');

        $method = new WithdrawMethod();
        $method->name = $request->name;
        $method->form_id = $generate->id ?? 0;
        $method->rate = $request->rate;
        $method->min_limit = $request->min_limit;
        $method->max_limit = $request->max_limit;
        $method->fixed_charge = $request->fixed_charge;
        $method->percent_charge = $request->percent_charge;
        $method->currency = $request->currency;
        $method->description = $request->instruction;


        if($request->hasFile('image')) {
            $method->image =fileUploader($request->image,getFilePath('withdrawMethod'), getFileSize('withdrawMethod'));
        }

        $method->save();


        $notify[] = ['success', 'Withdraw method has been added successfully'];
        return to_route('admin.withdraw.method.index')->withNotify($notify);
    }


    public function edit($id)
    {
        $pageTitle = 'Update Withdrawal Method';
        $method = WithdrawMethod::with('form')->findOrFail($id);
        $form = $method->form;
        return view('Admin::withdraw.edit', compact('pageTitle', 'method','form'));
    }

    public function update(Request $request, $id)
    {
        $validation = [
            'name'           => 'required',
            'min_limit'      => 'required|numeric|gt:0',
            'max_limit'      => 'required|numeric|gt:min_limit',
            'fixed_charge'   => 'required|numeric|gte:0',
            'percent_charge' => 'required|numeric|between:0,100',
            'currency'       => 'required',
            'instruction'    => 'required',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ];

        $formProcessor = new FormProcessor();
        $generatorValidation = $formProcessor->generatorValidation();
        $validation = array_merge($validation,$generatorValidation['rules']);
        $request->validate($validation,$generatorValidation['messages']);

        $method = WithdrawMethod::findOrFail($id);

        $generate = $formProcessor->generate('withdraw_method',true,'id',$method->form_id);
        $method->form_id        = $generate->id ?? 0;
        $method->name           = $request->name;
        $method->rate           = $request->rate;
        $method->min_limit      = $request->min_limit;
        $method->max_limit      = $request->max_limit;
        $method->fixed_charge   = $request->fixed_charge;
        $method->percent_charge = $request->percent_charge;
        $method->description    = $request->instruction;
        $method->currency       = $request->currency;

        if($request->hasFile('image')) {
            $method->image =fileUploader($request->image,getFilePath('withdrawMethod'), getFileSize('withdrawMethod'), $method->image);
        }

        $method->save();


        $notify[] = ['success', 'Withdraw method has been updated successfully'];
        return back()->withNotify($notify);
    }


    public function activate($id)
    {
        $method = WithdrawMethod::findOrFail($id);
        $method->status = 1;
        $method->save();
        $notify[] = ['success', 'Withdraw method activated successfully'];
        return to_route('admin.withdraw.method.index')->withNotify($notify);
    }

    public function deactivate($id)
    {
        $method = WithdrawMethod::findOrFail($id);
        $method->status = 0;
        $method->save();
        $notify[] = ['success', 'Withdraw method deactivated successfully'];
        return to_route('admin.withdraw.method.index')->withNotify($notify);
    }

}
