<?php
namespace App\Applications\Api\Http\Middleware;

use Illuminate\Routing\Middleware\ThrottleRequests as BaseThrottleRequests;

class ThrottleRequests extends BaseThrottleRequests
{
    protected function buildResponse($key, $maxAttempts)
    {
        $response = response([
            'code' => 429,
            'description' => trans('errors.too_many_attempts')
        ], 429);

        $retryAfter = $this->limiter->availableIn($key);

        return $this->addHeaders(
            $response, $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts, $retryAfter),
            $retryAfter
        );
    }
}
