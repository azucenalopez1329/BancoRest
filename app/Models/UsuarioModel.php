<?php namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table          = 'usuario';
    protected $primaryKey     = 'id';

    protected $returnType     = 'array';
    protected $allowedFields  = ['nombre', 'username', 'password', 'rol_id'];

    protected $useTimestamps  = 'true';
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';

    protected $validationRules = [
        'nombre'    => 'required|alpha_space|min_length[3]|max_length[65]',
        'username'  => 'required|alpha_space|min_length[3]|max_length[10]',
        'password'  => 'required|text|is_valid_usuario',
        'rol_id'    => 'required|integer|is_valid_usuario',
    ];

    protected $validationMessages = [
        'rol_id'              => [
            'is_valid_rol_id' => 'Estimado usuario, usted debe ingresar un rol',
            'is_allow_rol_id' => 'Estimado usuario, usted debe ingresar un rol'
        ]
    ];

    protected $skipValidation = false;
}