<?php

namespace App\Listings;

use App\Exceptions\NotAModelClassException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TableListing
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var Builder
     */
    protected $query;

    /**
     * @var int
     */
    protected $currentPage;

    /**
     * @var int
     */
    protected $perPage;

    /**
     * @var string
     */
    protected $pageColumnName = 'page';

    /**
     * @var bool
     */
    protected $hasPagination = false;

    /**
     * @var bool
     */
    protected $modelHasTranslations = false;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var string
     */
    protected $orderBy;

    /**
     * @var string
     */
    protected $orderDirection = 'asc';

    /**
     * @var string
     */
    protected $search;

    /**
     * @var array
     */
    protected $searchIn = [];

    /**
     * @var bool
     */
    protected $hasRelation = false;

    /**
     * @var string
     */
    protected $relation = '';


    /**
     * @param string|Model $model
     * @return TableListing
     * @throws NotAModelClassException
     */
    public static function create($model): self {
        return (new static)->setModel($model);
    }

    /**
     * @param string|Model $model
     * @return $this
     * @throws NotAModelClassException
     */
    private function setModel($model): self {
        if (is_string($model)) {
            $model = app($model);
        }

        if (!is_a($model, Model::class)) {
            throw new NotAModelClassException("Only eloquent models are accepted");
        }

        $this->model = $model;
        $this->init();

        return $this;
    }

    private function init(): void {
        $this->query = $this->model->newQuery();
        $this->orderBy = $this->model->getKeyName();
    }

    public function withRelation(string $model): self {

        $this->hasRelation = true;
        $this->relation = $model;

        return $this;

    }

    public function processAndGet(Request $request, array $columns = ['*'], array $searchIn = []) {
        $this->setOrdering($request->input('orderBy', $this->model->getKeyName()), $request->input('orderDirection', 'asc'));
        $this->setSearch($request->input('search', ''), $searchIn);
        $this->setPaginate($request->input('page', 1), $request->input('per_page', 10));

        if($this->hasRelation) {
            $this->query->with($this->relation);
        }

        return $this->getResults($columns);
    }

    private function setOrdering(string $orderBy, string $orderDirection = 'asc'): void {
        $this->orderBy = $orderBy;
        $this->orderDirection = $orderDirection;
    }

    private function setSearch($search, array $searchIn = []): void {
        $this->search = $search;
        $this->searchIn = $searchIn;
    }

    private function setPaginate(string $pageNumber, string $perPage): void {
        $this->hasPagination = true;
        $this->perPage = $perPage;
        $this->currentPage = $pageNumber;
    }

    /**
     * @param $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|Builder[]|Collection
     */
    private function getResults($columns) {
        $this->buildSearch();
        $this->buildOrdering();
        return $this->paginateWithResult($columns);

    }

    private function buildSearch(): void {

        // We don't need to search if we not provide and column to search in
        if (!is_array($this->searchIn) || count($this->searchIn) === 0) {
            return;
        }

        // We don't need to search if we put nothing to searcher
        $search = trim($this->search);
        if ($search === '') {
            return;
        }

        $searchIn = collect($this->searchIn);

        $this->query->where(function (Builder $query) use ($searchIn, $search) {
            $searchIn->each(function ($column) use ($search, $query) {
                $this->whereLike($query, $column, $search);
            });
        });
    }

    private function whereLike(Builder $query, string $column, string $value): void {
        $query->orWhere($column, 'like', '%'. $value . '%');
    }

    private function buildOrdering(): void {
        $this->query->orderBy($this->orderBy, $this->orderDirection);
    }

    /**
     * @param $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|Builder[]|Collection
     */
    private function paginateWithResult($columns) {
        if ($this->hasPagination) {
            return $this->query->paginate($this->perPage, $columns, $this->pageColumnName, $this->currentPage);
        }
        else {
            return $this->query->get($columns);
        }
    }
}
