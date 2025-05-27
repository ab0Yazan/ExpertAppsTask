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
    public function __invoke(LoginRequest $request, LoginAction $action): JsonResponse
    {
        try {
            /** @array $data */
            $data = $action->execute($request->getLoginDto());
            return self::apiResponse()->ok(LoginResource::make($data));
        }
        catch (UnauthorizedException $e) {
            return self::apiResponse()->error($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        catch (\Exception $e) {
            return self::apiResponse()->error('An unexpected error occurred.',Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
