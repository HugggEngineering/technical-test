<?php
namespace App\Repositories;
 
use App\Transaction;
use Carbon\Carbon;

 
class TransactionRepository extends Repository {

    function model() {
        return 'App\Transaction';
    }

	public function paginate( $where = [], $sort = null, $limit = 20 ) {

		$transactions = $this->model->leftJoin('hugs', function($query){
                            $query->on('transactions.hug_id', '=', 'hugs.id')
                                ->where('hugs.error', '=', '0');
                        })
                        ->leftJoin('super_urls', function($query) {
                            $query->on('super_urls.id', '=', 'transactions.super_url_id');
                        })
                        ->leftJoin('redemptions', function($query){
                            $query->on('redemptions.hug_id', '=', 'transactions.hug_id');
                        })
                        ->leftJoin('prices', function($query){
                            // Look
                            // If you had
                            // One join
                            // Or one query
                            // To seize all the data you ever wanted
                            // In one query builder
                            // Would you capture it
                            // Or split the query?
                            //
                            // Yo
                            // Her palms are sweaty, knees weak, standing desk is ready
                            // There's failing tests already, errors in the geTTY
                            // She's nervous, but on the surface she looks calm and ready
                            // To drop statements, but she keeps forgetting,
                            // What code she wrote, laravel's not got much bloat,
                            // She opens her ide, but eloquent's error-ing
                            // She's chokin', boo, the codes fu'kin' poo
                            // The query builder's maxed out, broken, nuuuu!
                            //
                            // ... Yeah so if you want to do a complex expression in a
                            // join's "ON" statement you kind of can't. And on top of that
                            // there is no OnRaw(). So instead you have to pass something
                            // that will always be true to on(), hence the insane 1 = 1,
                            // then extend it with a whereRaw() for your actual query
                            $query->on(\DB::raw('1'), '=', \DB::raw('1'))
                                ->whereRaw('(' .
                                    '(hugs.purchase_id IS NOT NULL AND prices.id = hugs.purchase_id) OR ' .
                                    '(super_urls.purchase_id IS NOT NULL AND prices.id = super_urls.purchase_id)' .
                                ')');
                        })
                        ->leftJoin('brands', function($query){
                            $query->on('prices.brand_id', '=', 'brands.id');
                        })
                        ->leftJoin('users as u2', function($query){
                            $query->on(\DB::raw('1'), '=', \DB::raw('1'))
                                ->whereRaw('(' .
                                    '(hugs.sender_id IS NOT NULL AND u2.id = hugs.sender_id) OR ' .
                                    '(super_urls.sender_id IS NOT NULL AND u2.id = super_urls.sender_id)' .
                                ')');
                        })
                        ->leftJoin('users as u', function($query){
                            $query->on('hugs.receiver_id', '=', 'u.id');
                        })
                        ->leftJoin('brands as b2', function($query) {
                            $query->on('redemptions.brand_id', '=', 'b2.id');
                        })
                        ->select('transactions.id', 'transactions.created_at as purchased_at', 'transactions.refunded', 'transactions.hug_id','transactions.retail', 'transactions.handling_fee',
                                'transactions.super_url_id',
                                'transactions.merchant_fee', 'transactions.merchant_vat', 'transactions.stripe_id')
                        ->addSelect(\DB::raw('date(redemptions.created_at) as redeemed_at_date, time(redemptions.created_at) as redeemed_at_time'))
                        ->addSelect(\DB::raw('CONCAT(u2.first_name, \' \', u2.surname) as sender, u2.phone_number as s_phone_number '))
                        ->addSelect(\DB::raw('CONCAT(u.first_name, \' \', u.surname) as receiver, u.phone_number as r_phone_number'))
                        ->addSelect('brands.name as b_name')
                        ->addSelect('brands.consolidated')
                        ->addSelect('b2.name as redeemed_at')
                        ->addSelect('redemptions.code_id as code_used')
                        ->when($where, function($query) use($where) {
                        	foreach ($where as $column => $value) {
                                //TODO: Make array more dynamic rather then hardcoding date vars
                                if(!!$value) {
                                    if ($column == 'brands.id') {
                                        $query->where(function($query) use ($value) {
                                            $query->where('brands.id', '=', $value);
                                            $query->orWhere('b2.id', '=', $value);
                                        });
                                    } else if($column == 'transactions.created_at_start') {
                                        $date = new Carbon($value);
                                        $query->where('transactions.created_at', '>=', $date);
                                    } else if ($column == 'transactions.created_at_end') {
                                        $date = new Carbon($value);
                                        $query->where('transactions.created_at', '<=', $date);
                                    } else {
                                        $query->where($column, 'like', '%' . $value . '%');
                                    }
                                }
                        	}
                        	return $query;
                        })
                        ->when($sort, function($query) use($sort){
                            return $query->orderBy($sort->by, $sort->order);
                        })
                        ->simplePaginate($limit);

        return $transactions;	
	}


