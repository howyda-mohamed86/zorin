<?php

namespace Tasawk\Enum;

use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasLabel {
    case IN_REVIEW = 'in_review';
    case PENDING = 'pending';
    case DESIGNING = 'designing';
    case DELIVERED = 'delivered';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';

    public function getLabel(): ?string {
        return __("panel.enums.$this->value");
    }

    public function getColor(): string {
        return match ($this->value) {
            'pending', 'in_review' => 'warning',
            'designing' => 'primary',
            'delivered', 'accepted' => 'success',
            'rejected' => 'danger',
        };

    }

}
