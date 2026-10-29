<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Productos iniciales de la práctica.
     */
    private function obtenerProductos(): array
    {
        $productosIniciales = [
            1 => [
                'id' => 1,
                'nombre' => 'Laptop',
                'categoria' => 'Tecnología',
                'precio' => 15999.00,
            ],
            2 => [
                'id' => 2,
                'nombre' => 'Mouse',
                'categoria' => 'Tecnología',
                'precio' => 299.00,
            ],
            3 => [
                'id' => 3,
                'nombre' => 'Escritorio',
                'categoria' => 'Muebles',
                'precio' => 2499.00,
            ],
            4 => [
                'id' => 4,
                'nombre' => 'Silla',
                'categoria' => 'Muebles',
                'precio' => 1899.00,
            ],
        ];

        return session()->get('productos', $productosIniciales);
    }

    /**
     * Mostrar productos y aplicar filtros por nombre y categoría.
     */
    public function index(Request $request)
    {
        $todos = $this->obtenerProductos();
        $buscar = $request->query('buscar');
        $categoria = $request->query('categoria');

        $productos = collect($todos);

        if ($buscar) {
            $productos = $productos->filter(function ($producto) use ($buscar) {
                return str_contains(
                    mb_strtolower($producto['nombre']),
                    mb_strtolower($buscar)
                );
            });
        }

        if ($categoria) {
            $productos = $productos->where('categoria', $categoria);
        }

        $categorias = collect($todos)
            ->pluck('categoria')
            ->unique();

        return view('productos.index', compact(
            'productos',
            'categorias',
            'buscar',
            'categoria'
        ));
    }

    /**
     * Registrar un producto.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|min:3|max:100',
            'categoria' => 'required|string|max:50',
            'precio' => 'required|numeric|min:0',
        ]);

        $productos = $this->obtenerProductos();

        $id = empty($productos)
            ? 1
            : max(array_keys($productos)) + 1;

        $productos[$id] = ['id' => $id] + $datos;

        session()->put('productos', $productos);

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    /**
     * Actualizar un producto.
     */
    public function update(Request $request, int $id)
    {
        $productos = $this->obtenerProductos();

        if (!isset($productos[$id])) {
            return redirect()->route('productos.index')
                ->with('error', 'El producto no existe.');
        }

        $datos = $request->validate([
            'nombre' => 'required|string|min:3|max:100',
            'categoria' => 'required|string|max:50',
            'precio' => 'required|numeric|min:0',
        ]);

        $productos[$id] = ['id' => $id] + $datos;

        session()->put('productos', $productos);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * Eliminar un producto.
     */
    public function destroy(int $id)
    {
        $productos = $this->obtenerProductos();

        if (!isset($productos[$id])) {
            return redirect()->route('productos.index')
                ->with('error', 'El producto no existe.');
        }

        unset($productos[$id]);

        session()->put('productos', $productos);

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}
