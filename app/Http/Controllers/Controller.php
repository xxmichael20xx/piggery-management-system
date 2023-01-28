<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function statuses() {
        return [
            'healthy', 'unhealthy', 'on_treatment', 'quarantined', 'deceased'
        ];
    }

    public function newLog( $title, $details ) {
        $newLog = new Log;
        $newLog->title = $title;
        $newLog->details = $details;
        $newLog->save();
    }
}
