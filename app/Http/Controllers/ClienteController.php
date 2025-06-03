<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documentos;
use App\Models\Estatus;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    public function Index () {

        $usuario_id = Auth::id();

        $documentos = Documentos::from('documentos as t1')
        ->leftJoin('estatus as t2', 't1.status', '=', 't2.id')        
        ->select(
            't1.id',
            't1.rfc',
            't1.rfc_file',
            't1.razon_social',            
            't1.nombre_comercial',
            't1.representante_legal',
            't1.antiguedad',
            't1.afluencia',
            't1.numero_colaboradores',
            't1.pdf_colaboradores',
            't1.pdf_cif',
            't1.status',
            't2.nombre',
        )
        ->where('usuario_fk', $usuario_id)
        ->get();

        foreach ($documentos as $item) {
            $item->pdf_colaboradores  ? $item->pdf_colaboradores = Storage::url($item->pdf_colaboradores) : '';
            $item->razon_social  ? $item->razon_social = Storage::url($item->razon_social) : '';
            $item->pdf_cif  ? $item->pdf_cif = Storage::url($item->pdf_cif) : '';            
            $item->rfc_file_url  ? $item->rfc_file_url = Storage::url($item->rfc_file) : '';
        }        

        $estatus = Estatus::all();
        $estatus_inicial = Estatus::where('nombre', 'LIKE', '%Pendiente%')->first();

        return Inertia::render('Clientes', [
            'Documentos' => $documentos,
            'Estatus' => $estatus,
            'EstatusInicial' => $estatus_inicial,
        ]);
    }

    public function Store (Request $request) {

        $usuario_id = Auth::id();
        
        $request->validate([
            'rfc' => 'required|size:12',            
            'razon_social' => 'required',
            'nombre_comercial' => 'required',
            'representante_legal' => 'required',
            'antiguedad' => 'required',
            'numero_colaboradores' => 'required',
            'pdf_colaboradores' => 'required',
            'pdf_cif' => 'required',
        ],[
            'rfc.required' => 'El campo rfc es obligatorio.',
            'rfc.size' => 'El RFC debe tener exactamente 12 caracteres.',
            'razon_social.required' => 'El campo razon social es obligatorio.',
            'nombre_comercial.required' => 'El campo nombre comercial es obligatorio.',
            'representante_legal.required' => 'El campo representante legal es obligatorio.',
            'antiguedad.required' => 'El campo antiguedad es obligatorio.',
            'numero_colaboradores.required' => 'El campo numero de colaboradores es obligatorio.',
            'pdf_colaboradores.required' => 'El campo pdf colaboradores es obligatorio.',
            'pdf_cif.required' => 'El campo pdf cif es obligatorio.',
        ]);

        try {

            Documentos::updateOrCreate(
                [
                    'id' => $request->id
                ],
                [
                    'rfc' => $request->rfc,
                    'rfc_file' => $request->rfc_file,
                    'razon_social' => $request->hasFile('razon_social')  ? $request->file('razon_social')->store('pdf') : $request->razon_social, 
                    'nombre_comercial' => $request->hasFile('nombre_comercial')  ? $request->file('nombre_comercial')->store('pdf') : $request->nombre_comercial,
                    'representante_legal' => $request->hasFile('representante_legal')  ? $request->file('representante_legal')->store('pdf') : $request->representante_legal,
                    'antiguedad' => $request->hasFile('antiguedad')  ? $request->file('antiguedad')->store('pdf') : $request->antiguedad, 
                    'afluencia' => $request->hasFile('afluencia')  ? $request->file('afluencia')->store('pdf') : $request->afluencia,
                    'numero_colaboradores' => $request->hasFile('numero_colaboradores')  ? $request->file('numero_colaboradores')->store('pdf') : $request->numero_colaboradores,
                    'pdf_colaboradores' => $request->hasFile('pdf_colaboradores')  ? $request->file('pdf_colaboradores')->store('pdf') : $request->pdf_colaboradores,
                    'pdf_cif' => $request->hasFile('pdf_cif')  ? $request->file('pdf_cif')->store('pdf') : $request->pdf_cif,
                    'status' => $request->status,
                    'usuario_fk' => $usuario_id,
                ]
            );   
            return response()->json(['msg' => 'Solicitud enviada.'], 200);
        } catch (\Throwable $th) {
            return response()->json(['msg' => 'Ups algo salio mal.'], 422);
        }
    }

    public function Edit ($id) {
        try {
            
            $data = Documentos::find($id);
            
            $data->rfc_file_url =  Storage::url($data->rfc_file);
            $data->razon_social_url =  Storage::url($data->razon_social);
            $data->nombre_comercial_url =  Storage::url($data->nombre_comercial);
            $data->representante_legal_url =  Storage::url($data->representante_legal);
            $data->antiguedad_url =  Storage::url($data->antiguedad);
            $data->afluencia_url =  Storage::url($data->afluencia);
            $data->numero_colaboradores_url =  Storage::url($data->numero_colaboradores);
            $data->pdf_colaboradores_url =  Storage::url($data->pdf_colaboradores);
            $data->pdf_cif_url =  Storage::url($data->pdf_cif);

            return $data;

        } catch (\Throwable $th) {
            return response()->json(['msg' => 'Ups algo salio mal.'], 422);
        }
    }

    public function Delete ($id) {
        try{
            
            Documentos::destroy($id);
            
            return response()->json(['msg' => 'Eliminado con exito']);
        } catch (\Throwable $th) {
            return response()->json(['msg' => 'Ups algo salio mal.']);
        }
    }
}
