<?php

namespace Jukit\ShardingEnhancer;

use RuntimeException;

/**
 * 分表类
 */
class Partition
{
    /**
     * @var array 分表配置
     */
    protected $partitionConfig = [];

    /**
     * @var int 分表计算因子
     */
    protected $partitionFactor = 0;

    /**
     * @var int 分表数
     */
    protected $partitionMaxCount = 0;

    /**
     * @desc 设置分表数
     * @param int $partitionMaxCount
     * @return $this
     */
    public function setPartitionMaxCount(int $partitionMaxCount)
    {
        if ($partitionMaxCount < 1) {
            throw new RuntimeException('请设置正确的分表数，值必须大于0!');
        }

        $this->partitionMaxCount = $partitionMaxCount;

        return $this;
    }

    /**
     * @desc 获取分表数
     * @return int
     */
    public function getPartitionMaxCount() : int
    {
        if ($this->partitionMaxCount < 1) {
            throw new RuntimeException('请设置正确的分表数，值必须大于0!');
        }

        return $this->partitionMaxCount;
    }

    /**
     * @desc 设置分表计算因子
     */
    public function setPartitionFactor(int $partitionFactor)
    {
        if ($partitionFactor < 0) {
            throw new RuntimeException('请设置正确的分表计算因子，值必须大于等于0!');
        }

        $this->partitionFactor = $partitionFactor;

        return $this;
    }

    /**
     * @desc 获取分表计算因子
     * @return int
     */
    public function getPartitionFactor()
    {
        if ($this->partitionFactor < 0) {
            throw new RuntimeException('请设置正确的分表计算因子，值必须大于等于0!');
        }

        return $this->partitionFactor;
    }

    /**
     * @desc 设置分表配置
     * @param array $partitionConfig
     * @return $this
     */
    public function setPartitionConfig(array $partitionConfig)
    {
        if (empty($partitionConfig)) {
            throw new RuntimeException('请在模型中设置分表表配置 Model::partitionConfig!');
        }

        foreach ($partitionConfig as $item) {
            if ( !isset($item['low'])) {
                throw new RuntimeException('key[low] Not Default.');
            }

            if ( !isset($item['high'])) {
                throw new RuntimeException('key[high] Not Default.');
            }
            if ( !isset($item['partition'])) {
                throw new RuntimeException('key[partition] Not Default.');
            }
        }

        $this->partitionConfig = $partitionConfig;

        return $this;
    }

    /**
     * @desc 获取分表配置
     * @return array
     */
    public function getPartitionConfig() : array
    {
        if (empty($this->partitionConfig)) {
            throw new RuntimeException('请设置分表配置!');
        }

        return $this->partitionConfig;
    }

    /**
     * @desc 计算分表
     * @return int
     */
    public function calculatePartition() : int
    {
        $pos = $this->calculateFactor();

        // 获取分表配置
        $partitionConfig = $this->getPartitionConfig();

        $partition = 0;

        foreach ($partitionConfig as $item) {
            if ($pos >= $item['low'] && $pos <= $item['high']) {
                $partition = $item['partition'];
                break;
            }
        }

        if ($partition < 1 || $partition > $this->getPartitionMaxCount()) {
            throw new RuntimeException('分表计算错误，请检查分表配置!');
        }

        return $partition;
    }

    /**
     * @desc 计算因子
     * @return int
     */
    public function calculateFactor() : int
    {
        $partitionMaxCount = $this->getPartitionMaxCount();

        $partitionFactor = $this->getPartitionFactor();

        return $partitionFactor % $partitionMaxCount;
    }
}
