<?php namespace Mrcore\Wiki\Http\Controllers\Admin;

use View;
use Config;
use Request;
use Input;
use Response;
use stdClass;
use Mrcore\Wiki\Models\Type;
use Mrcore\Wiki\Http\Controllers\Controller;

class TypeController extends Controller
{

    /**
     * Displays a DataTable
     *
     * @return Response
     */
    public function index()
    {
        $data = new stdClass();
        $data->name = 'Type';
        $data->partial = '_types';
        $data->dataUrl = 'type/data';
        
        return View::make('admin.content', compact('data'));
    }

    /**
     * Retrieves Data for the DataTable
     * @return json
     */
    public function getData()
    {
        $items = Type::select(array('id as ID', 'name as Name', 'constant as Constant'))->get();
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

        $type = new Type;
        $type->name = $name;
        $type->constant = $constant;
        $type->save();

        return Response::json([
            'message' => 'Success! Type Added!'
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
            $type = Type::find($id);
            $type->name = $name;
            $type->constant = $constant;
            $type->save();

            return Response::json([
                'message' => 'Success! Type Updated!'
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
            $type = Type::find($id);
            $type->delete();

            return Response::json([
                'message' => 'Success! Type Deleted!'
            ], 200);
        } else {
            return Response::json([
                'message' => 'Error! Delete Failed!'
            ], 400);
        }
    }
}
