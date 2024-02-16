<?php

namespace Tasawk\Lib;

use Tasawk\Models\Customer;
use Tasawk\Models\User;
use Tasawk\Notifications\Notification;

class NotificationMessageParser {
    public $adminMessage = null;
    public $mangaerMessage = null;
    public $customerMessage = null;
    private User $notifiable;

    public function __construct(User $notifiable) {
        $this->notifiable = $notifiable;
    }

    public static function init(User $notifiable) {
        return new static($notifiable);
    }

    public function adminMessage($text, $params = []): static {
        $this->adminMessage = Utils::convertStringToArrayLanguage($text, $params);
        return $this;
    }

    public function managerMessage($text, $params = []): static {
        $this->mangaerMessage = Utils::convertStringToArrayLanguage($text, $params);
        return $this;
    }

    public function customerMessage($text, $params = []): static {
        $this->customerMessage = Utils::convertStringToArrayLanguage($text, $params);
        return $this;
    }

    public function parse() {

        return match ($this->notifiable->roles()->first()->name) {
            Customer::ROLE => $this->customerMessage,
            default => $this->adminMessage??$this->mangaerMessage??$this->customerMessage,
        };

    }

}
