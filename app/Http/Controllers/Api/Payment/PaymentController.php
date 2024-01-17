<?php

namespace App\Http\Controllers\Api\Payment;

use App\DataTransferObjects\PaymentTransferDTO;
use App\Exceptions\BalanceShortageException;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Services\BasePayment;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function transfer(PaymentRequest $paymentRequest): JsonResponse
    {
        $validated = $paymentRequest->validated();

        $paymentDTO = new PaymentTransferDTO(
            source_card_number: $validated['source_card_number'],
            destination_card_number: $validated['destination_card_number'],
            amount: $validated['amount']
        );

        try {
            $paymentMethod = resolve(BasePayment::class, ['method' => $validated['payment_method']]);
            $paymentMethod->transfer($paymentDTO);
        } catch (BalanceShortageException $balanceShortageException) {
            return $this->handleException($balanceShortageException, JsonResponse::HTTP_FORBIDDEN);
        } catch (\Exception $exception) {
            return $this->handleException($exception, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return new JsonResponse(['message' => trans('messages.payment_success')], JsonResponse::HTTP_OK);
    }

    public function lastTransactions(TransactionService $transactionService): JsonResponse
    {
        $result = $transactionService->getTopUsersTransaction(3, 10, 10);

        return new JsonResponse([
            'message' => null,
            'data' => $result],JsonResponse::HTTP_OK
        );
    }

    private function handleException(\Exception $exception, int $statusCode): JsonResponse
    {
        return new JsonResponse(
            [
                'message' => trans('messages.payment_failed'),
                'status' => $statusCode,
                'errors' => ['message' => $exception->getMessage()]
            ],
            $statusCode
        );
    }
}
