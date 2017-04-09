<?php


namespace App\Http\Business;


use App\Http\DataAccess\UsersDao;

class UsersBusiness
{
    /**
     * UsersBusiness constructor.
     * @param UsersDao $dao
     */
    public function __construct(UsersDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param array $condition
     */
    public function index(array $condition = [], array $select_field = ['*'], array $relevance = [])
    {
        $relevance['tags'] = function ($query) use($condition) {
            return $query->select(['id', 'name', 'tag_id']);
        };
        
        return $this->dao->index($condition, $select_field, $relevance);
    }
}