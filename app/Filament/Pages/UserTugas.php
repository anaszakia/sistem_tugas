<?php

namespace App\Filament\Pages;

use App\Models\Tugas;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class UserTugas extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Tugas Saya';
    // protected static ?string $navigationGroup = 'Tugas Management';
    protected static string $view = 'filament.pages.user-tugas';

    public $tugas;

    public function mount(): void
    {
        $this->refreshTugas();
    }

    // Refresh the tasks list
    private function refreshTugas(): void
    {
        $this->tugas = Tugas::where('id_user', Auth::id())->get();
    }

    // Update the status of a task
    public function updateStatus(int $tugasId, string $newStatus): void
    {
        $task = Tugas::find($tugasId);
        
        if (!$task || $task->id_user !== Auth::id()) {
            Notification::make()
                ->title('Error')
                ->body('Tugas tidak ditemukan atau Anda tidak memiliki akses.')
                ->danger()
                ->send();
            return;
        }
        
        $task->status = $newStatus;
        $task->save();
        
        $this->refreshTugas();
        
        Notification::make()
            ->title('Status Tugas Diperbarui')
            ->body("Status tugas '{$task->nama_tugas}' berhasil diubah menjadi '{$newStatus}'.")
            ->success()
            ->send();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->can('page_UserTugas');
    }

}