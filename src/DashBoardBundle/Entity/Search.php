<?php

namespace DashBoardBundle\Entity;

/**
 * Search
 */
class Search
{
    /**
     * @var string
     */
    private $search;

    /**
     * Set search
     *
     * @param string $search
     *
     * @return Search
     */
    public function setSearch($search)
    {
        $this->search = $search;

        return $this;
    }

    /**
     * Get search
     *
     * @return string
     */
    public function getSearch()
    {
        return $this->search;
    }
}

