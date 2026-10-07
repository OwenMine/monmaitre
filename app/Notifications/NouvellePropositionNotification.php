<?php

namespace App\Notifications;

use App\Models\Proposition;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class NouvellePropositionNotification extends Notification
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
            'type'       => 'nouvelle_proposition',
            'titre'      => 'Nouvelle proposition reçue',
            'message'    => 'MON MAÎTRE vous a proposé un répétiteur pour la demande #MM-' .
                            str_pad($this->proposition->demande->id, 4, '0', STR_PAD_LEFT) . '.',
            'url'        => route('parent.propositions.index'),
            'icone'      => 'bell',
            'created_at' => now()->toISOString(),
        ];
            
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Nouvelle proposition reçue')
            ->icon('/images/pwa/icon-192.png')
            ->badge('/images/pwa/icon-72.png')
            ->body('MON MAÎTRE vous a proposé un répétiteur pour la demande #MM-' .
                   str_pad($this->proposition->demande->id, 4, '0', STR_PAD_LEFT) . '.')
            ->action('Voir la proposition', route('parent.propositions.index'))
            ->data(['url' => route('parent.propositions.index')])
            ->tag('nouvelle-proposition-' . $this->proposition->id)
            ->options(['TTL' => 3600]);
    }
}