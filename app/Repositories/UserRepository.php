<?php

namespace App\Repositories;

use App\Models\User;

/**
 * Class UserRepository.
 */
class UserRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function getAll()
    {
       return User::paginate(5);
    }
    
}
