<?php

namespace Jukit\ShardingEnhancer\Contracts;

use Closure;

interface RetrieveQueryContract
{
    /**
     * @desc 查询的列
     * @return array
     */
    public function columns(): array;

    /**
     * @desc 排序
     * @return array
     */
    public function sort(): string;

    /**
     * @desc 分页
     * @return array
     */
    public function paging(): array;

    /**
     * @desc 关系
     * @return array
     */
    public function relations(): array;

    /**
     * @desc 查询前
     * @return Closure|null
     */
    public function before(): ?Closure;

    /**
     * @desc 查询后
     * @return Closure|null
     */
    public function after(): ?Closure;

}
