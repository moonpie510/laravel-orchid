<?php

namespace App\Orchid\Screens\Clien;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Models\Service;
use App\Orchid\Layouts\Client\ClientListTable;
use App\Orchid\Layouts\CreateOrUpdateClient;
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
    public $permision = 'platform.clients';

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
            ModalToggle::make('Создать клиента')->modal('createClient')->method('createOrUpdateClient'),
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
            Layout::modal('createClient', CreateOrUpdateClient::class)->title('Создать клиента')->applyButton('Создать'),
            Layout::modal('editClient', CreateOrUpdateClient::class)->title('Редактировать клиента')->async('asyncGetClient'),
        ];
    }

    public function asyncGetClient(Client $client)
    {
       return [
           'client' => $client
       ];
    }

    public function createOrUpdateClient(ClientRequest $request)
    {
        $data = $request->validated();
        $clientId = $request->input('client.id');
        $data['client']['status'] = 'interviewed';

        Client::updateOrCreate(['id' => $clientId], $data['client']);
        is_null($clientId) ? Toast::info('Клиент создан') : Toast::info('Клиент Отредактирован');
    }

}
