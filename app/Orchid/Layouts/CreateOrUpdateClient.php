<?php

namespace App\Orchid\Layouts;

use App\Models\Service;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class CreateOrUpdateClient extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        $isClientExist = is_null($this->query->getContent('client')) === false;
        return [
            Input::make('client.id')->hidden(),
            Input::make('client.phone')->title('Телефон')->required()->disabled($isClientExist),
            Group::make([
                Input::make('client.name')->title('Имя')->required(),
                Input::make('client.last_name')->title('Фамилия')->required(),
            ]),
            Input::make('client.email')->title('Email')->type('email')->required(),
            DateTimer::make('client.birthday')->format('Y-m-d')->title('Дата рождения')->required(),
            Relation::make('client.service_id')->fromModel(Service::class, 'name')->title('Тип услуги')->required(),
            Select::make('client.assessment')->required()->options([
                'Отлично' => 'Отлично',
                'Хорошо' => 'Хорошо',
                'Средне' => 'Средне',
                'Ужасно' => 'Ужасно',
            ])->help('Реакция на услугу')->empty('Нет оценки', 'Нет оценки'),
        ];
    }
}
