<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use App\Models\HomeTalent;
use App\Models\OrderCode;
use App\Models\Settings;
use App\Models\TalentEarning;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use stdClass;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $settings;

    public function __construct()
    {
        $this->settings = Settings::first();
        // $data = file_get_contents('https://unpkg.com/visionscarto-world-atlas@0.0.4/world/50m.json');
        TalentEarning::where('type', 'wish')->where('expire_date', '>', now())->update([
            'is_expire' => false,
        ]);
        TalentEarning::where('type', 'wish')->where('expire_date', '<', now())->update([
            'is_expire' => true,
        ]);
        TalentEarning::where('type', 'mylife')->where('expire_date', '<', now())->update([
            'is_expire' => true,
        ]);
    }

    public function deleteUser($id) {
        $user = User::find($id);
        if ($id && $user) {
            Follower::where('user_id', $id)->delete();
            if ($user->role == 'talent') {
                HomeTalent::where('user_id', $id)->delete();
            }
        }
    }

    public function getOrderCode() {
        OrderCode::where('created_at', now()->subHour(24))->delete();
        $orderCode = OrderCode::create([]);
        $orderCode = OrderCode::orderBy('id', 'desc');
        $orderId = ($orderCode->count() == 0 ? 0 : $orderCode->first()->id) + 1;
        $zeros = strlen($orderId) < 7 ? 7 - strlen($orderId) : 0;
        $code = '';
        for($i = 1; $i <= $zeros; $i++) $code .= '0';
        $code .= $orderId;
        return $code;
    }
}
