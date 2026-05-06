<?php

namespace App\Filters;

class TransactionFilter
{
    /**
     * Apply the filters to the transaction query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function apply($query, $filters)
    {
        return $query
            ->when($filters['type'] ?? null, function ($q, $type) {
                return $q->where('type', $type);
            })
            ->when($filters['category_id'] ?? null, function ($q, $catId) {
                return $q->where('category_id', $catId);
            })
            ->when($filters['account_id'] ?? null, function ($q, $accId) {
                return $q->where('account_id', $accId);
            })
            ->when($filters['from_date'] ?? null, function ($q, $fromDate) {
                return $q->whereDate('date', '>=', $fromDate);
            })
            ->when($filters['to_date'] ?? null, function ($q, $toDate) {
                return $q->whereDate('date', '<=', $toDate);
            })
            ->when($filters['search'] ?? null, function ($q, $search) {
                return $q->where(function ($sub) use ($search) {
                    $sub->where('source_vendor', 'like', "%$search%")
                        ->orWhere('description', 'like', "%$search%");
                });
            });
    }
}
