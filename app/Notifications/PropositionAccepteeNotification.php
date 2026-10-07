<?php

namespace App\Notifications;

use App\Models\Proposition;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class PropositionAccepteeNotification extends Notification
{
    use Queueable;

    public function __construct(public Proposition $proposition) {}

    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        // Vider le cache de notifications
    \Illuminate\Support\Facades\Cache::forget("user_{$notifiable->id}_unread_notifs");
    \Illuminate\Support\Facades\Cache::forget("user_{$notifiable->id}_latest_notifs");
        return [
            'type'       => 'proposition_acceptee',
            'titre'      => 'Proposition acceptée',
            'message'    => 'Le parent a accepté la proposition pour la demande #MM-' .
                            str_pad($this->proposition->demande->id, 4, '0', STR_PAD_LEFT) . '.',
            'url'        => route('admin.demandes.show', $this->proposition->demande),
            'icone'      => 'check-badge',
            'created_at' => now()->toISOString(),
        ];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Proposition acceptée ✓')
            ->icon('/images/pwa/icon-192.png')
            ->badge('/images/pwa/icon-72.png')
            ->body('Le parent a accepté la proposition pour la demande #MM-' .
                   str_pad($this->proposition->demande->id, 4, '0', STR_PAD_LEFT) . '.')
            ->action('Voir la demande', route('admin.demandes.show', $this->proposition->demande))
            ->data(['url' => route('admin.demandes.show', $this->proposition->demande)])
            ->tag('proposition-acceptee-' . $this->proposition->id)
            ->options(['TTL' => 3600]);
    }
}