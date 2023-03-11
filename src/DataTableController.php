<?php

namespace Designbycode\Datatables;

use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

abstract class DataTableController extends Controller implements Builder
{
    protected bool $allowCreation = true;

    protected bool $allowDeletion = true;

    protected string|null $tableName = null;

    protected mixed $builder;

    abstract public function builder();

    public function __construct()
    {
        $builder = $this->builder();

        if (! $builder instanceof Builder) {
            throw new Exception('Not an instance of Builder');
        }

        $this->builder = $builder;
    }

    /**
     * Entry point for application
     */
    public function index(Request $request)
    {
        return response()->json($this->getResponseData($request));
    }

    /**
     * @return array[]
     */
    public function getResponseData(Request $request): array
    {
        return [

            'allow' => [
                'creation' => $this->allowCreation,
                'deletion' => $this->allowDeletion,
            ],
            'custom_columns' => $this->getCustomColumnNames(),
            'table' => $this->tableName ?? $this->builder->getModel()->getTable(),
            'displayable' => array_values($this->getDisplayableColumns()),
            'updatable' => array_values($this->getUpdatableColumns()),
            'records' => $this->getRecords($request),

        ];
    }

    /**
     * Update a record in column
     */
    public function update($id, Request $request): void
    {
        $this->builder->find($id)->update($request->only($this->getUpdatableColumns()));
    }

    /**
     * Store a new column in database
     *
     * @return void
     */
    public function store(Request $request)
    {
        if (! $this->allowCreation) {
            return;
        }

        $this->builder->create($request->only($this->getUpdatableColumns()));
    }

    /**
     * Delete records.
     */
    public function destroy($ids, Request $request): void
    {
        if (! $this->allowDeletion) {
            return;
        }

        $this->builder->whereIn('id', explode(',', $ids))->delete();
    }

    /**
     * Override default column names.
     */
    public function getCustomColumnNames(): array
    {
        return [];
    }

    /**
     * Get list of items in column that can be displayed.
     */
    public function getDisplayableColumns(): array
    {
        return array_diff($this->getDisplayColumnNames(), $this->builder->getModel()->getHidden());
    }

    /**
     * Get item in column that is updatable.
     */
    public function getUpdatableColumns(): array
    {
        return $this->getDisplayableColumns();
    }

    /**
     * Dynamic default column names.
     */
    private function getDisplayColumnNames(): array
    {
        return Schema::getColumnListing($this->builder->getModel()->getTable());
    }

    /**
     * Get list of data from model
     */
    public function getRecords(Request $request): Collection
    {
        $builder = $this->builder;

        if ($this->hasSearchQuery($request)) {
            $this->buildSearch($builder, $request);
        }

        try {
            return $this->builder->limit($request->limit)->orderBy('id', 'asc')->get($this->getDisplayableColumns());
        } catch (QueryException $e) {
            return new Collection([]);
        }
    }

    /**
     * Check if search parameters are present.
     */
    private function hasSearchQuery(Request $request): bool
    {
        return count(array_filter($request->only('column', 'operator', 'value'))) === 3;
    }

    /**
     * Build search form resolvedQueryParts.
     */
    private function buildSearch(Builder $builder, Request $request): Builder
    {
        $queryParts = $this->resolveQueryParts($request->operator, $request->value);

        return $builder->where($request->column, $queryParts['operator'], $queryParts['value']);
    }

    /**
     * Query part for search builder.
     */
    private function resolveQueryParts($operator, $value): mixed
    {
        return Arr::get([
            'equals' => [
                'operator' => '=',
                'value' => $value,
            ],
            'contains' => [
                'operator' => 'LIKE',
                'value' => "%{$value}%",
            ],
            'starts_with' => [
                'operator' => 'LIKE',
                'value' => "{$value}%",
            ],
            'ends_with' => [
                'operator' => 'LIKE',
                'value' => "%{$value}",
            ],
            'greater_than' => [
                'operator' => '>',
                'value' => $value,
            ],
            'greater_or_equal_than' => [
                'operator' => '>=',
                'value' => $value,
            ],
            'less_than' => [
                'operator' => '<',
                'value' => $value,
            ],
            'less_or_equal_than' => [
                'operator' => '<=',
                'value' => $value,
            ],
        ], $operator);
    }
}
