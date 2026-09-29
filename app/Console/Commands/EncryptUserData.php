<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;

class EncryptUserData extends Command
{
    protected $signature = 'users:encrypt';
    protected $description = 'Encrypt plain text user sensitive data (skips already encrypted)';

    public function handle()
    {
        $fields = ['first_name', 'last_name', 'email', 'phone'];
        $encrypted = 0;
        $skipped = 0;

        User::withTrashed()->chunk(100, function ($users) use ($fields, &$encrypted, &$skipped) {
            foreach ($users as $user) {
                $updates = [];

                foreach ($fields as $field) {
                    $raw = $user->getRawOriginal($field);

                    if (blank($raw)) {
                        continue;
                    }

                    if ($this->isAlreadyEncrypted($raw)) {
                        $skipped++;
                        continue;
                    }

                    $updates[$field] = encrypt($raw);
                    $encrypted++;
                }

                if (!empty($updates)) {
                    $user->updateQuietly($updates);
                }
            }
        });

        $this->info("Done. Encrypted: {$encrypted} fields. Skipped (already encrypted): {$skipped} fields.");
    }

    private function isAlreadyEncrypted(string $value): bool
    {
        try {
            Crypt::decryptString($value);
            return true;
        } catch (\Exception) {
            return false;
        }
    }
}