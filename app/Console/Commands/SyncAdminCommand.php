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
        // 1. Selaraskan akun Administrator utama
        User::where('username', 'admin')->update([
            'name' => 'Vicky Koroh, S.Pd., Gr. (Administrator)',
        ]);

        // 2. Pastikan Master Profil Sekolah memiliki Kepala Sekolah resmi jika belum terisi
        $sekolah = MasterSekolah::first();
        if ($sekolah) {
            if (empty($sekolah->kepala_sekolah)) {
                $sekolah->update([
                    'kepala_sekolah' => 'Apolos Kofan, S.Pd.,MM',
                    'nip_kepala_sekolah' => '196704062006041009',
                ]);
            }
        }

        $this->info('[SUKSES] Nama Admin CBT dan data Kepala Sekolah berhasil diselaraskan.');
        return 0;
    }
}
