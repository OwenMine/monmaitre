<?php

namespace App\Notifications;

use App\Models\Repetiteur;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ProfilValideNotification extends Notification
{
    use Queueable;

    public function __construct(public Repetiteur $repetiteur) {}

    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'profil_valide',
            'titre'      => 'Profil validé',
            'message'    => 'Votre profil répétiteur a été validé par MON MAÎTRE. Vous pouvez maintenant recevoir des propositions.',
            'url'        => route('repetiteur.profil.edit'),
            'icone'      => 'check-badge',
            'created_at' => now()->toISOString(),
        ];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('🎉 Profil validé !')
            ->icon('/images/pwa/icon-192.png')
            ->badge('/images/pwa/icon-72.png')
            ->body('Félicitations ! Votre profil répétiteur a été validé par MON MAÎTRE.')
            ->action('Voir mon profil', route('repetiteur.profil.edit'))
            ->data(['url' => route('repetiteur.profil.edit')])
            ->tag('profil-valide-' . $this->repetiteur->id)
            ->options(['TTL' => 3600]);
    }
}