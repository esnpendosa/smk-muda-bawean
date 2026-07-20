<?php

namespace App\Services;

use App\Models\PpdbRegistration;
use Illuminate\Support\Facades\DB;

class PpdbService
{
    /**
     * Generate registration number with pessimistic locking.
     */
    public function generateRegistrationNumber(): string
    {
        return DB::transaction(function () {
            $today = now()->format('Ymd');
            $prefix = "PPDB-{$today}-";

            $last = PpdbRegistration::where('registration_number', 'like', $prefix . '%')
                ->lockForUpdate()
                ->orderByDesc('registration_number')
                ->first();

            $sequence = $last
                ? (int) substr($last->registration_number, -4) + 1
                : 1;

            return $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
        });
    }
}
