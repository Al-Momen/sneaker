<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $pageTitle = 'Menu Management';
        $menus = Menu::searchable(['name'])->latest()->paginate(getPaginate());
        return view('Admin::menu.index',compact('pageTitle','menus'));
    }

    public function storeOrUpdate(Request $request, $id = null)
    {
        $request->validate([
            'name' => 'required|string|max:40|unique:menus,name,' . $id,
        ]);

        if ($id) {
            $menu = Menu::findOrFail($id);
            $message = 'Menu updated successfully';
        } else {
            $menu = new Menu();
            $message = 'Menu created successfully';
        }

        $menu->name = $request->name;
        $menu->slug = slug($request->name);
        $menu->save();
        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->status = $menu->status == Status::ENABLE ? Status::DISABLE : Status::ENABLE;
        $menu->save();

        $notify[] = ['success', 'Status change has been successfully'];
        return back()->withNotify($notify);
    }

    public function remove($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        $notify[] = ['success', 'Menu has been deleted successfully'];
        return back()->withNotify($notify);
    }


    public function assignMenuItem($id)
    {
        $menu = Menu::findOrFail($id);

        $items = MenuItem::where('status',Status::ENABLE)->get();
        $assigned = $menu->items()->get();

        $pageTitle = $menu->name . ' - Assign Menu Items';
        $menus = Menu::searchable(['name'])->latest()->paginate(getPaginate());
        return view('Admin::menu.assign_item',compact('pageTitle', 'menu', 'items', 'assigned'));
    }

    public function assignMenuItemSubmit(Request $request,$id)
    {
        $menu = Menu::findOrFail($id);

        if($request->menu_items) {
            $menu->items()->detach();
            $menu->items()->sync($request->menu_items);
            $notify[] = ['success', 'Menu items updated successfully'];
            return back()->withNotify($notify);
        }

        $notify[] = ['info', 'You have not selected any menu items'];
        return back()->withNotify($notify);

    }
}
