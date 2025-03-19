<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Backend API",
 *     version="1.0.0",
 *     description="API que permite consultar información sobre Star Wars",
 *     @OA\Contact(
 *         email="adrian_tula@hotmail.com"
 *     )
 * )
 * @OA\Server(
 *     url="http://127.0.0.1:8000",
 *     description="Servidor de desarrollo"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="passport",
 *     type="oauth2",
 *     scheme="bearer",
 *     description="Laravel Passport OAuth2 Authentication",
 *     @OA\Flow(
 *         flow="password",
 *         tokenUrl="http://127.0.0.1:8000/api/login",
 *         scopes={}
 *     )
 * )
 */

abstract class Controller
{
    //
}
