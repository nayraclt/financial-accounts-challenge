<?php
namespace App\Http\Services;

use App\Exceptions\UnauthorizedUserException;

interface TransferServiceInterface
{
    /**
     * @throws UnauthorizedUserException
     */
    public function sendCash($dados) : array;
}
