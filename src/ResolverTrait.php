<?php

namespace Jukit\ShardingEnhancer;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use InvalidArgumentException;

trait ResolverTrait
{

    /**
     * 关联配置
     * @return array
     */
    public function relationConfig(): array
    {
        return [];
    }

    /**
     * 关联解析器
     * @param Builder $query
     * @param array $relations 关联配置
     * @return Builder
     */
    public function relationResolver(Builder $query, array $relations): Builder
    {
        $relationConfigs = $this->relationConfig();

        $filterConfigs = [];

        $nameSets = [];

        foreach ($relations as $relationName => $relationParam) {
            //
            //$name = substr($relationName, 5);
            $name = $relationName;

            if (is_array($relationConfigs[$name]) && isset($relationConfigs[$name]["relation"]) && isset($relationConfigs[$name]["call"])) {
                // New
                if ($relationConfigs[$name]["call"] instanceof Closure) {
                    // 回调
                    $relationConfig = $relationConfigs[$name]["call"];
                    // 关联名称
                    $name = $relationConfigs[$name]["relation"];

                    if (isset($nameSets[$name])) {
                        throw new InvalidArgumentException("关联配置引用重复: {$name}");
                    }
                    // 记录关联名称
                    $nameSets[$name] = true;
                }
            }

            if (is_array($relationParam) && !empty($relationParam) && $relationConfig instanceof Closure) {
                //
                $filterConfigs[$name] = $relationConfig($relationParam);
            }
        }

        if (empty(!$filterConfigs)) {
            $query->with($filterConfigs);
        }

        return $query;
    }

    /**
     * 排序配置
     * @return array[]
     */
    public function sortConfig(): array
    {
        return [
            "-id" => [
                "table" => "",
                "column" => "id",
                "direction" => "DESC",
            ],
            "id" => [
                "table" => "",
                "column" => "id",
                "direction" => "ASC",
            ],
        ];
    }

    /**
     * 排序解析器
     * @param Builder $query
     * @param string $sortKey
     * @return Builder
     */
    public function sortResolver(Builder $query, string $sortKey = ""): Builder
    {
        $sortConfigs = $this->sortConfig();

        if (isset($sortConfigs[$sortKey])) {
            $config = $sortConfigs[$sortKey];
            if (empty($config["table"])) {
                $query->orderByRaw("{$config["column"]} {$config["direction"]}");
            } else {
                $tableColumn = $config["table"] . "." . $config["column"];
                $query->orderByRaw("{$tableColumn} {$config["direction"]}");
            }
        }

        return $query;
    }


}
