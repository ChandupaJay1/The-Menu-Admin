<?php

namespace App\Console\Commands;

use App\Services\SubscriptionRenewalService;
use Illuminate\Console\Command;

class RenewSubscriptionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:renew';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically renew completed meal subscriptions for the next cycle with the same pattern';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Checking subscriptions for auto-renewal...");
        $renewed = SubscriptionRenewalService::processAutoRenewals();
        $this->info("Auto-renewal complete. " . count($renewed) . " subscription(s) renewed.");
        return Command::SUCCESS;
    }
}
