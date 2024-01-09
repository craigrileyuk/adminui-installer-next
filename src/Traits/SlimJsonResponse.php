<?php

namespace AdminUI\AdminUIInstaller\Traits;

trait SlimJsonResponse
{
    public function sendSuccess(array $data = null)
    {
        return response()->json([
            'status' => 'success',
            'data'  => $data,
            'log'   => [],
        ]);
    }
    public function sendFailed(string $errorMessage)
    {
        return response()->json([
            'status' => 'failed',
            'error' => $errorMessage,
            'log'   => []
        ]);
    }
}
