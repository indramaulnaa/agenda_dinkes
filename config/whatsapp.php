<?php

return [
    'groups' => [
        // Format: 'Nama Persis di Pilihan Form' => env('VARIABLE_DI_ENV'),

        'Subbagian Umum & Kepegawaian' => env('WA_GROUP_UMUM_KEPEGAWAIAN'),

        'Subbagian Program & Keuangan' => env('WA_GROUP_PROGRAM_KEUANGAN'),
        
        'Puskesmas'                    => env('WA_GROUP_PUSKESMAS'),
        
        'Seluruh Pegawai Dinas Kesehatan' => env('WA_GROUP_ALL'),
        
        'Bidang Kesehatan Masyarakat (Kesmas)' => env('WA_GROUP_KESMAS'),
        
        'Bidang Pencegahan & Pengendalian Penyakit (P2P)' => env('WA_GROUP_P2P'),
        
        'Bidang Pelayanan Kesehatan (Yankes)' => env('WA_GROUP_YANKES'),
        
        'Kepala Dinas & Pejabat Struktural' => env('WA_GROUP_STRUKTURAL'),
    ],
];