<?php

namespace App\Notifications;

use App\Models\Seance;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class NouvelleSeanceNotification extends Notification
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
        return [
            'type'       => 'nouvelle_seance',
            'titre'      => 'Nouvelle séance à valider',
            'message'    => $this->seance->repetiteur->user->name .
                            ' a enregistré une séance pour ' .
                            $this->seance->placement->eleve->prenom .
                            ' le ' . $this->seance->date->format('d/m/Y') .
                            '. Merci de la valider.',
            'url'        => route('parent.placements.show', $this->seance->placement),
            'icone'      => 'check-badge',
            'created_at' => now()->toISOString(),
        ];
      
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('📚 Nouvelle séance à valider')
            ->icon('/images/pwa/icon-192.png')
            ->badge('/images/pwa/icon-72.png')
            ->body($this->seance->repetiteur->user->name .
                   ' a enregistré une séance pour ' .
                   $this->seance->placement->eleve->prenom .
                   ' le ' . $this->seance->date->format('d/m/Y') . '.')
            ->action('Valider la séance', route('parent.placements.show', $this->seance->placement))
            ->data(['url' => route('parent.placements.show', $this->seance->placement)])
            ->tag('nouvelle-seance-' . $this->seance->id)
            ->options(['TTL' => 3600]);
    }
}