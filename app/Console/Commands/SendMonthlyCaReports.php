<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Mail\CaMonthlyReportMail;
use App\Services\ReportService;
use App\Services\WhatsappService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMonthlyCaReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:share-with-ca';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically share monthly profit reports with Chartered Accountants on the 1st of every month.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ReportService $reportService, WhatsappService $whatsappService)
    {
        $users = User::where('ca_sharing_enabled', true)
                    ->whereNotNull('ca_email')
                    ->get();

        $this->info('Found ' . $users->count() . ' users with CA sharing enabled.');

        foreach ($users as $user) {
            $this->info("Processing report for: {$user->name} (Business: {$user->business_name})");

            try {
                // 1. Generate Report Data
                $data = $reportService->getProfitData($user);
                
                // 2. Generate PDF
                $pdf = Pdf::loadView('profit.pdf', $data)
                          ->setPaper('A4', 'portrait')
                          ->setOptions(['dpi' => 150, 'defaultFont' => 'DejaVu Sans']);
                
                $pdfData = $pdf->output();
                $monthName = now()->subMonth()->format('F Y');
                $filename = 'Profit_Report_' . str_replace(' ', '_', $monthName) . '.pdf';

                // 3. Send Email
                Mail::to($user->ca_email)->send(new CaMonthlyReportMail(
                    $user->ca_name,
                    $user->business_name ?? 'StockProNex',
                    $monthName,
                    $pdfData
                ));
                $this->info(" - Email sent to: {$user->ca_email}");

                // 4. Send WhatsApp (if number provided)
                if ($user->ca_whatsapp) {
                    $caption = "Hello CA {$user->ca_name}, please find the monthly profit report for {$user->business_name} for {$monthName}.";
                    $wsResult = $whatsappService->sendDocument(
                        $user->ca_whatsapp,
                        $pdfData,
                        $filename,
                        $caption
                    );

                    if ($wsResult['success']) {
                        $this->info(" - WhatsApp sent to: {$user->ca_whatsapp}");
                    } else {
                        $this->error(" - WhatsApp failed: " . $wsResult['message']);
                    }
                }

            } catch (\Exception $e) {
                $this->error(" - Error processing user {$user->id}: " . $e->getMessage());
                Log::error("CA Report Command Error: " . $e->getMessage(), ['user_id' => $user->id]);
            }
        }

        $this->info('CA Report Sharing completed.');
        return 0;
    }
}
