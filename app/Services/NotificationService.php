<?php

namespace App\Services;

use App\Models\Pembayaran;
use App\Models\PengajuanKost;
use App\Models\Pengiriman;
use App\Models\Penawaran;
use App\Models\Proyek;
use App\Models\User;
use App\Notifications\PembayaranApprovedNotification;
use App\Notifications\PembayaranSubmittedNotification;
use App\Notifications\PengajuanKostRevisionRequestedNotification;
use App\Notifications\PengajuanKostSubmittedNotification;
use App\Notifications\PengirimanCreatedNotification;
use App\Notifications\PenawaranAccNotification;
use App\Notifications\ProyekStatusChangedNotification;
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * Helper: ambil user berdasarkan role.
     *
     * @return Collection<int, User>
     */
    private function usersByRoles(array $roles): Collection
    {
        return User::query()
            ->whereIn('role', $roles)
            ->get();
    }

    /**
     * (1) Penawaran ACC -> semua role.
     */
    public function penawaranAcc(Penawaran $penawaran): void
    {
        /** @var \Illuminate\Support\Collection<int, \App\Models\User> $users */
        $users = $this->usersByRoles(['superadmin', 'admin_marketing', 'admin_purchasing', 'admin_keuangan']);
        foreach ($users as $user) {
            /** @var \App\Models\User $user */
            $user->notify(new PenawaranAccNotification($penawaran));
        }
    }

    /**
     * (2) Status proyek berubah -> PIC marketing + PIC purchasing.
     */
    public function proyekStatusChanged(Proyek $proyek, string $oldStatus, string $newStatus): void
    {
        $recipientIds = collect([$proyek->id_admin_marketing, $proyek->id_admin_purchasing])
            ->filter()
            ->unique()
            ->values();

        if ($recipientIds->isEmpty()) {
            return;
        }

        /** @var \Illuminate\Support\Collection<int, \App\Models\User> $users */
        $users = User::whereIn('id_user', $recipientIds)->get();
        foreach ($users as $user) {
            /** @var \App\Models\User $user */
            $user->notify(new ProyekStatusChangedNotification($proyek, $oldStatus, $newStatus));
        }
    }

    /**
     * (3) Pengajuan cost dibuat -> admin keuangan.
     */
    public function pengajuanKostSubmitted(PengajuanKost $pengajuan): void
    {
        /** @var \Illuminate\Support\Collection<int, \App\Models\User> $users */
        $users = $this->usersByRoles(['admin_keuangan']);
        foreach ($users as $user) {
            /** @var \App\Models\User $user */
            $user->notify(new PengajuanKostSubmittedNotification($pengajuan));
        }
    }

    /**
     * (4) Revisi cost -> user pembuat pengajuan.
     */
    public function pengajuanKostRevisionRequested(PengajuanKost $pengajuan): void
    {
        if (!$pengajuan->created_by) {
            return;
        }

        $user = User::find($pengajuan->created_by);
        if ($user) {
            $user->notify(new PengajuanKostRevisionRequestedNotification($pengajuan));
        }
    }

    /**
     * (5) Purchasing submit pembayaran -> admin keuangan.
     */
    public function pembayaranSubmitted(Pembayaran $pembayaran): void
    {
        /** @var \Illuminate\Support\Collection<int, \App\Models\User> $users */
        $users = $this->usersByRoles(['admin_keuangan']);
        foreach ($users as $user) {
            /** @var \App\Models\User $user */
            $user->notify(new PembayaranSubmittedNotification($pembayaran));
        }
    }

    /**
     * (5) Keuangan approve pembayaran -> purchasing PIC proyek.
     */
    public function pembayaranApproved(Pembayaran $pembayaran): void
    {
        $proyek = $pembayaran->penawaran?->proyek;
        $purchasingId = $proyek?->id_admin_purchasing;
        if (!$purchasingId) {
            return;
        }

        $user = User::find($purchasingId);
        if ($user) {
            $user->notify(new PembayaranApprovedNotification($pembayaran));
        }
    }

    /**
     * (6) Pengiriman dibuat -> marketing PIC proyek.
     */
    public function pengirimanCreated(Pengiriman $pengiriman): void
    {
        $proyek = $pengiriman->penawaran?->proyek;
        $marketingId = $proyek?->id_admin_marketing;
        if (!$marketingId) {
            return;
        }

        $user = User::find($marketingId);
        if ($user) {
            $user->notify(new PengirimanCreatedNotification($pengiriman));
        }
    }
}
