<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UnblockExpiredUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:unblock-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unblock users that have been blocked for more than 30 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredDate = Carbon::now()->subDays(30);
        
        $unblockedCount = User::where('blocked', true)
            ->whereNotNull('blocked_at')
            ->where('blocked_at', '<=', $expiredDate)
            ->update([
                'blocked' => false,
                'blocked_at' => null
            ]);

        $this->info("Unblocked {$unblockedCount} user(s).");
        
        return Command::SUCCESS;
    }
}
