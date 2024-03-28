<?php

namespace Jukit\ShardingEnhancer\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface ResolverContract
{
    /*
    * return [
    *    'assignRemittance' => [
    *        'relation' => 'assignRemittance',
    *         'call'    => (function ($where){
    *                return function (HasMany $query) use ($where){
    *              };
    *          }),
    *     ],
    * ];
    */
    /**
     * @desc 关联配置
     * @return array
     */
    public function relationConfig(): array;

    /**
     * @desc 关联解析器
     * @param Builder $query 查询构造器
     * @param array $relations 关联配置
     * @return Builder
     */
    public function relationResolver(Builder $query, array $relations): Builder;

    /**
     * return [
     *     '-id' => [
     *     'orderTable' => $orderTable,
     *     'key'        => 'id',
     *     'value'      => 'desc',
     *   ],
     *   'id' => [
     *     'orderTable' => $orderTable,
     *     'key'        => 'id',
     *     'value'      => 'asc',
     *   ],
     * ];
     */
    /**
     * @desc 排序配置
     * @return array
     */
    public function sortConfig(): array;

    /**
     * @desc 排序解析器
     * @param Builder $query 查询构造器
     * @param string $sortKey 选择排序的键
     * @return Builder
     */
    public function sortResolver(Builder $query, string $sortKey = ''): Builder;
}
