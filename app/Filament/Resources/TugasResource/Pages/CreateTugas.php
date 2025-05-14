<?php

namespace App\Filament\Resources\TugasResource\Pages;

use App\Filament\Resources\TugasResource;
use App\Models\User;
use App\Helpers\FonteWa;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateTugas extends CreateRecord
{
    protected static string $resource = TugasResource::class;

    // Fungsi ini akan dipanggil setelah record tugas berhasil dibuat
    protected function afterCreate(): void
    {
        // Ambil user yang menerima tugas
        $user = User::find($this->record->id_user);

        // Pastikan user ditemukan
        if ($user && $user->no_wa) {
            // Kirim pesan WhatsApp kepada pengguna
            $no_wa = $user->no_wa;  // Ambil nomor WhatsApp pengguna
            $message = "📌 Halo {$user->name}, Anda memiliki tugas baru: {$this->record->nama_tugas}. Deadline: {$this->record->deadline}. Segera kerjakan tugasmu dan konfirmasi tugasmu di web !";

            // Pastikan format no_wa sesuai dengan format yang diterima API (misal: 628xxxx)
            $is_sent = FonteWa::send($no_wa, $message);

            if ($is_sent) {
                // Tampilkan notifikasi sukses jika pesan berhasil terkirim
                Notification::make()
                    ->title('Pesan WhatsApp Terkirim')
                    ->body("Pesan WhatsApp telah berhasil dikirim ke {$user->name}.")
                    ->success()
                    ->send();
            } else {
                // Tampilkan notifikasi gagal jika pesan gagal dikirim
                Notification::make()
                    ->title('Error Pengiriman')
                    ->body('Gagal mengirim pesan WhatsApp.')
                    ->danger()
                    ->send();
            }
        } else {
            // Tampilkan notifikasi jika user tidak memiliki nomor WA
            Notification::make()
                ->title('Error Pengiriman')
                ->body('User tidak ditemukan atau tidak memiliki nomor WhatsApp.')
                ->warning()
                ->send();
        }
    }
}