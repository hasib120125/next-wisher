<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionExpireMail;
use App\Models\TalentEarning;
use App\Models\User;
use App\Models\UserNotice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class NoficeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notice:watch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */

    protected function sendSubscriptionExpireNotice() {
        $subscriptions = TalentEarning::where('type', 'mylife')
                                    ->with(['talent', 'user'])
                                    ->where('expire_date', '<', now()->today())
                                    ->where('expire_noticed', false)
                                    ->get();
        foreach ($subscriptions as $key => $subscribe) {
            $talent = User::find($subscribe->talent_id);
            UserNotice::create([
                'talent_id' => $subscribe->talent_id,
                'user_id' => $subscribe->user_id,
                'title' => 'Subscription expired',
                'details' => 'Your subscription expired',
                // 'is_seen' => '',
                // 'settings' => '',
            ]);
            try {
                DB::beginTransaction();
                if ($subscribe->user && $subscribe->talent) {
                    Mail::to($subscribe->user)->send(new SubscriptionExpireMail($subscribe->talent, $subscribe->user));
                    $subscribe->update([
                        'expire_noticed' => true,
                    ]);
                }
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
            }
        }
    }
    
    public function handle()
    {
        $this->sendSubscriptionExpireNotice();
        // return Command::SUCCESS;
    }
}
