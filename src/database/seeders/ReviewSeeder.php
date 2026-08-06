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
        $proyek = Proyek::where('status', 'Submitted')->first() ?? Proyek::first();
        $analyst = User::whereHas('role', fn($q) => $q->where('nama_role', 'Analyst'))->first();
        
        if (!$proyek || !$analyst) {
            return; // Skip if no necessary data is found
        }
        
        $reviews = Review::factory()->count(2)->create([
            'id_proyek' => $proyek->id_proyek,
            'id_reviewer' => $analyst->id_user,
        ]);
        
        foreach ($reviews as $review) {
            $comments = ReviewComment::factory()->count(3)->create([
                'id_review' => $review->id_review,
            ]);
            
            // Create a reply to the first comment
            if ($comments->isNotEmpty()) {
                ReviewComment::factory()->create([
                    'id_review' => $review->id_review,
                    'id_parent' => $comments->first()->id_comment,
                ]);
            }
        }
    }
}
