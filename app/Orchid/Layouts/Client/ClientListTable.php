<?php

namespace App\Orchid\Layouts\Client;

use App\Models\Client;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ClientListTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'clients';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('phone', 'Телефон')->width('150px')->filter(TD::FILTER_TEXT),
            TD::make('status', 'Статус')->render(function (Client $client) {
                return $client->status === 'interviewed' ? 'Опрошен' : 'Не опрошен';
            })->width('150px')->popover('Статус работы оператора')->sort(),
            TD::make('email', 'Email'),
            TD::make('assessment', 'Оценка')->width('200px'),
            TD::make('created_at', 'Дата создания')->defaultHidden(),
            TD::make('updated_at', 'Дата обновления')->defaultHidden(),
            TD::make('action')->render(function (Client $client) {
                return ModalToggle::make('Редактировать')
                    ->modal('editClient')
                    ->method('createOrUpdateClient')
                    ->modalTitle('Редактировать клиента ' . $client->phone)
                    ->asyncParameters([
                        'client' => $client->id
                    ]);
            }),
        ];
    }
}
