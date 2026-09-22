<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ChatMessage;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\MessageAttachment;
use App\Models\NotificationPreference;
use App\Models\Proyek;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChatSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan direktori dan dummy berkas lampiran tersedia di public storage
        try {
            if (!Storage::disk('public')->exists('chat_attachments')) {
                Storage::disk('public')->makeDirectory('chat_attachments');
            }
            if (!Storage::disk('public')->exists('chat_attachments/demo_matriks.pdf')) {
                Storage::disk('public')->put('chat_attachments/demo_matriks.pdf', "%PDF-1.4\n% Matriks Revisi Valuasi Benoa - PKSPL IPB\n%%EOF");
            }
            if (!Storage::disk('public')->exists('chat_attachments/dokumen_pengesahan.pdf')) {
                Storage::disk('public')->put('chat_attachments/dokumen_pengesahan.pdf', "%PDF-1.4\n% Dokumen Pengesahan Peneliti Benoa - PKSPL IPB\n%%EOF");
            }
        } catch (\Throwable $e) {
            // Abaikan jika storage disk belum terkonfigurasi
        }

        $roleAnalyst = Role::firstOrCreate(['nama_role' => Role::ANALYST], ['deskripsi' => 'Role analyst aplikasi.']);
        $rolePeneliti = Role::firstOrCreate(['nama_role' => Role::PENELITI], ['deskripsi' => 'Role peneliti aplikasi.']);
        $roleAdmin = Role::firstOrCreate(['nama_role' => Role::ADMIN], ['deskripsi' => 'Role administrator aplikasi.']);

        // =========================================================================
        // 1. Pastikan Akun Utama & Preferensi Notifikasi Tersedia
        // =========================================================================

        // A. Analyst
        $analyst = User::firstOrCreate(
            ['email' => 'analyst@gmail.com'],
            [
                'nama' => 'Analyst PKSPL',
                'id_role' => $roleAnalyst->id_role,
                'password' => Hash::make('password'),
                'status' => 'Aktif',
                'email_verified_at' => now(),
            ]
        );

        NotificationPreference::firstOrCreate(
            ['id_user' => $analyst->id_user],
            [
                'email_chat' => true,
                'email_revision' => true,
                'email_status_review' => true,
                'app_notification' => true,
            ]
        );

        // B. Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'nama' => 'Administrator PKSPL',
                'id_role' => $roleAdmin ? $roleAdmin->id_role : $roleAnalyst->id_role,
                'password' => Hash::make('password'),
                'status' => 'Aktif',
                'email_verified_at' => now(),
            ]
        );

        NotificationPreference::firstOrCreate(
            ['id_user' => $admin->id_user],
            [
                'email_chat' => true,
                'email_revision' => true,
                'email_status_review' => true,
                'app_notification' => true,
            ]
        );

        // C. Peneliti (Bima Saputra, Retno, Fauzi, Wayan)
        $researchersData = [
            [
                'email' => 'peneliti@gmail.com',
                'nama' => 'Bima Saputra',
            ],
            [
                'email' => 'demo.retno@pkspl.ipb.ac.id',
                'nama' => 'Dr. Ir. Retno Wulandari, M.Si.',
            ],
            [
                'email' => 'demo.fauzi@pkspl.ipb.ac.id',
                'nama' => 'Dr. Ahmad Fauzi, S.Kel., M.Sc.',
            ],
            [
                'email' => 'demo.wayan@pkspl.ipb.ac.id',
                'nama' => 'Prof. Dr. Wayan Sudarma, M.Env.',
            ],
        ];

        $researcherUsers = [];
        foreach ($researchersData as $r) {
            $user = User::firstOrCreate(
                ['email' => $r['email']],
                [
                    'nama' => $r['nama'],
                    'id_role' => $rolePeneliti->id_role,
                    'password' => Hash::make('password'),
                    'status' => 'Aktif',
                    'email_verified_at' => now(),
                ]
            );

            NotificationPreference::firstOrCreate(
                ['id_user' => $user->id_user],
                [
                    'email_chat' => true,
                    'email_revision' => true,
                    'email_status_review' => true,
                    'app_notification' => true,
                ]
            );

            $researcherUsers[] = $user;
        }

        $bima = $researcherUsers[0];
        $retno = $researcherUsers[1];
        $fauzi = $researcherUsers[2];
        $sampleProyek = Proyek::first();

        // =========================================================================
        // 2. Thread Percakapan Role Analyst
        // =========================================================================

        // Thread 1: Analyst <-> Dr. Retno Wulandari (Percakapan aktif, ada unread + file)
        $convRetno = Conversation::firstOrCreate([
            'type' => 'direct',
            'title' => 'Diskusi Telaah: ' . $retno->nama,
            'id_proyek' => $sampleProyek?->id_proyek,
            'created_by' => $analyst->id_user,
        ]);

        ConversationParticipant::firstOrCreate([
            'id_conversation' => $convRetno->id_conversation,
            'id_user' => $analyst->id_user,
        ], [
            'last_read_at' => now()->subMinutes(10),
            'joined_at' => now()->subDays(2),
        ]);

        ConversationParticipant::firstOrCreate([
            'id_conversation' => $convRetno->id_conversation,
            'id_user' => $retno->id_user,
        ], [
            'last_read_at' => now(),
            'joined_at' => now()->subDays(2),
        ]);

        ChatMessage::firstOrCreate([
            'id_conversation' => $convRetno->id_conversation,
            'id_sender' => $retno->id_user,
            'message' => 'Selamat pagi Tim Analyst PKSPL, kami telah memperbarui data perhitungan TEV untuk zona mangrove inti Teluk Benoa.',
        ], [
            'id_proyek' => $sampleProyek?->id_proyek,
            'message_type' => 'text',
            'created_at' => now()->subHours(2),
            'updated_at' => now()->subHours(2),
        ]);

        ChatMessage::firstOrCreate([
            'id_conversation' => $convRetno->id_conversation,
            'id_sender' => $analyst->id_user,
            'message' => 'Terima kasih Bu Retno. Kami sedang memverifikasi parameter koefisien valuasi jasa ekosistem penahan abrasi pada tabel telaah.',
        ], [
            'id_proyek' => $sampleProyek?->id_proyek,
            'message_type' => 'text',
            'created_at' => now()->subHour(),
            'updated_at' => now()->subHour(),
        ]);

        $msgAttachment = ChatMessage::firstOrCreate([
            'id_conversation' => $convRetno->id_conversation,
            'id_sender' => $retno->id_user,
            'message' => 'Baik, berkas telaah revisi dan matriks justifikasi telah kami persiapkan. Mohon dicek kembali apakah format matriks sudah sesuai dengan standar PKSPL.',
        ], [
            'id_proyek' => $sampleProyek?->id_proyek,
            'message_type' => 'file',
            'created_at' => now()->subMinutes(5),
            'updated_at' => now()->subMinutes(5),
        ]);

        MessageAttachment::firstOrCreate([
            'id_message' => $msgAttachment->id_message,
            'file_name' => 'Matriks_Revisi_Valuasi_Benoa.pdf',
        ], [
            'file_path' => 'chat_attachments/demo_matriks.pdf',
            'file_type' => 'pdf',
            'file_size' => 2450000,
            'mime_type' => 'application/pdf',
        ]);

        // Thread 2: Analyst <-> Dr. Ahmad Fauzi (Padang Lamun Teluk Banten)
        $convFauzi = Conversation::firstOrCreate([
            'type' => 'direct',
            'title' => 'Diskusi Telaah: ' . $fauzi->nama,
            'id_proyek' => $sampleProyek?->id_proyek,
            'created_by' => $analyst->id_user,
        ]);

        ConversationParticipant::firstOrCreate([
            'id_conversation' => $convFauzi->id_conversation,
            'id_user' => $analyst->id_user,
        ], [
            'last_read_at' => now(),
            'joined_at' => now()->subDays(5),
        ]);

        ConversationParticipant::firstOrCreate([
            'id_conversation' => $convFauzi->id_conversation,
            'id_user' => $fauzi->id_user,
        ], [
            'last_read_at' => now(),
            'joined_at' => now()->subDays(5),
        ]);

        ChatMessage::firstOrCreate([
            'id_conversation' => $convFauzi->id_conversation,
            'id_sender' => $fauzi->id_user,
            'message' => 'Halo rekan Analyst, untuk dataset tutupan lamun Teluk Banten tahun 2024 sudah kami sesuaikan format shapefile ESRI-nya.',
        ], [
            'id_proyek' => $sampleProyek?->id_proyek,
            'message_type' => 'text',
            'created_at' => now()->subDays(1),
            'updated_at' => now()->subDays(1),
        ]);

        ChatMessage::firstOrCreate([
            'id_conversation' => $convFauzi->id_conversation,
            'id_sender' => $analyst->id_user,
            'message' => 'Sudah kami periksa Pak Fauzi, koordinat proyeksi EPSG 4326 sudah valid dan layer poligon tidak memiliki overlap.',
        ], [
            'id_proyek' => $sampleProyek?->id_proyek,
            'message_type' => 'text',
            'created_at' => now()->subHours(12),
            'updated_at' => now()->subHours(12),
        ]);

        // =========================================================================
        // 3. Thread Percakapan Role Admin
        // =========================================================================

        // Thread 3: Admin <-> Analyst PKSPL (Koordinasi telaah umum)
        $convAdminAnalyst = Conversation::firstOrCreate([
            'type' => 'direct',
            'title' => 'Koordinasi Evaluasi: ' . $analyst->nama,
            'id_proyek' => $sampleProyek?->id_proyek,
            'created_by' => $admin->id_user,
        ]);

        ConversationParticipant::firstOrCreate([
            'id_conversation' => $convAdminAnalyst->id_conversation,
            'id_user' => $admin->id_user,
        ], [
            'last_read_at' => now()->subMinutes(30),
            'joined_at' => now()->subDays(4),
        ]);

        ConversationParticipant::firstOrCreate([
            'id_conversation' => $convAdminAnalyst->id_conversation,
            'id_user' => $analyst->id_user,
        ], [
            'last_read_at' => now(),
            'joined_at' => now()->subDays(4),
        ]);

        ChatMessage::firstOrCreate([
            'id_conversation' => $convAdminAnalyst->id_conversation,
            'id_sender' => $admin->id_user,
            'message' => 'Selamat siang Tim Analyst, bagaimana status validasi data valuasi ekonomi pesisir untuk proyek berjalan?',
        ], [
            'id_proyek' => $sampleProyek?->id_proyek,
            'message_type' => 'text',
            'created_at' => now()->subHours(4),
            'updated_at' => now()->subHours(4),
        ]);

        ChatMessage::firstOrCreate([
            'id_conversation' => $convAdminAnalyst->id_conversation,
            'id_sender' => $analyst->id_user,
            'message' => 'Progres verifikasi berjalan sesuai jadwal Pak Admin. Hasil perhitungan telaah zona Benoa sedang dalam tahap finalisasi.',
        ], [
            'id_proyek' => $sampleProyek?->id_proyek,
            'message_type' => 'text',
            'created_at' => now()->subHours(1),
            'updated_at' => now()->subHours(1),
        ]);

        // Thread 4: Admin <-> Dr. Retno Wulandari (Ada pesan unread dan lampiran untuk Admin)
        $convAdminRetno = Conversation::firstOrCreate([
            'type' => 'direct',
            'title' => 'Koordinasi Proyek: ' . $retno->nama,
            'id_proyek' => $sampleProyek?->id_proyek,
            'created_by' => $admin->id_user,
        ]);

        ConversationParticipant::firstOrCreate([
            'id_conversation' => $convAdminRetno->id_conversation,
            'id_user' => $admin->id_user,
        ], [
            'last_read_at' => now()->subHours(2),
            'joined_at' => now()->subDays(3),
        ]);

        ConversationParticipant::firstOrCreate([
            'id_conversation' => $convAdminRetno->id_conversation,
            'id_user' => $retno->id_user,
        ], [
            'last_read_at' => now(),
            'joined_at' => now()->subDays(3),
        ]);

        ChatMessage::firstOrCreate([
            'id_conversation' => $convAdminRetno->id_conversation,
            'id_sender' => $admin->id_user,
            'message' => 'Yth. Bu Dr. Retno, mohon kelengkapan dokumen pengesahan tim peneliti untuk verifikasi administratif.',
        ], [
            'id_proyek' => $sampleProyek?->id_proyek,
            'message_type' => 'text',
            'created_at' => now()->subHours(3),
            'updated_at' => now()->subHours(3),
        ]);

        $adminRetnoMsg = ChatMessage::firstOrCreate([
            'id_conversation' => $convAdminRetno->id_conversation,
            'id_sender' => $retno->id_user,
            'message' => 'Selamat siang Pak Admin, berikut berkas pengesahan tim peneliti dan ringkasan eksekutif telah kami lampirkan.',
        ], [
            'id_proyek' => $sampleProyek?->id_proyek,
            'message_type' => 'file',
            'created_at' => now()->subMinutes(25),
            'updated_at' => now()->subMinutes(25),
        ]);

        MessageAttachment::firstOrCreate([
            'id_message' => $adminRetnoMsg->id_message,
            'file_name' => 'Dokumen_Pengesahan_Peneliti_Benoa.pdf',
        ], [
            'file_path' => 'chat_attachments/dokumen_pengesahan.pdf',
            'file_type' => 'pdf',
            'file_size' => 1820000,
            'mime_type' => 'application/pdf',
        ]);

        // =========================================================================
        // 4. Thread Percakapan Role Peneliti (Bima Saputra <-> Analyst)
        // =========================================================================

        $convBimaAnalyst = Conversation::firstOrCreate([
            'type' => 'direct',
            'title' => 'Konsultasi Parameter: ' . $bima->nama,
            'id_proyek' => $sampleProyek?->id_proyek,
            'created_by' => $bima->id_user,
        ]);

        ConversationParticipant::firstOrCreate([
            'id_conversation' => $convBimaAnalyst->id_conversation,
            'id_user' => $bima->id_user,
        ], [
            'last_read_at' => now()->subHours(1),
            'joined_at' => now()->subDays(1),
        ]);

        ConversationParticipant::firstOrCreate([
            'id_conversation' => $convBimaAnalyst->id_conversation,
            'id_user' => $analyst->id_user,
        ], [
            'last_read_at' => now(),
            'joined_at' => now()->subDays(1),
        ]);

        ChatMessage::firstOrCreate([
            'id_conversation' => $convBimaAnalyst->id_conversation,
            'id_sender' => $bima->id_user,
            'message' => 'Halo Tim Analyst, apakah ada batasan rentang nilai untuk koefisien valuasi pariwisata bahari?',
        ], [
            'id_proyek' => $sampleProyek?->id_proyek,
            'message_type' => 'text',
            'created_at' => now()->subHours(2),
            'updated_at' => now()->subHours(2),
        ]);

        $bimaReply = ChatMessage::firstOrCreate([
            'id_conversation' => $convBimaAnalyst->id_conversation,
            'id_sender' => $analyst->id_user,
            'message' => 'Halo Mas Bima, rentang koefisien mengacu pada standar BPS dan kajian valuasi PKSPL tahun 2023. Silakan gunakan interval referensi pada modul Master Data.',
        ], [
            'id_proyek' => $sampleProyek?->id_proyek,
            'message_type' => 'text',
            'created_at' => now()->subMinutes(15),
            'updated_at' => now()->subMinutes(15),
        ]);

        // =========================================================================
        // 5. Sample In-App Notifications (Bell Topbar)
        // =========================================================================

        // Notifikasi untuk Admin
        DB::table('notifications')->insertOrIgnore([
            'id' => (string) Str::uuid(),
            'type' => 'App\Notifications\Chat\NewChatMessageNotification',
            'notifiable_type' => User::class,
            'notifiable_id' => $admin->id_user,
            'data' => json_encode([
                'type' => 'chat_message',
                'conversation_id' => $convAdminRetno->id_conversation,
                'message_id' => $adminRetnoMsg->id_message,
                'sender_id' => $retno->id_user,
                'sender_name' => $retno->nama,
                'sender_role' => 'Peneliti',
                'message' => 'Selamat siang Pak Admin, berikut berkas pengesahan tim peneliti dan ringkasan eksekutif telah kami lampirkan.',
                'project_name' => $sampleProyek?->nama_proyek,
                'action_url' => '/admin/messages',
            ]),
            'read_at' => null,
            'created_at' => now()->subMinutes(25),
            'updated_at' => now()->subMinutes(25),
        ]);

        // Notifikasi untuk Analyst
        DB::table('notifications')->insertOrIgnore([
            'id' => (string) Str::uuid(),
            'type' => 'App\Notifications\Chat\NewChatMessageNotification',
            'notifiable_type' => User::class,
            'notifiable_id' => $analyst->id_user,
            'data' => json_encode([
                'type' => 'chat_message',
                'conversation_id' => $convRetno->id_conversation,
                'message_id' => $msgAttachment->id_message,
                'sender_id' => $retno->id_user,
                'sender_name' => $retno->nama,
                'sender_role' => 'Peneliti',
                'message' => 'Baik, berkas telaah revisi dan matriks justifikasi telah kami persiapkan.',
                'project_name' => $sampleProyek?->nama_proyek,
                'action_url' => '/analyst/discussions',
            ]),
            'read_at' => null,
            'created_at' => now()->subMinutes(5),
            'updated_at' => now()->subMinutes(5),
        ]);

        // Notifikasi untuk Peneliti (Bima Saputra)
        DB::table('notifications')->insertOrIgnore([
            'id' => (string) Str::uuid(),
            'type' => 'App\Notifications\Chat\NewChatMessageNotification',
            'notifiable_type' => User::class,
            'notifiable_id' => $bima->id_user,
            'data' => json_encode([
                'type' => 'chat_message',
                'conversation_id' => $convBimaAnalyst->id_conversation,
                'message_id' => $bimaReply->id_message,
                'sender_id' => $analyst->id_user,
                'sender_name' => $analyst->nama,
                'sender_role' => 'Analyst',
                'message' => 'Halo Mas Bima, rentang koefisien mengacu pada standar BPS dan kajian valuasi PKSPL tahun 2023.',
                'project_name' => $sampleProyek?->nama_proyek,
                'action_url' => '/peneliti/messages',
            ]),
            'read_at' => null,
            'created_at' => now()->subMinutes(15),
            'updated_at' => now()->subMinutes(15),
        ]);

        $this->command->info('ChatSeeder berhasil dijalankan: percakapan, pesan obrolan, lampiran, dan notifikasi siap digunakan.');
    }
}
