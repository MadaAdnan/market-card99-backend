<?php

namespace App\Exports;

use App\Http\Resources\UserBalanceResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserBalanceExport implements FromCollection,WithHeadings
{
    /**
    * @return \LaravelIdea\Helper\App\Models\_IH_User_QB|EloquentBuilder|User
     */
    use Exportable;

    public function query()
    {
        return User::query()->append('balance');
    }

    public function collection()
    {
        return UserBalanceResource::collection(User::get()->sortBy('balance',SORT_REGULAR,true));
    }

    public function headings(): array
    {
        return [
            'الاسم',
            'البريد',
            'الرصيد',
        ];
    }
}
