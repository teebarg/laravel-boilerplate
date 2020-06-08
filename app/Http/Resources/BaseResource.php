<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Tymon\JWTAuth\Facades\JWTAuth;

class BaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        return [
            'status' => 'Success',
            'code' => 200,
            'message' => 'Request Action Successfull'
        ];
    }


    /**
     * Customize the outgoing response for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function withResponse($request, $response)
    {
        $token = JWTAuth::getToken();
        $response->header('Bearer', $token);
    }
}
