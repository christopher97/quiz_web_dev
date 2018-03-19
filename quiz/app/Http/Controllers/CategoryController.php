<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Validator;

class CategoryController extends Controller
{
    protected $category;

    public function __construct(Category $category) {
        $this->category = $category;
    }

    public function fetch() {
        $categories = $this->category->all();

        if (count($categories) > 0)
            return response()->json(['success' => true, 'data' => $categories], 200);

        return response()->json(['success' => true, 'data' => $categories], 204);
    }

    public function find($id) {
        $category = $this->category->find($id)->with('items')->first();

        if ($category)
            return response()->json(['success' => true, 'data' => $category], 200);

        return response()->json(['success' => false, 'message' => 'Incorrect category ID'], 400);
    }

    public function insert(Request $request) {
        $credentials = $request->only('name');
        $validator = $this->validateName($credentials);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()], 400);
        }

        $category = [
            'name' => $request->name
        ];

        $category = $this->category->create($category);

        return response()->json(['success' => 'true', 'data' => $category], 201);
    }

    public function update(Request $request, $id) {
        $credentials = $request->only('name');
        $validator = $this->validateName($credentials);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()], 400);
        }

        $category = $this->category->find($id);

        if ($category) {
            try {
                $category->name = $request->name;
                $category->save();
                return response()->json(['success' => true, 'data' => $category], 200);
            } catch(Exception $ex) {
                return response()->json(['success' => false, 'error' => $ex], 500);
            }
        }

        return response()->json(['success' => false, 'message' => 'Incorrect category ID'], 400);
    }

    public function delete($id) {
        $category = $this->category->find($id);

        if ($category) {
            try {
                $category->delete();
                return response()->json(['success' => true, 'message' => 'Category deleted'], 200);
            } catch(Exception $ex) {
                return response()->json(['success' => false, 'error' => $ex], 500);
            }
        }

        return response()->json(['success' => false, 'message' => 'Incorrect category ID'], 400);
    }

    private function validateName($credentials) {
        $rules = [
            'name' => 'required|max:255'
        ];

        $validator = Validator::make($credentials, $rules);
        return $validator;
    }
}
