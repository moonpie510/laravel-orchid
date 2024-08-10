<?php

namespace App\Orchid\Screens\Clien;

use App\Models\Client;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class ClientListScreen extends Screen
{
    public $description = 'Список клментов';

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'clients' => Client::paginate(10)
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Клиенты';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::table('clients', [
                TD::make('phone', 'Телефон')->width('150px'),
                TD::make('status', 'Статус')->render(function (Client $client) {
                    return $client->status === 'interviewed' ? 'Опрошен' : 'Не опрошен';
                })->width('150px')->popover('Статус работы оператора'),
                TD::make('email', 'Email'),
                TD::make('assessment', 'Оценка')->width('200px'),
                TD::make('created_at', 'Дата создания')->defaultHidden(),
                TD::make('updated_at', 'Дата обновления')->defaultHidden(),
            ])
        ];
    }
}
