<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * cPanel paylaşımlı hosting'te `php artisan` SSH erişim sınırlı olabilir.
 * Bu rota authenticated admin'e migration + cache rebuild yapmasını sağlar.
 *
 * URL: /admin/system-update-{SYSTEM_UPDATE_SECRET}
 *
 * SECRET .env'de tanımlı, hash'lenmemiş — sadece authenticated admin
 * tahmin edilemez bir URL'den erişir.
 */
class SystemController extends Controller
{
    public function migrate(): JsonResponse
    {
        Log::info('System update triggered by admin', ['user' => auth()->user()?->email]);

        $output = [];

        Artisan::call('migrate', ['--force' => true]);
        $output['migrate'] = Artisan::output();

        Artisan::call('db:seed', ['--class' => 'AdminSeeder', '--force' => true]);
        $output['seed_admins'] = Artisan::output();

        Artisan::call('config:cache');
        $output['config'] = Artisan::output();

        Artisan::call('view:cache');
        $output['view'] = Artisan::output();

        Artisan::call('route:cache');
        $output['route'] = Artisan::output();

        Cache::flush();
        $output['cache'] = 'flushed';

        return response()->json([
            'success' => true,
            'output' => $output,
            'timestamp' => now()->toDateTimeString(),
        ]);
    }
}