    public function days( $days = 30) {

        $transactions = $this->model->leftJoin('hugs', function($query){
                            $query->on('transactions.hug_id', '=', 'hugs.id')
                                ->where('hugs.error', '=', '0');
                        })
                        ->leftJoin('super_urls', function($query) {
                            $query->on('super_urls.id', '=', 'transactions.super_url_id');
                        })
                        ->leftJoin('redemptions', function($query){
                            $query->on('redemptions.hug_id', '=', 'transactions.hug_id');
                        })
                        ->leftJoin('prices', function($query){
                            $query->on(\DB::raw('1'), '=', \DB::raw('1'))
                                ->whereRaw('(' .
                                    '(hugs.purchase_id IS NOT NULL AND prices.id = hugs.purchase_id) OR ' .
                                    '(super_urls.purchase_id IS NOT NULL AND prices.id = super_urls.purchase_id)' .
                                ')');
                        })
                        ->leftJoin('brands', function($query){
                            $query->on('prices.brand_id', '=', 'brands.id');
                        })
                        ->leftJoin('users as u2', function($query){
                            $query->on(\DB::raw('1'), '=', \DB::raw('1'))
                                ->whereRaw('(' .
                                    '(hugs.sender_id IS NOT NULL AND u2.id = hugs.sender_id) OR ' .
                                    '(super_urls.sender_id IS NOT NULL AND u2.id = super_urls.sender_id)' .
                                ')');
                        })
                        ->leftJoin('users as u', function($query){
                            $query->on('hugs.receiver_id', '=', 'u.id');
                        })
                        ->select('transactions.id', 'transactions.created_at as purchased_at', 'transactions.refunded', 'transactions.hug_id','transactions.retail', 'transactions.handling_fee',
                                'transactions.super_url_id',
                                'transactions.merchant_fee', 'transactions.merchant_vat', 'transactions.stripe_id')
                        ->addSelect(\DB::raw('date(redemptions.created_at) as redeemed_at_date, time(redemptions.created_at) as redeemed_at_time'))
                        ->addSelect(\DB::raw('CONCAT(u2.first_name, \' \', u2.surname) as sender, u2.phone_number as s_phone_number '))
                        ->addSelect(\DB::raw('CONCAT(u.first_name, \' \', u.surname) as receiver, u.phone_number as r_phone_number'))
                        ->addSelect('brands.name as b_name')
                        ->addSelect('redemptions.code_id as code_used')
                        ->groupBy('transactions.id')
                        // ->when($where, function($query) use($where) {
                        //     foreach ($where as $column => $value) {
                        //         //TODO: Make array more dynamic rather then hardcoding date vars
                        //         if(!!$value) {
                        //             if($column == 'transactions.created_at_start') {
                        //                 $date = new Carbon($value);
                        //                 $query->where('transactions.created_at', '>=', $date);
                        //             } else if ($column == 'transactions.created_at_end') {
                        //                 $date = new Carbon($value);
                        //                 $query->where('transactions.created_at', '<=', $date);
                        //             } else {
                        //                 $query->where($column, 'like', '%' . $value . '%');
                        //             }
                        //         }
                        //     }
                        //     return $query;
                        // })
                        // ->when($sort, function($query) use($sort){
                        //     return $query->orderBy($sort->by, $sort->order);
                        // })
                        ->get()
                        ->groupBy(function($transaction) {
                            return Carbon::parse($transaction->created_at)->format('d');
                        });

        return $transactions;   
    }

