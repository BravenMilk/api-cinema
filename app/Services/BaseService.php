<?php

namespace App\Services;

class BaseService
{
    /**
     * Format response standar untuk dikirim ke Controller (API)
     */
    protected function response(bool $success, string $message, $data = null, int $code = 200)
    {
        return [
            'success' => $success,
            'message' => $message,
            'data'    => $data,
            'code'    => $code
        ];
    }

    /**
     * Helper authorization.
     * @param string|array $roles
     * @return bool
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function authorizeRole($roles)
    {
        $user = auth()->user();

        if (!$user || !$user->role) {
            abort(response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'data' => null,
                'code' => 401
            ], 401));
        }

        $userRole = strtolower($user->role->name);
        
        if (is_array($roles)) {
            $roles = array_map('strtolower', $roles);
            if (!in_array($userRole, $roles)) {
                abort(response()->json([
                    'success' => false,
                    'message' => 'Forbidden. Requires one of: ' . implode(', ', $roles),
                    'data' => null,
                    'code' => 403
                ], 403));
            }
        } else {
            if ($userRole !== strtolower($roles)) {
                abort(response()->json([
                    'success' => false,
                    'message' => 'Forbidden. Requires role: ' . $roles,
                    'data' => null,
                    'code' => 403
                ], 403));
            }
        }

        return true;
    }
}
