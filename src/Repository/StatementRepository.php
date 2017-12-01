<?php

namespace SONFin\Repository;

use Illuminate\Database\Eloquent\Collection;
use SONFin\Models\BillPay;
use SONFin\Models\BillReceive;

class StatementRepository implements StatementRepositoryInterface
{

    public function all(string $dateStart, string $dateEnd, int $userId): array
    {
       // Select form bill_pays left join category_costs
        $billPays = BillPay::query()
            ->selectRaw('bill_pays.*, category_costs.name as category_name')
            ->leftJoin('category_costs', 'category_costs.id', 'bill_pays.category_cost_id')
            ->whereBetween('date_launch', [$dateStart, $dateEnd])
            ->where('bill_pays.user_id', $userId)
            ->get();

        $billReceives = BillReceive::query()
            ->whereBetween('date_launch', [$dateStart, $dateEnd])
            ->where('user_id', $userId)
            ->get();
        //Collection [0 => BillPay, 1 => BillPay...]
        //Collection [0 => BillReceive, 1 => BillReceives...]
        $collection = new Collection(array_merge_recursive($billPays->toArray()), array_merge_recursive($billReceives->toArray()));
        $statement = $collection->sortByDesc('date_launch');

        return [
          'statements' => $statement,
          'total_pays' => $billPays->sum('value'),
          'total_receives' => $billReceives->sum('value')
        ];
    }
}