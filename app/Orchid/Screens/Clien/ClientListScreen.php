<?php

namespace App\Orchid\Screens\Clien;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Models\Service;
use App\Orchid\Layouts\Client\ClientListTable;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

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
            'clients' => Client::filters()->defaultSort('status', 'desc')->paginate(10)
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
        return [
            ModalToggle::make('Создать клиента')->modal('createClient')->method('create'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
           ClientListTable::class,
            Layout::modal('createClient', Layout::rows([
                Input::make('client.phone')->title('Телефон')->required(),
                Group::make([
                    Input::make('client.name')->title('Имя')->required(),
                    Input::make('client.last_name')->title('Фамилия')->required(),
                ]),
                Input::make('client.email')->title('Email')->type('email'),
                DateTimer::make('client.birthday')->format('Y-m-d')->title('Дата рождения'),
                Relation::make('client.service_id')->fromModel(Service::class, 'name')->title('Тип услуги')->required(),
            ]))->title('Создать клиента')->applyButton('Создать'),

            Layout::modal('editClient', Layout::rows([
                Input::make('client.id')->hidden(),
                Input::make('client.phone')->disabled()->title('Телефон'),
                Group::make([
                    Input::make('client.name')->required()->placeholder('Имя клиента')->title('Имя'),
                    Input::make('client.last_name')->required()->placeholder('Фамилия клиента')->title('Фамилия'),
                ]),
                Input::make('client.email')->title('Email')->type('email')->required(),
                DateTimer::make('client.birthday')->format('Y-m-d')->title('День рождения')->required(),
                Relation::make('client.service_id')->fromModel(Service::class, 'name')->title('Тип услуги')->required(),
                Select::make('client.assessment')->required()->options([
                    'Отлично' => 'Отлично',
                    'Хорошо' => 'Хорошо',
                    'Средне' => 'Средне',
                    'Ужасно' => 'Ужасно',
                ])->help('Реакция на услугу')->empty('Нет оценки', 'Нет оценки'),
            ]))->title('Редактировать клиента')->async('asyncGetClient'),
        ];
    }

    public function create(ClientRequest $request): void
    {
        $data = $request->validated();
        $data['client']['status'] = 'interviewed';

        Client::create($data['client']);
        Toast::info('Клиент создан');
    }

    public function asyncGetClient(Client $client)
    {
       return [
           'client' => $client
       ];
    }

    public function update(Client $client, ClientRequest $request)
    {
        $data = $request->client;
        $data['status'] = 'interviewed';
        $client->update($data);
        Toast::info('Клиент опрошен');
    }

//    public function createOrUpdateClient(ClientRequest $request, Client $client)
//    {
//        $data = $request->validated();
//        $clientId = $request->input('client.id');
//        $data['client']['status'] = 'interviewed';
//
//        Client::updateOrCreate(['id' => $clientId], $data['client']);
//        is_null($clientId) ? Toast::info('Клиент создан') : Toast::info('Клиент Отредактирован');
//    }

}
