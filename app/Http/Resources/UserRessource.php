<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Http\Resources\RoleRessource;
use App\Http\Resources\RoleCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class UserRessource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'roles' => RoleRessource::collection($this->roles),
        ];
    }
    public function boot()
    {
        JsonResource::withoutWrapping();
    }
}
