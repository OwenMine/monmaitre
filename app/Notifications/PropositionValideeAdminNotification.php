<?php

namespace App\Notifications;

use App\Models\Proposition;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class PropositionValideeAdminNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Proposition $proposition,
        public ?string $messageAdmin = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'proposition_validee_admin',
            'titre'      => 'Votre proposition a été validée',
            'message'    => 'MON MAÎTRE a validé la proposition pour la demande #MM-' .
                            str_pad($this->proposition->demande->id, 4, '0', STR_PAD_LEFT) .
                            '. Un placement a été créé.',
            'url'        => route('parent.placements.index'),
            'icone'      => 'check-badge',
            'created_at' => now()->toISOString(),
        ];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('✓ Proposition validée')
            ->icon('/images/pwa/icon-192.png')
            ->badge('/images/pwa/icon-72.png')
            ->body('MON MAÎTRE a validé la proposition. Le placement a été créé.')
            ->action('Voir mes placements', route('parent.placements.index'))
            ->data(['url' => route('parent.placements.index')])
            ->tag('proposition-validee-' . $this->proposition->id)
            ->options(['TTL' => 3600]);
    }
}