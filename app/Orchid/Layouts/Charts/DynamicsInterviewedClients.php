<?php

namespace App\Orchid\Layouts\Charts;

use Orchid\Screen\Layouts\Chart;

class DynamicsInterviewedClients extends Chart
{
    /**
     * Available options:
     * 'bar', 'line',
     * 'pie', 'percentage'.
     *
     * @var string
     */
    protected $type = 'line';

    protected $title = 'Динамика опрошенных клиентов';

    protected $target = 'interviewedClients';

    /**
     * Determines whether to display the export button.
     *
     * @var bool
     */
    protected $export = true;
}
