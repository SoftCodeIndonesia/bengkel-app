<?php

namespace App\Services;

use App\Models\JobOrder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReminderWebhookService
{
    public function sendJobOrder(
        JobOrder $jobOrder,
        ?string $customerName = null,
        ?string $whatsapp = null,
        string $logContext = 'Webhook reminder failed',
        string $type = 'Reminder'
    ): bool {
        return $this->sendPayloads([
            $this->makeJobOrderPayload($jobOrder, $customerName, $whatsapp),
        ], $type, $logContext);
    }

    public function sendJobOrders(
        iterable $jobOrders,
        string $type,
        string $logContext = 'Webhook reminder failed'
    ): bool {
        $payloads = [];
        foreach ($jobOrders as $jobOrder) {
            $payloads[] = $this->makeJobOrderPayload($jobOrder);
        }

        return $this->sendPayloads($payloads, $type, $logContext);
    }

    private function makeJobOrderPayload(
        JobOrder $jobOrder,
        ?string $customerName = null,
        ?string $whatsapp = null
    ): array {
        $jobOrder->loadMissing(['customerVehicle.customer', 'customerVehicle.vehicle']);

        $customerVehicle = $jobOrder->customerVehicle;
        $customer = $customerVehicle?->customer;
        $vehicle = $customerVehicle?->vehicle;

        return [
            'no_wo' => $jobOrder->unique_id,
            'nama_customer' => $customerName ?? $customer?->name ?? '',
            'whatsapp' => $whatsapp ?? $customer?->phone,
            'kendaraan' => $vehicle ? "{$vehicle->merk} {$vehicle->tipe}" : '',
            'nomor_polisi' => $vehicle?->no_pol ?? '',
            'tanggal_service' => $jobOrder->service_at?->format('d/m/Y')
                ?? $jobOrder->created_at?->format('d/m/Y'),
            'paket_service' => $jobOrder->notes ?? '',
        ];
    }

    private function sendPayloads(array $payloads, string $type, string $logContext): bool
    {
        $webhookUrl = config('services.reminder.url');
        $headerName = config('services.reminder.header_name');
        $headerValue = config('services.reminder.header_value');

        if (blank($webhookUrl)) {
            Log::error($logContext . ': webhook URL is not configured.');
            return false;
        }

        $webhookPayload = [
            'type' => $type,
            'payload' => $payloads,
        ];

        try {
            $request = Http::timeout(10);
            if (filled($headerName)) {
                $request = $request->withHeaders([$headerName => $headerValue]);
            }

            $response = $request->post($webhookUrl, $webhookPayload);
            if (!$response->successful()) {
                Log::error($logContext . ': webhook returned HTTP ' . $response->status(), [
                    'type' => $type,
                    'payload_count' => count($payloads),
                ]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error($logContext . ': ' . $e->getMessage(), [
                'type' => $type,
                'payload_count' => count($payloads),
            ]);
            return false;
        }
    }
}
