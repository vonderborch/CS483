<?php

class MovieRepository
{
    private $_db;
    
    //Note: only one constructor is allowed
    public function __construct()
    {
        $this->_db = DatabaseFactory::getDefaultPdoObject();
    }
    
    public function getMovies($title = "", $actor = "", $year = "")
    {
        $query = new MoviesQuery();
        $query->setActorSearch($actor);
        $query->setTitleSearch($title);
        $query->setYearSearch($year);
        return $query->execute();
    }
    
    function getMovieDetails($id)
    {
        $query = "SELECT * "
                . "FROM film "
                . "WHERE film_id = :filmId"
                . "; "
                . "SELECT c.* "
                . "FROM film_category fc "
                . "INNER JOIN category c ON fc.category_id = c.category_id "
                . "WHERE fc.film_id = :filmId "
                . "; "
                . "SELECT a.* "
                . "FROM film_actor fa "
                . "INNER JOIN actor a ON fa.actor_id = a.actor_id "
                . "WHERE fa.film_id = :filmId "
                . ";";
        $statement = $this->_db->prepare($query);
        $statement->execute( array('filmId' => $id) );
        $statement->setFetchMode(PDO::FETCH_OBJ);
        
        $result = new Movie();
        
        //first result set (basic movie info)
        while($row = $statement->fetch())
        {
            $result->film_id = $row->film_id;
            $result->description = ucfirst(strtolower($row->description));
            $result->title = ucwords(strtolower($row->title));
            $result->release_year = $row->release_year;
        }
        
        //film categories
        $statement->nextRowset();
        while($row = $statement->fetch())
        {
            $result->categories[] = $row;
        }
        
        //film actors
        $statement->nextRowset();
        while($row = $statement->fetch())
        {
            $row->first_name = ucwords(strtolower($row->first_name));
            $row->last_name = ucwords(strtolower($row->last_name));
            $result->actors[] = $row;
        }
        return $result;
    }
}