<?php

namespace App\Http\Controllers\Misc;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\DB;

class HealthCheckController extends Controller
{
    use HttpResponses;

    public function __invoke()
    {
        $dbStatus = 'healthy';
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $dbStatus = 'unhealthy';
        }

        return $this->success([
            'status' => $dbStatus,
            'message' => $dbStatus === 'healthy' ? 'All systems are operational' : 'Database connection failed'
        ], 'Health Check');
    }
}
