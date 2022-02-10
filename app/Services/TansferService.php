<?php
namespace App\Http\Services;

class TansferService implements TransferServiceInterface
{
    public function sendCash($dados) : array
    {
        try
        {
            dd('teste');
        }
        catch (Exception $e)
        {
            throw new $e->getMessage();
        }
    }
}
