<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MasterSekolah;
use App\Models\User;

class SyncAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cbt:sync-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi gelar dan nama Kepala Sekolah serta Admin CBT tanpa shell_exec';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        MasterSekolah::query()->update([
            'kepala_sekolah' => 'Vicky Koroh, S.Pd., Gr.',
        ]);

        User::where('username', 'admin')->update([
            'name' => 'Vicky Koroh, S.Pd., Gr. (Administrator)',
        ]);

        $this->info('[SUKSES] Nama Kepala Sekolah dan Admin berhasil diselaraskan ke: Vicky Koroh, S.Pd., Gr.');
        return 0;
    }
}
