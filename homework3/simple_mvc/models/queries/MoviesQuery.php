<?php

class MoviesQuery extends QueryBase
{
    private $_query_base = '
                    SELECT f.*
                    FROM film f
                    INNER JOIN film_actor fa ON f.film_id = fa.film_id
                    INNER JOIN actor a ON fa.actor_id = a.actor_id
                    INNER JOIN film_category fc ON f.film_id = fc.film_id
                    WHERE 1 = 1 ';
    private $_query_where = '';
    private $_query_group_by = "\n GROUP BY f.film_id";
    private $_query_title_search = '';
    private $_query_actor_search = '';
    private $_query_year_search = '';
    
    /**
     * @desc adds a title filter to the movie query
     * @param string $title
     */
    public function setTitleSearch($title)
    {
        $this->_query_title_search = $title;
    }
    
    /**
     * @desc searches by actor first and last name
     * @param string $actor
     */
    public function setActorSearch($actor)
    {
        $this->_query_actor_search = $actor;
    }
    
    /**
     * @desc searches for movies that were released during a specific year
     * @param int $year
     */
    public function setYearSearch($year)
    {
        $this->_query_year_search = $year;
    }
    
    /**
     * @desc executes the query, returns a list of movies
     * @return array
     */
    public function execute()
    {
        
        //figure out what query parameters we need to send to the DB
        $query_params = array();
        if(strlen($this->_query_title_search) > 0)
        {
            $this->_query_where .= "\n AND f.title LIKE :title ";
            $query_params['title'] = '%' . $this->_query_title_search . '%';
        }
        if(strlen($this->_query_year_search) > 0)
        {
            $this->_query_where .= "\n AND f.release_year = :year ";
            $query_params['year'] = $this->_query_year_search;
        }
        if(strlen($this->_query_actor_search) > 0)
        {
            $this->_query_where .= "\n AND (a.first_name LIKE :actorFirst OR a.last_name LIKE :actorLast) ";
            $query_params['actorFirst'] = $this->_query_actor_search . '%';
            $query_params['actorLast'] = $this->_query_actor_search . '%';
        }
        
        //Build the complete query
        $sql = $this->_query_base
                . $this->_query_where
                . $this->_query_group_by;
        
        //send the statement to the DB
        $statement = $this->db->prepare($sql);
        $statement->execute($query_params);
        $statement->setFetchMode(PDO::FETCH_OBJ);
        
        //convert results to array of objects and return
        $result = array();
        while($row = $statement->fetch())
        {
            $result[] = $row;
        }
        return $result;
    }
}