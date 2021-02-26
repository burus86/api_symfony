<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Trait ApiBaseControllerTrait
 * @package App\Controller\Api
 */
trait ApiBaseControllerTrait
{
    /**
     * @param array $data
     * @param string $status
     * @param bool|null $success
     * @return JsonResponse
     */
    public function getApiJsonResponse(array $data, string $status, $success = true): JsonResponse
    {
        $result = array('success' => $success);
        if (is_array($data) && count($data) > 0) {
            $result = array_merge($result, $data);
        }

        return $this->json($result, $status);
    }

    /**
     * @param array $data
     * @param string|null $status
     * @return JsonResponse
     */
    public function getApiSuccessJsonResponse(array $data, string $status = Response::HTTP_OK): JsonResponse
    {
        return $this->getApiJsonResponse($data, $status, $success = true);
    }

    /**
     * @param array $data
     * @param string|null $status
     * @return JsonResponse
     */
    public function getApiErrorJsonResponse(array $data, string $status = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return $this->getApiJsonResponse($data, $status, $success = false);
    }
}
