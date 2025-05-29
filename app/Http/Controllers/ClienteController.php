<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documentos;
use App\Models\Estatus;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ClienteController extends Controller
{
    public function Index () {

        $documentos = Documentos::from('documentos as t1')
        ->leftJoin('estatus as t2', 't1.status', '=', 't2.id')->get();

        foreach ($documentos as $item) {
            $item->pdf_colaboradores = Storage::url($item->pdf_colaboradores);
            $item->pdf_cif = Storage::url($item->pdf_cif);
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
        
        $request->validate([
            'rfc' => 'required',
            'razon_social' => 'required',
            'nombre_comercial' => 'required',
            'representante_legal' => 'required',
            'antiguedad' => 'required',
            'numero_colaboradores' => 'required',
            'pdf_colaboradores' => 'required',
            'pdf_cif' => 'required',
        ],[
            'rfc.required' => 'El campo rfc es obligatorio.',
            'razon_social.required' => 'El campo razon social es obligatorio.',
            'nombre_comercial.required' => 'El campo nombre comercial es obligatorio.',
            'representante_legal.required' => 'El campo representante legal es obligatorio.',
            'antiguedad.required' => 'El campo antiguedad es obligatorio.',
            'numero_colaboradores.required' => 'El campo numero de colaboradores es obligatorio.',
            'pdf_colaboradores.required' => 'El campo pdf colaboradores es obligatorio.',
            'pdf_cif.required' => 'El campo pdf cif es obligatorio.',
        ]);

        try {
            
            $pdf_colaboradores = '';
            if ($request->hasFile('pdf_colaboradores')) {
                $pdf_colaboradores = $request->file('pdf_colaboradores')->store('pdf');    
            } else {
                $pdf_colaboradores  = $request->pdf_colaboradores;
            }

            $pdf_cif = '';
            if ($request->hasFile('pdf_cif')) {
                $pdf_cif = $request->file('pdf_cif')->store('pdf');
            } else {
                $pdf_cif  = $request->pdf_cif;
            }
            
            Documentos::updateOrCreate(
                [
                    'id' => $request->id
                ],
                [
                    'rfc' => $request->rfc,
                    'razon_social' => $request->razon_social, 
                    'nombre_comercial' => $request->nombre_comercial,
                    'representante_legal' => $request->representante_legal,
                    'antiguedad' => $request->antiguedad, 
                    'afluencia' => $request->afluencia,
                    'numero_colaboradores' => $request->numero_colaboradores,
                    'pdf_colaboradores' => $pdf_colaboradores,
                    'pdf_cif' => $pdf_cif,
                    'status' => $request->status,
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
