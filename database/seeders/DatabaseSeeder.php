<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\MataKuliah;
use App\Models\Material;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Dosen Pengampu Default
        $dosen = User::updateOrCreate(
            ['email' => 'dosen@celoe.test'],
            [
                'name' => 'Dr. Ir. Budi Santoso, M.T.',
                'password' => Hash::make('dosen123'),
                'role' => 'dosen',
                'nomor_induk' => '198501012015041001',
            ]
        );

        // 2. Akun Mahasiswa Default
        $mahasiswa1 = User::updateOrCreate(
            ['email' => 'mahasiswa@celoe.test'],
            [
                'name' => 'Ahmad Pratama',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'nomor_induk' => '1301210001',
            ]
        );

        // 3. Mata Kuliah Dasar (Modul 3)
        $course1 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF301'],
            [
                'nama_mk' => 'Pengembangan Aplikasi Website',
                'deskripsi' => 'Mata kuliah yang membahas arsitektur aplikasi web modern menggunakan framework Laravel, MVC, Eloquent ORM, dan keamanan web.',
                'sks' => 4,
                'semester' => 'Ganjil 2026/2027',
                'dosen_id' => $dosen->id,
            ]
        );

        // 4. Daftarkan Mahasiswa ke Mata Kuliah Pertama (status approved)
        $course1->students()->syncWithoutDetaching([
            $mahasiswa1->id => ['status' => 'approved'],
        ]);

        // 5. Tambahkan Materi Contoh
        Material::updateOrCreate(
            ['course_id' => $course1->id, 'minggu_ke' => 1, 'urutan' => 1],
            [
                'judul' => 'Pengenalan Arsitektur MVC & Routing di Laravel 12',
                'konten' => 'Mempelajari alur HTTP Request, Route Middleware, Controller, dan pembuatan View menggunakan Blade Template.',
            ]
        );

        // TODO 8: Tambahkan minimal 2 mata kuliah lagi dengan semester berbeda, 1 mahasiswa tambahan, 2 tugas, dan contoh data submission
        // 6. Mahasiswa Tambahan
        $mahasiswa2 = User::updateOrCreate(
            ['email' => 'mahasiswa2@celoe.test'],
            [
                'name' => 'Illona Indriani Fellysa',
                'password' => Hash::make('123'),
                'role' => 'mahasiswa',
                'nim' => '102042500002',
            ]
        );

        // 7. Mata Kuliah Tambahan dengan Semester Berbeda
        $course2 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF302'],
            [
                'nama_mk' => 'Basis Data',
                'deskripsi' => 'Mempelajari perancangan basis data, SQL, normalisasi, dan pengelolaan database.',
                'sks' => 3,
                'semester' => 'Genap 2026/2027',
                'dosen_id' => $dosen->id,
            ]
        );

        $course3 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF303'],
            [
                'nama_mk' => 'Pemrograman Berorientasi Objek',
                'deskripsi' => 'Mempelajari konsep pemrograman berorientasi objek, class, object, inheritance, dan polymorphism.',
                'sks' => 3,
                'semester' => 'Ganjil 2025/2026',
                'dosen_id' => $dosen->id,
            ]
        );

        // 8. Pendaftaran Mahasiswa ke Mata Kuliah
        $course1->students()->syncWithoutDetaching([
            $mahasiswa1->id => ['status' => 'approved'],
            $mahasiswa2->id => ['status' => 'approved'],
        ]);

        $course2->students()->syncWithoutDetaching([
            $mahasiswa1->id => ['status' => 'approved'],
            $mahasiswa2->id => ['status' => 'approved'],
        ]);

        $course3->students()->syncWithoutDetaching([
            $mahasiswa1->id => ['status' => 'approved'],
            $mahasiswa2->id => ['status' => 'approved'],
        ]);

        // 9. Tugas Contoh
        $assignment1 = Assignment::updateOrCreate(
            ['course_id' => $course1->id, 'judul' => 'Tugas Laravel MVC'],
            [
                'deskripsi' => 'Jelaskan konsep MVC pada Laravel dan berikan contoh penerapannya.',
                'deadline' => now()->addDays(7),
            ]
        );

        $assignment2 = Assignment::updateOrCreate(
            ['course_id' => $course2->id, 'judul' => 'Tugas SQL dan Normalisasi'],
            [
                'deskripsi' => 'Buat contoh query SQL dan jelaskan proses normalisasi database.',
                'deadline' => now()->addDays(14),
            ]
        );

        // 10. Contoh Pengumpulan Tugas
        Submission::updateOrCreate(
            [
                'assignment_id' => $assignment1->id,
                'user_id' => $mahasiswa1->id,
            ],
            [
                'jawaban' => 'MVC merupakan pola arsitektur yang memisahkan Model untuk pengelolaan data, View untuk tampilan, dan Controller untuk mengatur alur aplikasi.',
                'submitted_at' => now(),
                'nilai' => 85,
                'catatan_dosen' => 'Jawaban sudah baik dan sesuai materi.',
            ]
        );

        Submission::updateOrCreate(
            [
                'assignment_id' => $assignment1->id,
                'user_id' => $mahasiswa2->id,
            ],
            [
                'jawaban' => 'MVC terdiri dari Model, View, dan Controller. Model mengelola data, View menampilkan halaman, sedangkan Controller mengatur proses dan menghubungkan Model dengan View.',
                'submitted_at' => now(),
                'nilai' => 90,
                'catatan_dosen' => 'Penjelasan cukup lengkap.',
            ]
        );

        Submission::updateOrCreate(
            [
                'assignment_id' => $assignment2->id,
                'user_id' => $mahasiswa1->id,
            ],
            [
                'jawaban' => 'SQL digunakan untuk mengelola data pada database. Normalisasi dilakukan untuk mengurangi redundansi dan menjaga konsistensi data.',
                'submitted_at' => now(),
                'nilai' => 88,
                'catatan_dosen' => 'Pemahaman sudah baik.',
            ]
        );
    }
}
