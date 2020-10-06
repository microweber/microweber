<?php

namespace MicroweberPackages\App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;



/**
 * @OA\Info(
 *    title="Microweber ApplicationAPI",
 *    version="1.0.0",
 *    description="Drag & drop website builder.",
 *     @OA\Contact(
 *          email="support@microweber.org"
 *      ),
 *     @OA\License(
 *         name="MIT License",
 *         url="https://github.com/microweber/microweber/blob/master/LICENSE"
 *     )
 * )
 *
 */
/**
 *  @OA\Server(
 *      url="__API_URL__",
 *      description="L5 Swagger OpenApi Server"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
