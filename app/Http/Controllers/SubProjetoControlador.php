<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categorias;
use App\Models\Foto;
use App\Models\SubProjetos;
use App\Models\Projeto;
use Illuminate\Support\Facades\Storage;
use Illuminate\Cache\RedisTaggedCache;
class SubProjetoControlador extends Controller
{

    private $objCategoria;
    private $objSubProjeto;
    private $objProjeto;
    private $objFoto;

    public function __construct()
    {
        $this->objSubProjeto = new SubProjetos();
        $this->objCategoria = new Categorias();
        $this->objProjeto = new Projeto();
        $this->objFoto = new Foto();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($projeto_id)
    {
        $subProjetos = SubProjetos::where('projeto_id', $projeto_id)->get();

        $categorias = $this->objCategoria->all();
        return view('layouts.subprojetos', compact('categorias','subProjetos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($projeto_id)
    {
        $subProjetos = $this->objSubProjeto->all();
        $categorias = Categorias::where('projeto_id', $projeto_id)->get();
        return view('layouts.formProjeto', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->objSubProjeto->titulo = $request->titulo;
        $this->objSubProjeto->projeto_id = $request->projeto_id;
        $this->objSubProjeto->categoria_id = $request->categoria_id;
        $this->objSubProjeto->descricao = $request->descricao;
        $this->objSubProjeto->integrantes = $request->integrantes;
        $this->objSubProjeto->save();

            /**$file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('storage/app/fotos', $filename);
            $this->objSubProjeto->foto = $filename;**/

        return redirect()->route('subprojetos', $request->projeto_id)->with(['message' => 'Subprojeto criado com sucesso!', 'msg-type' => 'success']);
    }

    public function fotos($projeto_id, $id) {
        $subProjeto = $this->objSubProjeto->find($id);
        return view('layouts.foto', compact('subProjeto'));
    }

    public function storeFoto(Request $request,$projeto_id, $id) {
          /*  $objFoto = new Foto();
            $arrayFotos = [];
            if ($request->hasFile('foto')) {
                foreach ($request->foto as $fotos) {
                    $filename = $fotos->getClientOriginalName();
                    $fotos->move('storage/app/fotos', $filename);
                    $arrayFotos[] = ['filename' => $filename, 'subprojeto_id' => $id];

                    $objFoto->foto = $filename;
                    $objFoto->subprojeto_id = $id;
                    if (Foto::where('subprojeto_id', $id)->count() < 4){
                        $objFoto->save();
                    }
                    else {
                        return redirect()->route('addFoto', [$projeto_id, $id])->with(['message' => 'O limite de imagens para cada projeto
                        é de quatro imagens!', 'msg-type' => 'danger']);
                    }
            }
        }*/
        $fotos = $request->file('foto');
        //dd($fotos);
        if(!empty($fotos)):

            foreach($fotos as $foto):
                $filename = $id . '_' . time() . '_' . $foto->getCLientOriginalName();
                $foto->move('storage/app/fotos', $filename);

                $objFoto = new Foto([
                    'foto' => $filename,
                    'subprojeto_id' => $id
                ]);
                if (Foto::where('subprojeto_id', $id)->count() < 4){
                    $objFoto->save();
                }
                else {
                    return redirect()->route('addFoto', [$projeto_id, $id])->with(['message' => 'O limite de imagens para cada projeto
                    é de quatro imagens!', 'msg-type' => 'danger']);
                }

            endforeach;
        endif;

        return redirect()->route('subprojetos', $projeto_id)->with(['message' => 'Imagem inserida com sucesso!', 'msg-type' => 'success']);
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
    public function edit($projeto_id, $id)
    {
        $subProjeto = $this->objSubProjeto->find($id);
        $categorias=$this->objCategoria->all();
        return view('layouts.formProjeto', compact('subProjeto', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $projeto_id, $id)
    {
        $subProjeto = $this->objSubProjeto->find($id);
        $subProjeto->titulo = $request->titulo;
        $subProjeto->projeto_id = $request->projeto_id;
        $subProjeto->categoria_id = $request->categoria_id;
        $subProjeto->descricao = $request->descricao;
        $subProjeto->integrantes = $request->integrantes;
        $subProjeto->save();

        return redirect()->route('subprojetos', $request->projeto_id)->with(['message' => 'Subprojeto atualizado com sucesso!', 'msg-type' => 'warning']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $this->objSubProjeto->destroy($id);
        return redirect()->back()->with(['message' => 'Subprojeto excluído com sucesso!', 'msg-type' => 'danger']);
    }
}