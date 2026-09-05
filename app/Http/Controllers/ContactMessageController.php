<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\StoreContactMessage;
use App\Http\Requests\StoreContactMessageRequest;
use Illuminate\Http\JsonResponse;

class ContactMessageController extends Controller
{
    /**
     * Store a message submitted from the landing page contact modal.
     */
    public function store(StoreContactMessageRequest $request, StoreContactMessage $storeContactMessage): JsonResponse
    {
        $storeContactMessage(
            $request->safe()->only(['name', 'email', 'message']),
            $request->ip(),
        );

        return response()->json([
            'message' => 'Köszönjük az üzenetet, hamarosan válaszolunk.',
        ], 201);
    }
}
