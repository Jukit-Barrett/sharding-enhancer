<?php

namespace Jukit\ShardingEnhancer\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * 模型契约
 */
interface ModelContract
{
    /**
     * @desc 设置模型
     * @param Model $model
     * @return $this
     */
    public function setModel(Model $model);

    /**
     * @desc 获取模型
     * @return Model
     */
    public function getModel(): Model;
}
