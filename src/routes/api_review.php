<?php
declare(strict_types=1);

use App\Http\Controllers\Api\V1\Review\ActivityLogController;
use App\Http\Controllers\Api\V1\Review\CommentAttachmentController;
use App\Http\Controllers\Api\V1\Review\CommentReplyController;
use App\Http\Controllers\Api\V1\Review\DatasetSubmissionController;
use App\Http\Controllers\Api\V1\Review\NotificationController;
use App\Http\Controllers\Api\V1\Review\ReviewCommentController;
use App\Http\Controllers\Api\V1\Review\ReviewController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('auth:sanctum')->group(function (): void {

    // Dataset Submission
    Route::post('proyek/{proyek}/submit', [DatasetSubmissionController::class, 'submit'])
        ->name('proyek.submit');

    // Reviews
    Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('proyek/{proyek}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');
    Route::patch('reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::post('reviews/{review}/resolve', [ReviewController::class, 'resolve'])->name('reviews.resolve');
    Route::post('reviews/{review}/reopen', [ReviewController::class, 'reopen'])->name('reviews.reopen');
    Route::post('reviews/{review}/close', [ReviewController::class, 'close'])->name('reviews.close');

    // Comments
    Route::get('reviews/{review}/comments', [ReviewCommentController::class, 'index'])->name('reviews.comments.index');
    Route::post('reviews/{review}/comments', [ReviewCommentController::class, 'store'])->name('reviews.comments.store');
    Route::get('reviews/{review}/comments/{comment}', [ReviewCommentController::class, 'show'])->name('reviews.comments.show');
    Route::patch('reviews/{review}/comments/{comment}', [ReviewCommentController::class, 'update'])->name('reviews.comments.update');
    Route::delete('reviews/{review}/comments/{comment}', [ReviewCommentController::class, 'destroy'])->name('reviews.comments.destroy');

    // Replies
    Route::post('reviews/{review}/comments/{comment}/replies', [CommentReplyController::class, 'store'])->name('reviews.comments.replies.store');

    // Attachments
    Route::post('reviews/{review}/comments/{comment}/attachments', [CommentAttachmentController::class, 'store'])->name('reviews.comments.attachments.store');
    Route::delete('reviews/{review}/comments/{comment}/attachments/{attachment}', [CommentAttachmentController::class, 'destroy'])->name('reviews.comments.attachments.destroy');

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    // Activity Logs
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
});
