<?php

namespace Tasawk\Forms\Components;

use Tasawk\Lib\FontawesomeIcons;
use Filament\Forms\Components\Select;

class SelectFontAwesomeIcon extends Select {
    protected string $view = 'forms.components.select-font-awesome-icon';

    public function getOptions(): array {
        return FontawesomeIcons::toSelect();
    }
}
