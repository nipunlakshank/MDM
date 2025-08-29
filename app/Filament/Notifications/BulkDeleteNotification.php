<?php

namespace App\Filament\Notifications;

use Filament\Notifications\Notification;

class BulkDeleteNotification
{
    public static function make(int $deletedCount, int $skippedCount)
    {
        if ($deletedCount > 0 && $skippedCount > 0) {
            Notification::make()
                ->title('Partial Delete')
                ->body("Deleted {$deletedCount} record(s). {$skippedCount} record(s) were skipped because you don't have permission.")
                ->warning()
                ->send();
        } elseif ($deletedCount > 0) {
            Notification::make()
                ->title('Deleted')
                ->body("Deleted {$deletedCount} record(s).")
                ->success()
                ->send();
        } elseif ($skippedCount > 0) {
            Notification::make()
                ->title('No Records Deleted')
                ->body("You don't have permission to delete any of the selected records.")
                ->warning()
                ->send();
        }
    }
}
