<?php

namespace App\Services;

use App\Models\DatabaseBackup;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Storage;

class BackupService
{
    public function createBackup(): ?DatabaseBackup
    {
        $filename = 'backup_' . date('Y-m-d_His') . '.sql';
        $directory = storage_path('backups');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $path = $directory . '/' . $filename;

        $dbHost = config('database.connections.mysql.host');
        $dbPort = config('database.connections.mysql.port');
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');

        $command = sprintf(
            'mysqldump --host=%s --port=%s --user=%s %s %s > %s 2>&1',
            escapeshellarg($dbHost),
            escapeshellarg($dbPort),
            escapeshellarg($dbUser),
            $dbPass ? '--password=' . escapeshellarg($dbPass) : '',
            escapeshellarg($dbName),
            escapeshellarg($path)
        );

        exec($command, $output, $returnCode);

        $status = $returnCode === 0 ? 'success' : 'failed';
        $size = file_exists($path) ? filesize($path) : 0;

        $backup = DatabaseBackup::create([
            'filename' => $filename,
            'path' => $path,
            'size_bytes' => $size,
            'status' => $status,
        ]);

        ActivityLog::log('database_backup', "Database backup {$status}: {$filename}", $backup);

        return $backup;
    }
}
