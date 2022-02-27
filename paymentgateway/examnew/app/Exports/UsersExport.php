<?php

namespace App\Exports;
use App\Models\Question;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class UsersExport implements FromCollection,WithHeadings,WithEvents
{
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
   
                $event->sheet->getDelegate()->getStyle('A1:AC1')
                                ->getFont()
                                ->setBold(true);
   
            },
        ];
    }

	public function headings():array{
        return[
            'Id',
            'hallticket',
            'name',
            'parentname',
            'email',
            'mobile',
            'dob',
            'age',
            'qualification',
            'id_proof',
            'address',
            'agegroup',
            'sign',
            'state',
            'district',
            'zone',
            'pincode',
            'city_village',
            'tehsil',
            'country',
            'post',
            'password',
            'success',
            'registermode',
            'created_at',
            'updated_at',
            'DistrictName',
            'StateName',
            'country_name'

        ];
    } 
    public function collection()
    {
        $users = DB::table('users_online')
            ->leftjoin('district', 'district.DistCode', '=', 'users_online.district')
            ->leftjoin('state', 'state.StCode', '=', 'users_online.state')
            ->leftjoin('countries', 'countries.country_code', '=', 'users_online.country')
            ->select('users_online.*', 'district.DistrictName', 'state.StateName', 'countries.country_name')
            ->get();
        
        return $users;
    }
}
