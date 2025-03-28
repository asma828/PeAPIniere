<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="PéAPInière API",
 *     version="1.0",
 *     description="Documentation de l'API pour la gestion des Pépinière.",
 *     @OA\Contact(
 *         email="support@PéAPInière.com"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
