<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BackupService;

class DatabaseBackup extends Command
{
    protected $signature = 'app:database-backup';
    protected $description = 'Create a database backup';

    public function handle(BackupService $backupService): int
    {
        $this->info('Creating database backup...');

        $backup = $backupService->createBackup();

        if ($backup && $backup->status === 'success') {
            $this->info("Backup created: {$backup->filename} ({$backup->size_bytes} bytes)");
        } else {
            $this->error('Backup failed.');
        }

        return Command::SUCCESS;
    }
}
