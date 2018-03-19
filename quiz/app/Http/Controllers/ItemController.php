<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Validator;

class ItemController extends Controller
{
    protected $item;

    public function __construct(Item $item) {
        $this->item = $item;
    }

    public function fetch() {
        $items = $this->item->all();

        if (count($items) > 0)
            return response()->json(['success' => true, 'data' => $items], 200);

        return response()->json(['success' => true, 'data' => $items], 204);
    }

    public function find($id) {
        $item = $this->item->find($id)->with('category')->first();

        if ($item)
            return response()->json(['success' => true, 'data' => $item], 200);

        return response()->json(['success' => false, 'message' => 'Incorrect item ID'], 400);
    }

    public function insert(Request $request) {
        $credentials = $request->only('category_id', 'name', 'price', 'stock');
        $validator = $this->validateItem($credentials);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()], 400);
        }

        $item = [
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock
        ];

        $item = $this->item->create($item);

        return response()->json(['success' => 'true', 'data' => $item], 201);
    }

    public function update(Request $request, $id) {
        $credentials = $request->only('category_id', 'name', 'price', 'stock');
        $validator = $this->validateItem($credentials);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()], 400);
        }

        $item = $this->item->find($id);

        if ($item) {
            try {
                $item->category_id = $request->category_id;
                $item->name = $request->name;
                $item->price = $request->price;
                $item->stock = $request->stock;
                $item->save();
                return response()->json(['success' => true, 'data' => $item], 200);
            } catch(Exception $ex) {
                return response()->json(['success' => false, 'error' => $ex], 500);
            }
        }

        return response()->json(['success' => false, 'message' => 'Incorrect item ID'], 400);
    }

    public function delete($id) {
        $item = $this->item->find($id);

        if ($item) {
            try {
                $item->delete();
                return response()->json(['success' => true, 'message' => 'Item deleted'], 200);
            } catch(Exception $ex) {
                return response()->json(['success' => false, 'error' => $ex], 500);
            }
        }

        return response()->json(['success' => false, 'message' => 'Incorrect item ID'], 400);
    }

    private function validateItem($credentials) {
        $rules = [
            'category_id' => 'required|integer',
            'name' => 'required|max:255',
            'price' => 'required|min:0',
            'stock' => 'required'
        ];

        $validator = Validator::make($credentials, $rules);
        return $validator;
    }
}
