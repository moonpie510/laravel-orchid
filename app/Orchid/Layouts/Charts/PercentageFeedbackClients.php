<?php

namespace App\Orchid\Layouts\Charts;

use Orchid\Screen\Layouts\Chart;

class PercentageFeedbackClients extends Chart
{
    /**
     * Available options:
     * 'bar', 'line',
     * 'pie', 'percentage'.
     *
     * @var string
     */
    protected $type = 'pie';

    protected $title = 'Отзывы клиентов';

    protected $target = 'percentageFeedback';

    /**
     * Determines whether to display the export button.
     *
     * @var bool
     */
    protected $export = true;
}
