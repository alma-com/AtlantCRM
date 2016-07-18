<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Alma;
use HTML;
use Response;

abstract class Request extends FormRequest
{
    /**
     * Get the proper failed validation response for the request.
     *
     * @param  array  $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        if ($this->ajax() || $this->wantsJson()) {
            //return new JsonResponse($errors, 422);

            return Response::json([
                'errFields' => $errors,
                'description' => Alma::getDescription('warning', HTML::ul(array_flatten($errors))),
                'status' => 'warning',
                'message' => 'Предупреждение',
            ]);
        }

        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }
}
