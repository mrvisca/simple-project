<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
     * @OA\OpenApi(
     *         @OA\Info(
     *                  version="1.0",
     *                  title="Visca Corporation API",
     *                  description="Dokumentasi Open Api Visca Corporation",
     *                  @OA\Contact(
     *                      email="bimasaktiputra95@gmail.com"
     *                  ),
     *         ),
     *         @OA\Server(
     *                  url="http://127.0.0.1:8000",
     *                  description="Server Local"
     *         )
     * )
     */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
