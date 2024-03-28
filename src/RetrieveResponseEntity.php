<?php

namespace Jukit\ShardingEnhancer;

use Closure;
use Illuminate\Contracts\Pagination\Paginator;

/**
 * 分页响应结构体
 */
class RetrieveResponseEntity
{

    /**
     * @desc 检索列表迭代器
     * @param Paginator $paginator
     * @param Closure|null $handle
     * @return array
     */
    public static function retrieveIterator(Paginator $paginator, Closure $handle = null): array
    {
        if (is_null($handle)) {
            return static::retrieve($paginator);
        }

        $data = [];

        // 自定义处理方式
        foreach ($paginator->getIterator() as $key => $value) {
            $handleResult = $handle($value, (int)$key);
            if (!is_null($handleResult)) {
                $data[] = $handleResult;
            }
        }

        return static::retrieveStructure($paginator->total(), $paginator->currentPage(), $paginator->perPage(), $data);
    }

    /**
     * @desc 检索列表结构
     * @param Paginator $paginator
     * @return array
     */
    public static function retrieve(Paginator $paginator): array
    {
        $data = [];

        foreach ($paginator->items() as $item) {
            $data[] = $item->toArray();
        }

        return static::retrieveStructure($paginator->total(), $paginator->currentPage(), $paginator->perPage(), $data);
    }

    /**
     * @desc 检索结构
     * @param int $total
     * @param int $currentPage
     * @param int $perPage
     * @param array $data
     * @return array
     */
    public static function retrieveStructure(int $total, int $currentPage, int $perPage, array $data): array
    {
        return [
            'total'       => $total,
            'currentPage' => $currentPage,
            'perPage'     => $perPage,
            'data'        => $data,
        ];
    }
}
