<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendWhatsAppReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send WhatsApp reminders 24 hours before appointments';

    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        parent::__construct();
        $this->whatsappService = $whatsappService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for appointments to send WhatsApp reminders...');

        // Calculate the target time range (24 hours from now, with a 2-hour window)
        $now = Carbon::now();
        $targetStart = $now->copy()->addHours(23);
        $targetEnd = $now->copy()->addHours(25);

        // Find appointments that:
        // 1. Are scheduled between 23-25 hours from now
        // 2. Haven't received a WhatsApp reminder yet
        // 3. Have a user with a phone number
        $appointments = Appointment::with(['user', 'services'])
            ->whereNull('whatsapp_sent_at')
            ->whereHas('user', function ($query) {
                $query->whereNotNull('phone_number')
                    ->where('phone_number', '!=', '');
            })
            ->whereDate('date', '>=', $targetStart->toDateString())
            ->whereDate('date', '<=', $targetEnd->toDateString())
            ->get()
            ->filter(function ($appointment) use ($targetStart, $targetEnd) {
                // Combine date (string) and start_time to create a full datetime
                $appointmentDateTime = Carbon::parse($appointment->date . ' ' . $appointment->start_time);
                return $appointmentDateTime->between($targetStart, $targetEnd);
            });

        if ($appointments->isEmpty()) {
            $this->info('No appointments found that need reminders.');
            return 0;
        }

        $this->info("Found {$appointments->count()} appointment(s) to send reminders for.");

        $sent = 0;
        $failed = 0;

        foreach ($appointments as $appointment) {
            try {
                $message = $this->buildMessage($appointment);

                $result = $this->whatsappService->sendMessage($appointment->user->phone_number, $message);

                if ($result['success']) {
                    // Mark as sent
                    $appointment->whatsapp_sent_at = Carbon::now();
                    $appointment->save();

                    $sent++;
                    $this->info("✓ Reminder sent to {$appointment->user->name} ({$appointment->user->phone_number})");
                } else {
                    $failed++;
                    $errorMessage = $result['error'] ?? 'Unknown error';

                    Log::error('Error sending WhatsApp reminder', [
                        'appointment_id' => $appointment->id,
                        'user_id' => $appointment->user->id,
                        'phone_number' => $appointment->user->phone_number,
                        'error' => $errorMessage,
                    ]);

                    $this->error("✗ Failed to send reminder to {$appointment->user->name} ({$appointment->user->phone_number})");
                    $this->error("  Error: {$errorMessage}");
                }
            } catch (\Exception $e) {
                $failed++;
                Log::error('Exception sending WhatsApp reminder', [
                    'appointment_id' => $appointment->id,
                    'user_id' => $appointment->user->id ?? null,
                    'phone_number' => $appointment->user->phone_number ?? null,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                $this->error("✗ Exception sending reminder to {$appointment->user->name}: {$e->getMessage()}");
            }
        }

        $this->info("\nSummary: {$sent} sent, {$failed} failed");

        return 0;
    }

    /**
     * Build the WhatsApp message for an appointment
     *
     * @param Appointment $appointment
     * @return string
     */
    protected function buildMessage(Appointment $appointment): string
    {
        // Parse date (string) and start_time to create a full datetime
        $date = Carbon::parse($appointment->date . ' ' . $appointment->start_time);
        $formattedDate = $date->format('d/m/Y');
        $formattedTime = $date->format('H:i');

        $services = $appointment->services->pluck('name')->join(', ');

        $message = "🔔 Promemoria Appuntamento\n\n";
        $message .= "Ciao {$appointment->user->name},\n\n";

        // Determine if it's tomorrow or today
        $today = Carbon::today();
        $appointmentDate = $date->copy()->startOfDay();
        $isTomorrow = $appointmentDate->equalTo($today->copy()->addDay());

        if ($isTomorrow) {
            $message .= "Ti ricordiamo che hai un appuntamento domani:\n\n";
        } else {
            $message .= "Ti ricordiamo che hai un appuntamento:\n\n";
        }

        $message .= "📅 Data: {$formattedDate}\n";
        $message .= "🕐 Orario: {$formattedTime}\n";

        if ($services) {
            $message .= "💼 Servizi: {$services}\n";
        }

        if ($appointment->notes) {
            $message .= "\n📝 Note: {$appointment->notes}\n";
        }

        $message .= "\nTi aspettiamo!";

        return $message;
    }
}
