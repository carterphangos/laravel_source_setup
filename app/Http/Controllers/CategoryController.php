<?php

namespace App\Http\Controllers;

use App\Enums\BaseLimit;
use App\Enums\CategoryColumns;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $categories = $this->categoryService->getCachedData(
            $request->input('perPage') === 'null' ? null : BaseLimit::LIMIT_10,
            $request->except('perPage'),
            [],
            [CategoryColumns::Name],
            $request->input('termSearch')
        );

        return response()->json([
            'status' => true,
            'message' => 'All Categories Get Successfully',
            'data' => $categories,
        ], Response::HTTP_OK);
    }

    public function store(CreateCategoryRequest $request)
    {
        $category = $this->categoryService->createCategory($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Category Create Successfully',
            'data' => $category,
        ], Response::HTTP_OK);
    }

    public function show($id)
    {
        $category = $this->categoryService->getById($id);

        return response()->json([
            'status' => true,
            'message' => 'Category Get Successfully',
            'data' => $category,
        ], Response::HTTP_OK);
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        try {
            $category = $this->categoryService->updateCategory($id, $request->all());

            return response()->json([
                'status' => true,
                'message' => 'Category Updated Successfully',
                'data' => $category,
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json([
                    'status' => false,
                    'message' => 'The category name has already been taken.',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while updating category.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        $this->categoryService->deleteCategory($id);

        return response()->json([
            'status' => true,
            'message' => 'Category Deleted Successfully',
        ], Response::HTTP_OK);
    }
}
