 <?php
 namespace App\Console;
 use Illuminate\Console\Scheduling\Schedule;
 use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
 use App\Services\OrderService;
 class Kernel extends ConsoleKernel
 {
    protected function schedule(Schedule $schedule): void
    {
        // Every minute auto-complete old active orders
        $schedule->call(function (OrderService $orders) {
            $orders->autoCompleteAged();
        })->everyMinute();
    }
 }