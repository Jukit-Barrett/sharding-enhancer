<?php

namespace Jukit\ShardingEnhancer\Contracts;

/**
 * 控制器服务契约
 */
interface ControlServiceContract
{
    /**
     * @desc 列表
     * @param array $inputParams
     * @return mixed
     */
    public function index(array $inputParams);

    /**
     * @desc 保存
     * @param array $inputParams
     * @return mixed
     */
    public function store(array $inputParams);

    /**
     * @desc 信息
     * @param int $id
     * @return mixed
     */
    public function show(int $id);

    /**
     * @desc 更新
     * @param int $id
     * @param array $inputParams
     * @return mixed
     */
    public function update(int $id, array $inputParams);

    /**
     * @desc 删除
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id);
}
