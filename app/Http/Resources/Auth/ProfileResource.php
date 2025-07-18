<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'full_name'=> $this->full_name,
            'email'=> $this->email,
            'email_verified_at'=> $this->email_verified_at,
            'role'=> $this->role
        ];
    }
}
