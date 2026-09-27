<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

/**
 * Admin-managed list of serviceable delivery pincodes.
 * This is the single source of truth for customer-facing delivery availability.
 */
class DeliveryPincode extends Model
{
    protected $fillable = [
        'pincode',
        'area_name',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Only enabled pincodes that customers are allowed to order to.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Normalise any user input into a 6 digit pincode string.
     */
    public static function normalize($pin): string
    {
        return preg_replace('/\D/', '', (string) $pin);
    }

    /**
     * Defensive check so the store keeps working even before the migration is run.
     */
    public static function isTableReady(): bool
    {
        try {
            return Schema::hasTable('delivery_pincodes');
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Has the admin configured any pincode at all (active or disabled)?
     */
    public static function isConfigured(): bool
    {
        if (!self::isTableReady()) {
            return false;
        }

        try {
            return self::query()->exists();
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Enabled pincode strings, ordered by admin sort order.
     */
    public static function activePincodes(): array
    {
        if (!self::isTableReady()) {
            return [];
        }

        try {
            return self::active()
                ->orderBy('sort_order', 'asc')
                ->orderBy('pincode', 'asc')
                ->pluck('pincode')
                ->map(fn ($pin) => self::normalize($pin))
                ->filter(fn ($pin) => strlen($pin) === 6)
                ->unique()
                ->values()
                ->all();
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Enabled hubs as a plain array payload for the customer-facing API.
     */
    public static function hubs(): array
    {
        if (!self::isTableReady()) {
            return [];
        }

        try {
            return self::active()
                ->orderBy('sort_order', 'asc')
                ->orderBy('pincode', 'asc')
                ->get()
                ->map(fn ($row) => [
                    'pincode' => self::normalize($row->pincode),
                    'area_name' => $row->area_name,
                    'areaName' => $row->area_name,
                    'is_active' => (bool) $row->is_active,
                ])
                ->filter(fn ($hub) => strlen($hub['pincode']) === 6)
                ->values()
                ->all();
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Look up a configured pincode row (active or disabled).
     */
    public static function findHub($pin): ?self
    {
        $clean = self::normalize($pin);

        if (strlen($clean) !== 6 || !self::isTableReady()) {
            return null;
        }

        try {
            return self::where('pincode', $clean)->first();
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Is this pincode currently serviceable?
     * When nothing has been configured yet we do not block deliveries (legacy behaviour).
     */
    public static function isServiceable($pin): bool
    {
        $clean = self::normalize($pin);

        if (strlen($clean) !== 6) {
            return false;
        }

        if (!self::isConfigured()) {
            return true;
        }

        return in_array($clean, self::activePincodes(), true);
    }
}
