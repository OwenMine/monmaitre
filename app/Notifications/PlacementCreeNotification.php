<?php

namespace App\Notifications;

use App\Models\Placement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class PlacementCreeNotification extends Notification
{
    use Queueable;

    public function __construct(public Placement $placement) {}

    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        $isRepetiteur = $notifiable->isRepetiteur();
        $url = $isRepetiteur
            ? route('repetiteur.placements.show', $this->placement)
            : route('parent.placements.show', $this->placement);
            // Vider le cache de notifications
    \Illuminate\Support\Facades\Cache::forget("user_{$notifiable->id}_unread_notifs");
    \Illuminate\Support\Facades\Cache::forget("user_{$notifiable->id}_latest_notifs");

        return [
            'type'       => 'placement_cree',
            'titre'      => 'Nouveau placement',
            'message'    => 'Un placement a été créé : ' . $this->placement->reference . '.',
            'url'        => $url,
            'icone'      => 'target',
            'created_at' => now()->toISOString(),
        ];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        $isRepetiteur = $notifiable->isRepetiteur();
        $url = $isRepetiteur
            ? route('repetiteur.placements.show', $this->placement)
            : route('parent.placements.show', $this->placement);

        return (new WebPushMessage)
            ->title('🎯 Nouveau placement')
            ->icon('/images/pwa/icon-192.png')
            ->badge('/images/pwa/icon-72.png')
            ->body('Un placement a été créé : ' . $this->placement->reference . ' — ' . $this->placement->matiere)
            ->action('Voir le placement', $url)
            ->data(['url' => $url])
            ->tag('placement-cree-' . $this->placement->id)
            ->options(['TTL' => 3600]);
    }
}