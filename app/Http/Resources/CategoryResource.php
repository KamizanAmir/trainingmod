<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            "id" => $this->id,
            "name" => $this->name,
            "t_name" => $this->t_name, //This First then go to controller for the model
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            "count" => $this->item->count()
        ];
    }
}
