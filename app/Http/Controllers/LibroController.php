<?php

namespace App\Http\Controllers;

use App\Models\AutoresHasLibro;
use App\Models\Libro;
use Illuminate\Http\Request;
use DB;
use Symfony\Component\Console\Input\Input;

class LibroController extends Controller
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

        $items = Libro::with('autores', 'editorial');
        if (isset($buscar)) {
            $items = $items->where('titulo', 'LIKE', '%' . $buscar . '%');
        }
        $items = $items->orderBy('created_at', 'DESC')->get();
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
                'form_libro.isbn' => 'required|numeric|unique:libros,isbn|digits_between:1,45',
                'form_libro.titulo' => 'required|max:45',
                'form_libro.editorial' => 'required',
                'form_libro.sinopsis' => 'required',
                'form_libro.autores' => 'required',
                'form_libro.numero_paginas' => 'required|numeric|digits_between:1,45',
            ];

        $niceNames = array(
            'form_libro.isbn' => 'ISBN',
            'form_libro.titulo' => 'TITULO',
            'form_libro.editorial' => 'EDITORIAL',
            'form_libro.sinopsis' => 'SINOPSIS',
            'form_libro.autores' => 'AUTORES',
            'form_libro.numero_paginas' => 'NUMERO DE PÁGINAS'
        );

        $this->validate($request, $rules, [], $niceNames);

        DB::beginTransaction();
        try {

            $isbn = $request->form_libro['isbn'];
            $titulo = $request->form_libro['titulo'];
            $editorial = $request->form_libro['editorial'];
            $sinopsis = $request->form_libro['sinopsis'];
            $autores = $request->form_libro['autores'];
            $numero_paginas = $request->form_libro['numero_paginas'];

            $insert_libro = new Libro();
            $insert_libro->isbn = $isbn;
            $insert_libro->id_editorial = $editorial;
            $insert_libro->titulo = $titulo;
            $insert_libro->sinopsis = $sinopsis;
            $insert_libro->n_paginas = $numero_paginas;
            $insert_libro->save();

            if (count($autores) > 0) {
                //SI EL SELECT DE AUTORES VIENE CON 1 O MAS AUTORES SELECCIONADOS
                foreach ($autores as $key => $autor) {
                    $insert_autores_has_libros = new AutoresHasLibro();
                    $insert_autores_has_libros->id_autor = $autor;
                    $insert_autores_has_libros->isbn = $insert_libro->isbn;
                    $insert_autores_has_libros->save();
                }
            }

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
                'form_libro.isbn' => 'required|numeric|unique:libros,isbn,' . $id . ',isbn|digits_between:1,45',
                'form_libro.titulo' => 'required|max:45',
                'form_libro.editorial' => 'required',
                'form_libro.sinopsis' => 'required',
                'form_libro.autores' => 'required',
                'form_libro.numero_paginas' => 'required|numeric|digits_between:1,45',
            ];

        $niceNames = array(
            'form_libro.isbn' => 'ISBN',
            'form_libro.titulo' => 'TITULO',
            'form_libro.editorial' => 'EDITORIAL',
            'form_libro.sinopsis' => 'SINOPSIS',
            'form_libro.autores' => 'AUTORES',
            'form_libro.numero_paginas' => 'NUMERO DE PÁGINAS'
        );

        $this->validate($request, $rules, [], $niceNames);

        DB::beginTransaction();
        try {

            // $isbn = $request->form_libro['isbn'];
            $titulo = $request->form_libro['titulo'];
            $editorial = $request->form_libro['editorial'];
            $sinopsis = $request->form_libro['sinopsis'];
            $autores = $request->form_libro['autores'];
            $numero_paginas = $request->form_libro['numero_paginas'];

            $insert_libro = Libro::where('isbn', $id)->first();
            // $insert_libro->isbn = $isbn;
            $insert_libro->id_editorial = $editorial;
            $insert_libro->titulo = $titulo;
            $insert_libro->sinopsis = $sinopsis;
            $insert_libro->n_paginas = $numero_paginas;
            $insert_libro->save();

            $delete_autores = AutoresHasLibro::where('isbn', $id)->delete();

            if (count($autores) > 0) {
                //SI EL SELECT DE AUTORES VIENE CON 1 O MAS AUTORES SELECCIONADOS
                foreach ($autores as $key => $autor) {
                    $insert_autores_has_libros = new AutoresHasLibro();
                    $insert_autores_has_libros->id_autor = $autor;
                    $insert_autores_has_libros->isbn = $id;
                    $insert_autores_has_libros->save();
                }
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
            $delete_autores = AutoresHasLibro::where('isbn', $id)->delete();
            $delete_libro = Libro::where('isbn', $id)->delete();
            DB::commit();


            return 'ok';
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }
}
