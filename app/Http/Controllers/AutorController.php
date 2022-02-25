<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use App\Models\AutoresHasLibro;
use Illuminate\Http\Request;
use DB;

class AutorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (isset($request->search)) {
            $buscar = $request->search;
        }
        $items = Autor::withCount('libros');
        if (isset($buscar)) {
            $items = $items->where(function ($q) use ($buscar) {
                $q->orWhere('nombre', 'LIKE', '%' . $buscar . '%')->orWhere('apellidos', 'LIKE', '%' . $buscar . '%');
            });
        }
        $items = $items->get();
        return $items;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules =
            [
                'form_autor.nombre' => 'required|max:45',
                'form_autor.apellidos' => 'required|max:45'
            ];

        $niceNames = array(
            'form_autor.nombre' => 'NOMBRE',
            'form_autor.apellidos' => 'APELLIDOS'
        );

        $this->validate($request, $rules, [], $niceNames);

        DB::beginTransaction();
        try {

            $nombre = $request->form_autor['nombre'];
            $apellidos = $request->form_autor['apellidos'];

            $insert_autor = new Autor();
            $insert_autor->nombre = $nombre;
            $insert_autor->apellidos = $apellidos;
            $insert_autor->save();

            DB::commit();

            return 'ok';
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules =
            [
                'form_autor.nombre' => 'required|max:45',
                'form_autor.apellidos' => 'required|max:45'
            ];

        $niceNames = array(
            'form_autor.nombre' => 'NOMBRE',
            'form_autor.apellidos' => 'APELLIDOS'
        );

        $this->validate($request, $rules, [], $niceNames);

        DB::beginTransaction();
        try {

            $nombre = $request->form_autor['nombre'];
            $apellidos = $request->form_autor['apellidos'];

            $insert_autor = Autor::where('id', $id)->first();
            if ($insert_autor) {
                $insert_autor->nombre = $nombre;
                $insert_autor->apellidos = $apellidos;
                $insert_autor->save();
            }


            DB::commit();

            return 'ok';
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $validar_autor_has_libro = AutoresHasLibro::where('id_autor', $id)->count();
            if ($validar_autor_has_libro == 0) {
                $delete_autor = Autor::where('id', $id)->delete();
            } else {
                return response()->json(["message" => "No fue posible eliminar el Autor, tiene libros registrados.", "errors" => ["error_delete" => ["No fue posible eliminar el Autor, tiene libros registrados."]]], 422);
            }
            DB::commit();


            return 'ok';
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }
}
