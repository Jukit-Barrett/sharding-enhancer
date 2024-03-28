<?php

namespace Jukit\ShardingEnhancer;

/**
 * 分区处理
 */
trait Positioner
{

    /**
     * @var int 分表因子
     */
    protected $factor = -1;

    /**
     * @var int 最大分表数
     */
//    protected $shardMaxCount = 64;

    /**
     * @var \int[][] 分表配置
     */
//    protected $shardConfig = [
//        [
//            'partition' => 8,
//            'low' => 0,
//            'high' => 7,
//        ],
//        [
//            'partition' => 16,
//            'low' => 8,
//            'high' => 15,
//        ],
//        [
//            'partition' => 24,
//            'low' => 16,
//            'high' => 23,
//        ],
//        [
//            'partition' => 32,
//            'low' => 0,
//            'high' => 31,
//        ],
//        [
//            'partition' => 40,
//            'low' => 32,
//            'high' => 39,
//        ],
//        [
//            'partition' => 48,
//            'low' => 40,
//            'high' => 47,
//        ],
//        [
//            'partition' => 56,
//            'low' => 48,
//            'high' => 55,
//        ],
//        [
//            'partition' => 64,
//            'low' => 32,
//            'high' => 63,
//        ],
//    ];


    /**
     * @desc 设置分表因子
     * @param int $factor
     * @return $this
     */
    public function setFactor(int $factor)
    {
        $this->factor = $factor;

        return $this;
    }

    /**
     * @desc 获取分表因子
     * @return int
     */
    public function getFactor(): int
    {
        return $this->factor;
    }

    /**
     * @desc 获取最大分表数
     * @return int
     */
    abstract public function getShardMaxCount(): int;


    /**
     * @desc 获取分表配置
     * @return array
     */
    abstract public function getShardConfig(): array;

    /**
     * @desc 获取当前分表数
     * @return int
     */
    public function getShardingCount(): int
    {
        return count($this->getshardConfig());
    }

    /**
     * @desc 匹配分区因子
     * @return int
     */
    public function getMatchFactor(): int
    {
        $factor = -1;

        if ($this->getShardingCount() > 0) {
            if (preg_match('/_(\d+)$/', $this->getTable(), $matches)) {
                if (isset($matches[1])) {
                    $factor = (int)$matches[1];
                    $factor = $factor - 1;
                }
            }
        }

        return $factor;
    }


    /**
     * @desc 获取分表实例
     * @return Partition
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getShardingInstance(): Partition
    {
        app()->singletonIf(Partition::class);

        return app()->make(Partition::class);
    }


    /**
     * @desc 定位
     * @param int $id
     */
    public function position(int $id)
    {
        $shardMaxCount = $this->getShardMaxCount();

        $shardConfig = $this->getShardConfig();

        $sharding = $this->getShardingInstance();

        $location = $sharding->setPartitionMaxCount($shardMaxCount)->setPartitionFactor($id)
            ->setPartitionConfig($shardConfig)->calculatePartition();

        return $location;
    }

    /**
     * @desc 分表
     * @return $this
     */
    public function sharding()
    {
        $location = $this->position($this->getFactor());

        $tableName = $this->getTable();

        $tableName = preg_replace('/_\d+$/', '', $tableName);

        $tableName = $tableName . "_{$location}";

        $this->setTable($tableName);

        return $this;
    }

}
