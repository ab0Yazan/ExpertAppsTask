<?php

namespace App\Http\Controllers;

use App\Actions\UserRegisterAction;
use App\Http\Requests\StoreRegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Response;

final class RegisterController extends Controller
{
    public function __invoke(StoreRegisterRequest $request, UserRegisterAction $action)
    {
        try {
            $user= $action
                ->execute($request->getRegisterUserDto(), $request->password);

            return $this->apiResponse()->created(UserResource::make($user));
        }
        catch (\InvalidArgumentException $exception){
            return $this->apiResponse()->error($exception->getMessage());
        }
        catch (\Exception $exception){
            return $this->apiResponse()->error("An unexpected error occurred", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
