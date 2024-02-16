<?php

namespace Tasawk\Enum;

use Filament\Support\Contracts\HasLabel;

enum OrderPaymentStatus: string implements HasLabel {
    case PENDING = 'pending';
    case PAID = 'paid';
    public function getLabel(): ?string {
        return __("panel.enums.$this->value");
    }
    public function getColor(): string {
        return match ($this->value) {
            'pending' => 'warning',
            'paid' => 'success',
        };
    }
}
