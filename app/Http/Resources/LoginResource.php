<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => $request->user(),
            'permissions' => $this->permissions ?? [],
            'roles' => $this->roles ?? [],
            'token' => $this->createToken('authToken')->plainTextToken
        ];

    }
}
