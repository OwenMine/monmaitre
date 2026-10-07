<?php

namespace App\Notifications;

use App\Models\Seance;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class SeanceContesteeNotification extends Notification
{
    use Queueable;

    public function __construct(public Seance $seance) {}

    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        // Vider le cache de notifications
    \Illuminate\Support\Facades\Cache::forget("user_{$notifiable->id}_unread_notifs");
    \Illuminate\Support\Facades\Cache::forget("user_{$notifiable->id}_latest_notifs");
        $isAdmin = $notifiable->isAdmin();

        return [
            'type'       => 'seance_contestee',
            'titre'      => $isAdmin ? 'Séance contestée — à arbitrer' : 'Séance contestée',
            'message'    => $isAdmin
                ? 'Le parent a contesté une séance du ' . $this->seance->date->format('d/m/Y') .
                  ' (' . $this->seance->placement->reference . '). Raison : ' . $this->seance->raison_contestation
                : 'Votre séance du ' . $this->seance->date->format('d/m/Y') .
                  ' a été contestée par le parent. MON MAÎTRE va arbitrer.',
            'url'        => $isAdmin
                ? route('admin.placements.show', $this->seance->placement)
                : route('repetiteur.seances.index', $this->seance->placement),
            'icone'      => 'target',
            'created_at' => now()->toISOString(),
        ];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        $isAdmin = $notifiable->isAdmin();
        $url = $isAdmin
            ? route('admin.placements.show', $this->seance->placement)
            : route('repetiteur.seances.index', $this->seance->placement);

        return (new WebPushMessage)
            ->title($isAdmin ? '⚠️ Séance contestée — à arbitrer' : '⚠️ Séance contestée')
            ->icon('/images/pwa/icon-192.png')
            ->badge('/images/pwa/icon-72.png')
            ->body($isAdmin
                ? 'Le parent a contesté une séance. Raison : ' . $this->seance->raison_contestation
                : 'Votre séance a été contestée. MON MAÎTRE va arbitrer.')
            ->action($isAdmin ? 'Arbitrer' : 'Voir la séance', $url)
            ->data(['url' => $url])
            ->tag('seance-contestee-' . $this->seance->id)
            ->options(['TTL' => 3600]);
    }
}