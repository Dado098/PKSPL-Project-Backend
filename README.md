# PKSPL Project Backend

Backend REST API untuk pencatatan proyek, area terdampak, jasa ekosistem, hasil valuasi, data referensi/AI, validasi analyst, dan histori pada PKSPL. Implementasi saat ini berfokus pada penyimpanan dan pengelolaan data melalui Laravel API v1.

## Tujuan

Menyediakan fondasi API terstruktur bagi proses valuasi jasa ekosistem dan Total Economic Value (TEV): proyek memiliki area terdampak, area memiliki catatan jasa ekosistem, dan hasil valuasi dapat divalidasi serta dicatat historinya.

> Status implementasi: API menyediakan CRUD dan validasi data. Formula perhitungan nilai jasa, TEV area/proyek, autentikasi, otorisasi, dan integrasi provider AI belum terdapat pada source code saat ini.

## Teknologi

- PHP ^8.3 dan Laravel ^13.8
- Eloquent ORM, Form Request, dan API Resource
- PostgreSQL 16 + PostGIS melalui Docker Compose (konfigurasi aplikasi tetap dapat diarahkan dengan `.env`)
- Vite, Tailwind CSS 4, dan PHPUnit

## Struktur utama

```text
.
├── docker/                  # Dockerfile PHP dan konfigurasi Nginx
├── docker-compose.yml       # App, Nginx, PostgreSQL/PostGIS, Adminer
├── docs/                    # Dokumentasi proyek dan prompt historis
└── src/                     # Aplikasi Laravel
    ├── app/Http/            # Controller API, Form Request, API Resource
    ├── app/Models/          # Model dan relasi Eloquent
    ├── database/            # Migration, factory, seeder
    ├── routes/api.php       # Endpoint /api/v1
    └── tests/               # Test PHPUnit
```

## Instalasi dan menjalankan

Instruksi lengkap tersedia di [docs/installation.md](docs/installation.md). Ringkasnya, dari `src/`:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Untuk Docker, jalankan `docker compose up --build` dari root proyek. Nginx tersedia pada `http://localhost:8000` dan Adminer pada `http://localhost:8081`. PostgreSQL tersedia pada `localhost:5433` jika Anda perlu koneksi dari host.

## Dokumentasi

- [Arsitektur](docs/architecture.md)
- [Aturan bisnis](docs/business-rules.md)
- [Database dan ERD](docs/database.md)
- [Workflow](docs/workflow.md)
- [API](docs/api.md)
- [Instalasi](docs/installation.md)
- [Standar coding](docs/coding-standard.md)

## Diagram gambar

`docs/usecase.png`, `docs/activity.png`, `docs/sequence.png`, dan `docs/erd.png` belum tersedia. Diagram Mermaid dalam dokumentasi dapat dipakai sebagai sumber untuk membuat diagram raster tersebut kemudian.

## Kontributor

Kontributor individual tidak tercantum dalam source code atau metadata repository yang dianalisis. Lisensi/proyek menyebut PKSPL IPB.
