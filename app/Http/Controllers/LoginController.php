<?php

namespace App\Http\Controllers;

use App\Actions\LoginAction;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\LoginResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;

class LoginController extends Controller
{
    public function login(LoginRequest $request, LoginAction $action): JsonResponse
    {
        try {
            /** @array $data */
            $data = $action->execute($request->getLoginDto());
            return response()->json(LoginResource::make($data), Response::HTTP_OK);
        }
        catch (UnauthorizedException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
