<?php

namespace Jukit\ShardingEnhancer\Contracts;

use Closure;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryContract
{
    /**
     * @desc 增
     * @param array $data 数据
     */
    public function create(array $data);

    /**
     * @desc 查
     * @return LengthAwarePaginator
     */
    public function retrieve(RetrieveQueryContract $retrieveContract): LengthAwarePaginator;

    /**
     * @desc 改
     * @param int $id
     * @param array $attributes
     * @param Closure|null $before
     * @return bool
     */
    public function update(int $id, array $attributes, Closure $before = null): bool;

    /**
     * @desc 删
     * @param int $id 主键
     * @return bool|null
     */
    public function delete(int $id): ?bool;

    /**
     * @desc 信息
     * @param int $id
     * @param string[] $fields
     * @param string[] $relations
     * @param Closure|null $before
     * @return mixed
     */
    public function info(int $id, array $fields = ['id'], array $relations = ['with_' => true], Closure $before = null);

    /**
     * @desc 多个
     * @param array $ids
     * @param array $fields
     * @param array $relations
     * @param \Closure|null $before
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function more(array $ids, array $fields = ['id'], array $relations = [], Closure $before = null);
}
