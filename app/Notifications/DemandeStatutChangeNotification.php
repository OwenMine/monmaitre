<?php

namespace App\Notifications;

use App\Models\Demande;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class DemandeStatutChangeNotification extends Notification
{
    use Queueable;

    public function __construct(public Demande $demande) {}

    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'demande_statut_change',
            'titre'      => 'Statut de votre demande modifié',
            'message'    => 'Votre demande #MM-' .
                            str_pad($this->demande->id, 4, '0', STR_PAD_LEFT) .
                            ' est passée au statut "' . $this->demande->statutLabel() . '".',
            'url'        => route('parent.demandes.show', $this->demande),
            'icone'      => 'document',
            'created_at' => now()->toISOString(),
        ];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Statut de votre demande modifié')
            ->icon('/images/pwa/icon-192.png')
            ->badge('/images/pwa/icon-72.png')
            ->body('Votre demande #MM-' .
                   str_pad($this->demande->id, 4, '0', STR_PAD_LEFT) .
                   ' est passée au statut "' . $this->demande->statutLabel() . '".')
            ->action('Voir la demande', route('parent.demandes.show', $this->demande))
            ->data(['url' => route('parent.demandes.show', $this->demande)])
            ->tag('demande-statut-' . $this->demande->id)
            ->options(['TTL' => 3600]);
    }
}