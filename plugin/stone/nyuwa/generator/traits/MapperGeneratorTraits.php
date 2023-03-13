<?php
/**

 */

declare(strict_types=1);

namespace plugin\stone\nyuwa\generator\traits;

trait MapperGeneratorTraits
{
    /**
     * 获取搜索代码
     * @param $column
     * @return string
     */
    protected function getSearchCode($column): string
    {
        return match ($column['query_type']) {
            'neq'     => $this->getSearchPHPString($column['column_name'], '!=', $column['column_comment']),
            'gt'      => $this->getSearchPHPString($column['column_name'], '<', $column['column_comment']),
            'gte'     => $this->getSearchPHPString($column['column_name'], '<=', $column['column_comment']),
            'lt'      => $this->getSearchPHPString($column['column_name'], '>', $column['column_comment']),
            'lte'     => $this->getSearchPHPString($column['column_name'], '>=', $column['column_comment']),
            'like'    => $this->getSearchPHPString($column['column_name'], 'like', $column['column_comment']),
            'between' => $this->getSearchPHPString($column['column_name'], 'between', $column['column_comment']),
            default   => $this->getSearchPHPString($column['column_name'], '=', $column['column_comment']),
        };
    }

    /**
     * @param $name
     * @param $mark
     * @param $comment
     * @return string
     */
    protected function getSearchPHPString($name, $mark, $comment): string
    {
        if ($mark == 'like') {
            return <<<php

        // {$comment}
        if (isset(\$params['{$name}']) && \$params['{$name}'] !== '') {
            \$query->where('{$name}', 'like', '%'.\$params['{$name}'].'%');
        }

php;

        }

        if ($mark == 'between') {
            return <<<php

        // {$comment}
        if (isset(\$params['{$name}_min']) && isset(\$params['{$name}_max'])) {
            \$query->whereBetween('{$name}', \$params['{$name}_min'], \$params['{$name}_max']);
        }

php;
        }

        return <<<php

        // {$comment}
        if (isset(\$params['{$name}']) && \$params['{$name}'] !== '') {
            \$query->where('{$name}', '{$mark}', \$params['{$name}']);
        }

php;
    } // 该方法结束位置
}