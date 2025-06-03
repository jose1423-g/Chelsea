<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documentos extends Model
{

    protected $table = 'documentos';

    protected $fillable = [
        'rfc',
        'razon_social',
        'nombre_comercial',
        'representante_legal',
        'antiguedad',
        'afluencia',
        'numero_colaboradores',
        'pdf_colaboradores',
        'pdf_cif',
        'status',
        'usuario_fk',
        'rfc_file',
    ];
}
