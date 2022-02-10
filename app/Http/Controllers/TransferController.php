<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedUserException;
use App\Http\Resources\Person as PersonResource;
use App\Http\Services\TransferServiceInterface;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransferController extends Controller
{

    public function store(Request $request)
    {
        try
        {
            $dados = $request->all();

            //Verificar saldo da carteira
            if($this->checkWallet($dados['payer'], $dados['value'])) {
                //Realizar a transação
                if(Transaction::create($dados)) {
                    //Atualizar as carteiras
                    $this->updateWallets($dados['payer'], $dados['payee'], $dados['value']);
                };

                return response()->json('Transação realizada com sucesso.', 200);
            };
            return response()->json('Saldo insuficiente...', 420);

        }
        catch (Exception $e)
        {
            return response()->json('Parece que algo deu errado...', 500);
        }
    }


    private function checkWallet($owner, $value)
    {
            $wallet = Wallet::where('owner', $owner)->first();

            if($wallet->value >= $value) {
                return true;
            }

            return false;

    }

    private function updateWallets($payer, $payee, $value)
    {
        $walletPayer = Wallet::where('owner', $payer)->first();
        $walletPayee = Wallet::where('owner', $payee)->first();

        $walletPayer->value = $walletPayer->value - $value;
        $walletPayee->value = $walletPayee->value + $value;

        $walletPayer->save();
        $walletPayee->save();
    }
}
