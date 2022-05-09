<?php namespace App\Models;

use CodeIgniter\Model;

class TipoTransaccionModel extends Model
{
    protected $table          = 'tipo_transaccion';
    protected $primaryKey     = 'id';

    protected $returnType     = 'array';
    protected $allowedFields  = ['descripcion'];

    protected $useTimestamps  = 'true';
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';

    protected $validationRules = [
        'descripcion'    => 'required|alpha_space|min_length[3]|max_length[65]',
    ];

    protected $validationMessages = [
        'descripcion'     => [
            'valid_descripcion'  => 'Estimado usuario, debe ingresar una descripcion'
        ]
    ];

    protected $skipValidation = false;
}