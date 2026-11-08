<?php

namespace App\Http\Controllers;

use App\Models\JobOrder;
use App\Services\ReminderWebhookService;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function index()
    {
        $jobOrders = JobOrder::with(['customerVehicle.customer', 'customerVehicle.vehicle'])
            ->orderByDesc('service_at')
            ->orderByDesc('id')
            ->paginate(25);

        return view('reminders.index', compact('jobOrders'));
    }

    public function send(Request $request, ReminderWebhookService $webhook)
    {
        $validated = $request->validate([
            'job_order_ids' => ['required', 'array', 'min:1', 'max:100'],
            'job_order_ids.*' => ['required', 'integer', 'distinct', 'exists:job_orders,id'],
            'queue_type' => ['required', 'in:after_service,reminder,all'],
        ], [
            'job_order_ids.required' => 'Pilih minimal satu Work Order.',
            'job_order_ids.max' => 'Maksimal 100 Work Order dapat dikirim sekaligus.',
        ]);

        $jobOrders = JobOrder::with(['customerVehicle.customer', 'customerVehicle.vehicle'])
            ->whereIn('id', $validated['job_order_ids'])
            ->get();

        $types = match ($validated['queue_type']) {
            'after_service' => ['After Service'],
            'reminder' => ['Reminder'],
            'all' => ['All'],
        };

        $sent = 0;
        foreach ($types as $type) {
            if ($webhook->sendJobOrders($jobOrders, $type)) {
                $sent++;
            }
        }

        $failed = count($types) - $sent;
        $message = "{$sent} antrean berhasil dikirim ke webhook.";
        if ($failed > 0) {
            $message .= " {$failed} antrean gagal dikirim; periksa log aplikasi.";
        }

        return redirect()->route('reminders.index')
            ->with($sent > 0 ? 'success' : 'error', $message)
            ->with('reminder_queue_sent', true);
    }
}
