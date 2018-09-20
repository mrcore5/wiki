<?php namespace Mrcore\Wiki\Http\Controllers\Admin;

use View;
use Config;
use Request;
use Input;
use Response;
use stdClass;
use Mrcore\Auth\Models\Role;
use Mrcore\Wiki\Http\Controllers\Controller;

class RoleController extends Controller
{

    /**
     * Displays a DataTable
     *
     * @return Response
     */
    public function index()
    {
        $data = new stdClass();
        $data->name = 'Role';
        $data->partial = '_roles';
        $data->dataUrl = 'role/data';

        return View::make('admin.content', compact('data'));
    }

    /**
     * Retrieves Data for the DataTable
     * @return json
     */
    public function getData()
    {
        $items = Role::select(array('id as ID', 'name as Name', 'constant as Constant'))->get();
        foreach ($items as $item) {
            $item->Action = '<button class="btn btn-sm btn-warning btn-edit"><i class="fa fa-edit"></i></button>
                             <button class="btn btn-sm btn-danger btn-delete"><i class="fa fa-trash"></i>
                            </button>';
        }
        return $items;
    }

    /**
     * Adds a new entry
     * @return response
     */
    public function store()
    {
        $name = Input::get('name');
        $constant = Input::get('constant');

        $role = new Role;
        $role->name = $name;
        $role->constant = $constant;
        $role->save();

        return Response::json([
            'message' => 'Success! Role Added!'
        ], 200);
    }

    /**
     * Updates an existing entry
     * @return response
     */
    public function update()
    {
        $id = Input::get('id');
        $name = Input::get('name');
        $constant = Input::get('constant');

        if ($id != 0) {
            $role = Role::find($id);
            $role->name = $name;
            $role->constant = $constant;
            $role->save();

            return Response::json([
                'message' => 'Success! Role Updated!'
            ], 200);
        } else {
            return Response::json([
                'message' => 'Error! Update Failed!'
            ], 400);
        }
    }

    /**
     * Deletes an existing entry
     * @return response
     */
    public function destroy()
    {
        $id = Input::get('id');

        if ($id != 0) {
            $role = Role::find($id);
            $role->delete();

            return Response::json([
                'message' => 'Success! Role Deleted!'
            ], 200);
        } else {
            return Response::json([
                'message' => 'Error! Delete Failed!'
            ], 400);
        }
    }
}
