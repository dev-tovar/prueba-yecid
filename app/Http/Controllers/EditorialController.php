<?php

namespace App\Http\Controllers;

use App\Models\Editorial;
use App\Models\Libro;
use Illuminate\Http\Request;
use DB;

class EditorialController extends Controller
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

        $items = Editorial::withCount('libros');
        if (isset($buscar)) {
            $items = $items->where(function ($q) use ($buscar) {
                $q->orWhere('nombre', 'LIKE', '%' . $buscar . '%')->orWhere('sede', 'LIKE', '%' . $buscar . '%');
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
                'form_editorial.nombre' => 'required|max:45',
                'form_editorial.sede' => 'required|max:45'
            ];

        $niceNames = array(
            'form_editorial.nombre' => 'NOMBRE',
            'form_editorial.sede' => 'SEDE'
        );

        $this->validate($request, $rules, [], $niceNames);

        DB::beginTransaction();
        try {

            $nombre = $request->form_editorial['nombre'];
            $sede = $request->form_editorial['sede'];

            $insert_editorial = new Editorial();
            $insert_editorial->nombre = $nombre;
            $insert_editorial->sede = $sede;
            $insert_editorial->save();

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
            'form_editorial.nombre' => 'required|max:45',
            'form_editorial.sede' => 'required|max:45'
        ];

    $niceNames = array(
        'form_editorial.nombre' => 'NOMBRE',
        'form_editorial.sede' => 'SEDE'
    );

    $this->validate($request, $rules, [], $niceNames);

    DB::beginTransaction();
    try {

        $nombre = $request->form_editorial['nombre'];
        $sede = $request->form_editorial['sede'];

        $insert_editorial = Editorial::where('id', $id)->first();
        if ($insert_editorial) {
            $insert_editorial->nombre = $nombre;
            $insert_editorial->sede = $sede;
            $insert_editorial->save();
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
            $validar_editorial_has_libro = Libro::where('id_editorial', $id)->count();
            if ($validar_editorial_has_libro == 0) {
                $delete_editorial = Editorial::where('id', $id)->delete();
            } else {
                return response()->json(["message" => "No fue posible eliminar la editorial, tiene libros registrados.", "errors" => ["error_delete" => ["No fue posible eliminar la editorial, tiene libros registrados."]]], 422);
            }
            DB::commit();


            return 'ok';
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }
}
