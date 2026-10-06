<?php

namespace App\Notifications;

use App\Models\Seance;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class SeanceValideeAdminNotification extends Notification
{
    use Queueable;

    public function __construct(public Seance $seance) {}

    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'seance_validee_admin',
            'titre'      => '✓ Séance validée par MON MAÎTRE',
            'message'    => 'Votre séance du ' . $this->seance->date->format('d/m/Y') .
                            ' pour ' . $this->seance->placement->eleve->prenom .
                            ' a été validée par MON MAÎTRE.',
            'url'        => route('repetiteur.seances.index', $this->seance->placement),
            'icone'      => 'check-badge',
            'created_at' => now()->toISOString(),
        ];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('✓ Séance validée par MON MAÎTRE')
            ->icon('/images/pwa/icon-192.png')
            ->badge('/images/pwa/icon-72.png')
            ->body('Votre séance du ' . $this->seance->date->format('d/m/Y') .
                   ' pour ' . $this->seance->placement->eleve->prenom .
                   ' a été validée.')
            ->action('Voir les séances', route('repetiteur.seances.index', $this->seance->placement))
            ->data(['url' => route('repetiteur.seances.index', $this->seance->placement)])
            ->tag('seance-validee-admin-' . $this->seance->id)
            ->options(['TTL' => 3600]);
    }
}