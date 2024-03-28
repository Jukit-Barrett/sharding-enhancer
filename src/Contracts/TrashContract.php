<?php

namespace Jukit\ShardingEnhancer\Contracts;

interface TrashContract
{
    /**
     * @desc 恢复软删除的数据
     * @param int $id
     * @return bool
     */
    public function restore(int $id): bool;
}
