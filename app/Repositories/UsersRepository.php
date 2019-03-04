<?php
namespace App\Repositories;

use App\User;

class UsersRepository
{
    function all( $where = [], $sort = null, $limit = 20) {
        $users  = User::leftJoin('hugs', function($query){
                $query->on('users.id', '=', 'hugs.sender_id')
                    ->where('hugs.error', '=', '0');
            })
            ->leftJoin('transactions', 'transactions.hug_id', '=', 'hugs.id')
            ->select('users.*')
            ->addSelect(\DB::raw('sum(transactions.retail + transactions.handling_fee) as spend'))
            /*
            ->when($type, function($query) use ($type){

                //Ok for legacy need to filter our names on user Types
                switch($type->id) {
                    case 0:
                        $query->where('first_name', '!=', '');
                        break;
                    default:
                        break;
                }

                return $query->where('type', (int)$type->id);
            })
            ->when($device_platform, function($query) use ($device_platform){
                return $query->where('device_platform', strtolower($device_platform->label));
            })
            ->when($search['phone_number'], function($query) use ($search){
                return $query->where('phone_number', 'like', '%' . (int)$search['phone_number'] . '%');
            })
            ->when( $marketing , function($query) use ($marketing){
                return $query->where('marketing', '=', (int)$marketing->id);
            })
            ->when($search['sent_hugs'], function($query) use ($search){
                return $query->has('sentHugs', '>=', (int)$search['sent_hugs']);
            })
            ->when($search['sent_hugs_lt'], function($query) use ($search){
                return $query->has('sentHugs', '<=', (int)$search['sent_hugs_lt']);
            })
            ->where('first_name', 'like', '%' . $search['first_name'] . '%')
            ->where('surname', 'like', '%' . $search['surname'] . '%')
            ->withCount('sentHugs')
            ->groupBy('users.id')
            ->when($sort, function($query) use($sort){
                return $query->orderBy($sort->by, $sort->order);
            })
            */
            // ->sum('prices.price')
            ->paginate(20);
            return $users;
    }
}
