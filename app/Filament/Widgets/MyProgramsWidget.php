<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class MyProgramsWidget extends Widget
{
    protected static string $view = 'filament.widgets.my-programs-widget';

    protected int | string | array $columnSpan = 'full';

}
