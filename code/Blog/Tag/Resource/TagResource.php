<?php

namespace Blog\Tag\Resource;

class TagResource extends \App\Resource\Resource
{
    protected string $_table = 'tag';

    public function selectTags(): self
    {
        $this->_query
            ->select(['tag.tag_id', 'tag.code', 'tag.label'])
            ->from($this->_table)
            ->rand();
        return $this;
    }

    public function filterTags(array $tagIds): self
    {
        $tagIdsStr = implode(',', $tagIds);
        $this->_query->where(["tag.tag_id IN ($tagIdsStr)"]);
        return $this;
    }
}
