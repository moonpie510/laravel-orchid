<?php

namespace App\Orchid\Screens;

use App\Models\Client;
use App\Orchid\Layouts\Charts\DynamicsInterviewedClients;
use App\Orchid\Layouts\Charts\PercentageFeedbackClients;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;
use Illuminate\Validation\ValidationException;

class AnalyticsAndReportsScreen extends Screen
{
    public $permission = ['platform.analytics', 'platform.reports'];

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'percentageFeedback' => Client::whereNotNull('assessment')->countForGroup('assessment')->toChart(),
            'interviewedClients' => [
                Client::countByDays(startDate:null, stopDate:null, dateColumn:'updated_at')->toChart('Опрошенные клиенты'),
                Client::countByDays()->toChart('Новые клиенты'),
            ],
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'AnalyticsAndReportsScreen';
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

    public function exportClients()
    {
        $clients = Client::with('service')->get(['phone', 'email', 'status', 'assessment', 'service_id']);
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=clients.csv'
        ];
        $columns = ['Телефон', 'email', 'Статус', 'Оценка', 'Сервис'];
        $callback = function () use ($clients, $columns) {
            $stream = fopen('php://output', 'w');
            fputcsv($stream, $columns);

            foreach ($clients as $client) {
                fputcsv($stream, [
                    'Телефон' => $client->phone,
                    'Email'   => $client->email,
                    'Статус'  => Client::STATUS[$client->status],
                    'Оценка' => $client->assessment,
                    'Сервис' => $client->service?->name,
                ]);
            }
            fclose($stream);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function importClientByPhone(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt']
        ]);
        $phones = array_map(function ($rawPhone) {
            return make_phone_normalized(array_shift($rawPhone));
        }, array_map('str_getcsv', file($request->file('file')->path())));

        $foundPhones = Client::whereIn('phone', $phones)->get();

        if ($foundPhones->count() > 0) {
            throw ValidationException::withMessages([
                'file' => 'Номера телефоно которые есть в сис-ме' .
                    PHP_EOL .
                    $foundPhones->implode('phone', ',')
            ]);
        }
        foreach ($phones as $phone) {
            Client::create([
                'phone' => $phone
            ]);
        }

        Toast::info('Новые клиенты успешно загружены');
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::columns([
                PercentageFeedbackClients::class,
                DynamicsInterviewedClients::class
            ]),
            Layout::tabs([
                'Загрузка новых телефонов' => [
                    Layout::rows([
                        Input::make('file')
                            ->type('file')
                            ->required()
                            ->help('Необходимо загрузить файл csv с телефонами')
                            ->title('Файл с телефонами в формате csv'),
                        Button::make('Загрузить')
                            ->confirm('Вы уверены?')
                            ->type(Color::PRIMARY())
                            ->method('importClientsByPhone')
                    ]),
                ],
                'Отчет по клиентам' => [
                    Layout::rows([
                        Button::make('Скачать')
                            ->method('exportClients')
                            ->rawClick()
                    ])
                ]
            ])
        ];
    }
}
