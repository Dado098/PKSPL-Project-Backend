<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Proyek;
use App\Models\Review;
use App\Models\ReviewComment;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $analyst1 = User::where('email', 'analyst@gmail.com')->first();
        $analyst2 = User::where('email', 'benny.nababan@pkspl.ipb.ac.id')->first();
        $penelitiRetno = User::where('email', 'demo.retno@pkspl.ipb.ac.id')->first();
        $penelitiBima = User::where('email', 'peneliti@gmail.com')->first();

        // 1. Review for PRJ-004 (Restorasi Karbon Biru Mangrove Teluk Benoa)
        $proyek004 = Proyek::where('kode_proyek', 'PRJ-004')->first() ?? Proyek::find(4);
        if ($proyek004 && $analyst1) {
            $rev1 = Review::updateOrCreate(
                [
                    'id_proyek' => $proyek004->id_proyek,
                    'id_reviewer' => $analyst1->id_user,
                ],
                [
                    'status' => Review::STATUS_OPEN,
                    'decision' => Review::DECISION_NEED_REVISION,
                    'notes' => 'Periksa kembali parameter harga pasar komoditas perikanan dan kesesuaian delineasi sempadan pesisir Teluk Benoa.',
                    'reviewed_at' => now()->subHours(5),
                ]
            );

            // Comments from Analyst PKSPL
            $c1 = ReviewComment::updateOrCreate(
                [
                    'id_review' => $rev1->id_review,
                    'body' => 'Nilai harga pasar komoditas perikanan dan kepiting bakau mohon dicek kembali dengan standar HET regional Bali.',
                ],
                [
                    'id_user' => $analyst1->id_user,
                    'id_parent' => null,
                ]
            );

            if ($penelitiRetno) {
                ReviewComment::updateOrCreate(
                    [
                        'id_review' => $rev1->id_review,
                        'id_parent' => $c1->id_comment,
                    ],
                    [
                        'id_user' => $penelitiRetno->id_user,
                        'body' => 'Baik, kami telah menyesuaikan tabel referensi dengan data TPI Kedonganan terbaru.',
                    ]
                );
            }

            ReviewComment::updateOrCreate(
                [
                    'id_review' => $rev1->id_review,
                    'body' => 'Metadata CRS shapefile EPSG:4326 telah terverifikasi, pastikan batas delineasi sempadan terhubung dengan data spasial.',
                ],
                [
                    'id_user' => $analyst1->id_user,
                    'id_parent' => null,
                ]
            );

            if ($analyst2) {
                $rev2 = Review::updateOrCreate(
                    [
                        'id_proyek' => $proyek004->id_proyek,
                        'id_reviewer' => $analyst2->id_user,
                    ],
                    [
                        'status' => Review::STATUS_OPEN,
                        'decision' => Review::DECISION_NEED_REVISION,
                        'notes' => 'Verifikasi luasan tutupan mangrove jarang pada sempadan pesisir (15 ha) dengan polygon GIS.',
                        'reviewed_at' => now()->subHours(4),
                    ]
                );

                ReviewComment::updateOrCreate(
                    [
                        'id_review' => $rev2->id_review,
                        'body' => 'Luas tutupan mangrove jarang pada sempadan pesisir (15 ha) mohon diverifikasi dengan polygon digitasi GIS.',
                    ],
                    [
                        'id_user' => $analyst2->id_user,
                        'id_parent' => null,
                    ]
                );
            }
        }

        // 2. Review for PRJ-001 (Kajian Valuasi Ekonomi Mangrove Teluk Benoa)
        $proyek001 = Proyek::where('kode_proyek', 'PRJ-001')->first() ?? Proyek::find(1);
        if ($proyek001 && $analyst1) {
            $rev001 = Review::updateOrCreate(
                [
                    'id_proyek' => $proyek001->id_proyek,
                    'id_reviewer' => $analyst1->id_user,
                ],
                [
                    'status' => Review::STATUS_RESOLVED,
                    'decision' => Review::DECISION_APPROVED,
                    'notes' => 'Seluruh metodologi valuasi dan perhitungan TEV telah divalidasi dan memenuhi standar PKSPL IPB.',
                    'reviewed_at' => now()->subDays(2),
                ]
            );

            $c001 = ReviewComment::updateOrCreate(
                [
                    'id_review' => $rev001->id_review,
                    'body' => 'Formula Replacement Cost untuk konstruksi seawall sudah sesuai panduan teknis.',
                ],
                [
                    'id_user' => $analyst1->id_user,
                    'id_parent' => null,
                ]
            );

            if ($penelitiBima) {
                ReviewComment::updateOrCreate(
                    [
                        'id_review' => $rev001->id_review,
                        'id_parent' => $c001->id_comment,
                    ],
                    [
                        'id_user' => $penelitiBima->id_user,
                        'body' => 'Siap, kami sudah melampirkan faktur survei pasar Badung pada sheet pendukung.',
                    ]
                );
            }
        }
    }
}