    /**
     * Find with array of variables or just pass in an ID
     * @param  Integer/Array $where Can be array or integerr
     * @return Collection    Will be Laravel collection
     */
    public function findOne( $where ) {
        if(!is_array($where)) {
            $where = [
                'id' => $where
            ];
        }

        $row = $this->model
                        ->where($where)
                        ->with('hug')
                        ->with('hug.receiver')
                        ->with('hug.sender')
                        ->firstOrFail();

        return $row;
    }

	public function all( $where = [], $sort = null, $limit = 20 ) {

		$transactions = Transaction::leftJoin('hugs', function($query){
                            $query->on('transactions.hug_id', '=', 'hugs.id')
                                ->where('hugs.error', '=', '0');
                        })
                        ->leftJoin('super_urls', function($query) {
                            $query->on('super_urls.id', '=', 'transactions.super_url_id');
                        })
                        ->leftJoin('redemptions', function($query){
                            $query->on('redemptions.hug_id', '=', 'transactions.hug_id');
                        })
                        ->leftJoin('prices', function($query){
                            $query->on(\DB::raw('1'), '=', \DB::raw('1'))
                                ->whereRaw('(' .
                                    '(hugs.purchase_id IS NOT NULL AND prices.id = hugs.purchase_id) OR ' .
                                    '(super_urls.purchase_id IS NOT NULL AND prices.id = super_urls.purchase_id)' .
                                ')');
                        })
                        ->leftJoin('brands', function($query){
                            $query->on('prices.brand_id', '=', 'brands.id');
                        })
                        ->leftJoin('users as u2', function($query){
                            $query->on(\DB::raw('1'), '=', \DB::raw('1'))
                                ->whereRaw('(' .
                                    '(hugs.sender_id IS NOT NULL AND u2.id = hugs.sender_id) OR ' .
                                    '(super_urls.sender_id IS NOT NULL AND u2.id = super_urls.sender_id)' .
                                ')');
                        })
                        ->leftJoin('users as u', function($query){
                            $query->on('hugs.receiver_id', '=', 'u.id');
                        })
                        ->leftJoin('brands as b2', function($query) {
                            $query->on('redemptions.brand_id', '=', 'b2.id');
                        })
                        ->select('transactions.id', 'transactions.created_at as purchased_at', 'transactions.refunded', 'transactions.hug_id','transactions.retail', 'transactions.handling_fee',
                                    'transactions.super_url_id',
                                    'transactions.merchant_fee', 'transactions.merchant_vat', 'transactions.stripe_id')
                        // ->addSelect(\DB::raw('SUM(transactions.retail) as totalRetail'))
                        ->addSelect(\DB::raw('date(redemptions.created_at) as redeemed_at_date, time(redemptions.created_at) as redeemed_at_time'))
                        ->addSelect(\DB::raw('CONCAT(u2.first_name, \' \', u2.surname) as sender, u2.phone_number as s_phone_number '))
                        ->addSelect(\DB::raw('CONCAT(u.first_name, \' \', u.surname) as receiver, u.phone_number as r_phone_number'))
                        ->addSelect('brands.name as b_name')
                        ->addSelect('redemptions.code_id as code_used')
                        ->addSelect('bulk_send')
                        ->addSelect(\DB::raw('CASE WHEN transactions.tag = "imessage" THEN "1" ELSE "0" END AS imessage'))                        
                        ->when($where, function($query) use($where) {
                            foreach ($where as $column => $value) {
                                //TODO: Make array more dynamic rather then hardcoding date vars
                                if($value != '') {
                                    if ($column == 'brands.id') {
                                        $query->where(function($query) use ($value) {
                                            $query->where('brands.id', '=', $value);
                                            $query->orWhere('b2.id', '=', $value);
                                        });
                                    } elseif($column == 'transactions.created_at_start') {
                                        $query->where('transactions.created_at', '>=', new Carbon($value));
                                    } else if ($column == 'transactions.created_at_end') {
                                        $query->where('transactions.created_at', '<=', new Carbon($value));
                                    } else {
                                        $query->where($column, 'like', '%' . $value . '%');
                                    }
                                }
                            }
                            return $query;
                        })
                        ->groupBy('transactions.id')
                        ->when($sort, function($query) use($sort){
                            return $query->orderBy($sort->by, $sort->order);
                        })
                        ->get();

        return $transactions;
		
	}

    public function find($id, $columns = array('*')){

    }
 
    public function findBy($field, $value, $columns = array('*')){

    }
}
