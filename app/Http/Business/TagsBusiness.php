<?php

namespace App\Http\Business;


use App\Http\DataAccess\TagsDao;

class TagsBusiness
{
    /**
     * TagsBusiness constructor.
     * @param TagsDao $dao
     */
    public function __construct(TagsDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param array $condition
     * @param array $select_field
     * @param array $relevance
     * @return mixed
     */
    public function index(array $condition = [], array $select_field = ['*'], array $relevance = [])
    {
        return $this->dao->index($condition, $select_field, $relevance);
    }
}