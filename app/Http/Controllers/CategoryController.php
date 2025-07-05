<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,NULL,id,user_id,'.$request->user()->id,
            'type' => ['required', Rule::in(['income', 'expense'])],
            'from_transaction' => 'sometimes|boolean'
        ]);

        $category = $request->user()->categories()->create($validated);

        if ($request->from_transaction) {
            return response()->json([
                'id' => $category->id,
                'name' => $category->name,
                'type' => $category->type
            ]);
        }

        return back()->with('success', 'Categoría creada exitosamente');
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        // Verificación de autorización usando Gate
        if (!Gate::allows('update', $category)) {
            abort(403, 'No tienes permiso para actualizar esta categoría');
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($category->id)->where('user_id', $request->user()->id)
            ],
            'type' => ['required', Rule::in(['income', 'expense'])]
        ]);

        $category->update($validated);

        return back()->with('success', 'Categoría actualizada exitosamente');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        // Verificación de autorización usando Gate
        if (!Gate::allows('delete', $category)) {
            abort(403, 'No tienes permiso para eliminar esta categoría');
        }

        DB::transaction(function () use ($category) {
            // Verificar si hay transacciones asociadas
            if ($category->transactions()->exists()) {
                return back()->with('error', 'No puedes eliminar una categoría con transacciones asociadas');
            }

            $category->delete();
        });

        return back()->with('success', 'Categoría eliminada correctamente');
    }

    /**
     * Get categories for API (used in transactions form)
     */
    public function getCategories(Request $request)
    {
        $categories = $request->user()->categories()
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->orderBy('name')
            ->get();

        return response()->json($categories);
    }
}