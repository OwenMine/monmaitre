<?php

namespace App\Notifications;

use App\Models\Proposition;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class PropositionRefuseeNotification extends Notification
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
            'type'       => 'proposition_refusee',
            'titre'      => 'Proposition refusée',
            'message'    => 'Le parent a refusé la proposition pour la demande #MM-' .
                            str_pad($this->proposition->demande->id, 4, '0', STR_PAD_LEFT) .
                            '. Un nouveau profil doit être recherché.',
            'url'        => route('admin.matching.rechercher', $this->proposition->demande),
            'icone'      => 'target',
            'created_at' => now()->toISOString(),
        ];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Proposition refusée')
            ->icon('/images/pwa/icon-192.png')
            ->badge('/images/pwa/icon-72.png')
            ->body('Le parent a refusé la proposition. Une nouvelle recherche est nécessaire.')
            ->action('Chercher un répétiteur', route('admin.matching.rechercher', $this->proposition->demande))
            ->data(['url' => route('admin.matching.rechercher', $this->proposition->demande)])
            ->tag('proposition-refusee-' . $this->proposition->id)
            ->options(['TTL' => 3600]);
    }
}