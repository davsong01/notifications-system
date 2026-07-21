<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $emailProviders = config('david-oghi-notifications-system.tables.email_providers', 'email_providers');
        $notificationLogs = config('david-oghi-notifications-system.tables.notification_logs', 'notification_logs');
        $notifications = config('david-oghi-notifications-system.tables.notifications', 'notifications');

        if (! Schema::hasTable($emailProviders)) {
            Schema::create($emailProviders, function (Blueprint $table): void {
                $table->id();
                $table->string('name')->nullable();
                $table->string('MAIL_MAILER')->nullable();
                $table->string('MAIL_HOST')->nullable();
                $table->string('MAIL_PORT')->nullable();
                $table->string('MAIL_USERNAME')->nullable();
                $table->string('MAIL_PASSWORD')->nullable();
                $table->string('MAIL_ENCRYPTION')->nullable();
                $table->string('MAIL_FROM_ADDRESS')->nullable();
                $table->string('MAIL_FROM_NAME')->nullable();
                $table->string('MAIL_REPLY_TO_ADDRESS')->nullable();
                $table->string('MAIL_REPLY_TO_NAME')->nullable();
                $table->string('status')->default('active')->index();
                $table->timestamps();
            });
        } else {
            Schema::table($emailProviders, function (Blueprint $table) use ($emailProviders): void {
                if (! Schema::hasColumn($emailProviders, 'MAIL_MAILER')) {
                    $table->string('MAIL_MAILER')->nullable()->after('name');
                }
            });
        }

        if (! Schema::hasTable($notificationLogs)) {
            Schema::create($notificationLogs, function (Blueprint $table): void {
                $table->id();
                $table->string('sender')->nullable();
                $table->unsignedBigInteger('provider_id')->nullable()->index();
                $table->integer('priority')->default(1)->index();
                $table->string('preamble')->nullable();
                $table->string('channel')->default('email')->index();
                $table->string('recipient')->nullable()->index();
                $table->string('type')->default('generic')->index();
                $table->string('subject')->nullable();
                $table->longText('content')->nullable();
                $table->json('attachments')->nullable();
                $table->json('meta')->nullable();
                $table->integer('status')->default(0)->index();
                $table->timestamp('sent_at')->nullable();
                $table->text('errors')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table($notificationLogs, function (Blueprint $table) use ($notificationLogs): void {
                if (! Schema::hasColumn($notificationLogs, 'meta')) {
                    $table->json('meta')->nullable()->after('attachments');
                }
            });
        }

        if (! Schema::hasTable($notifications)) {
            Schema::create($notifications, function (Blueprint $table): void {
                $table->uuid('id')->primary();
                $table->string('type', 191);
                $table->string('notifiable_type', 191);
                $table->unsignedBigInteger('notifiable_id');
                $table->tinyInteger('priority')->default(1)->index();
                $table->json('data');
                $table->timestamp('read_at')->nullable()->index();
                $table->timestamps();

                $table->index(['notifiable_type', 'notifiable_id'], 'notifications_notifiable_index');
                $table->index(['notifiable_type', 'notifiable_id', 'created_at'], 'notifications_notifiable_created_index');
            });
        }
    }

    public function down(): void
    {
        if (! config('david-oghi-notifications-system.migrations.drop_tables_on_rollback', false)) {
            return;
        }

        Schema::dropIfExists(config('david-oghi-notifications-system.tables.notification_logs', 'notification_logs'));
        Schema::dropIfExists(config('david-oghi-notifications-system.tables.notifications', 'notifications'));
        Schema::dropIfExists(config('david-oghi-notifications-system.tables.email_providers', 'email_providers'));
    }
};
