<?php

##########################################################
# File Name: database.php
# Author: Denis Pikusov
# Date Created: 29/01/2005
# Description: Module for working with database
##########################################################

class Database
{
  var $db_name;
  var $host;
  var $user;
  var $pass;
  var $link;
  var $res_id;
  var $error_msg;
  var $queries = array();

  # Constructor
  function Database($database_name, $host_name = "localhost", $user_name = "", $password = "")
  {
    $this->db_name = $database_name;
    $this->host = $host_name;
    $this->user = $user_name;
    $this->pass = $password;
    $this->link = 0;
    $this->res_id = 0;
    $this->error_msg = "";
  }

  # Connecting to the database
  function connect()
  {
    if(!$this->link = mysql_connect($this->host, $this->user, $this->pass))
    {
      $this->error_msg = "Could not connect to the database on $this->host";
      return 0;
    }
    if(!mysql_select_db($this->db_name, $this->link))
    {
      $this->error_msg = "Could not select the $this->db_name database";
      return 0;
    }
    return $this->link;
  }

  # Close the database connection
  function disconnect()
  {
    if(!mysql_close($this->link))
    {
       $this->error_msg = "Could not close the $this->db_name database";
       return 0;
    }
    return 1;
  }

  # Execute the query or queries array
  function query($q)
  {
    if($this->link)
    {
      $start = microtime(true);
      $this->res_id = mysql_query($q, $this->link);
      $end = microtime(true);
      $query->sql = $q;
      $query->exec_time = $end-$start;
      $this->queries[] = $query;
      
    }else
    {
      $this->error_msg = "Could not execute query to $this->db_name database, wrong database link";
      return 0;
    }
    if(!$this->res_id)
    {
      $this->error_msg = "Could not execute query to $this->db_name database, wrong result id";
      return 0;
    }
    return $this->res_id;
  }

  # Returns results array of the query in array of objects
  function results()
  {
    $result = array();
    if(!$this->res_id)
    {
      $this->error_msg = "Could not execute query to $this->db_name database, wrong result id";
      return 0;
    }
    while($row = mysql_fetch_object($this->res_id))
    {
      array_push($result, $row);
    }
    return $result;
  }

  # Returns result of the query in array of objects
  function result()
  {
    $result = array();
    if(!$this->res_id)
    {
      $this->error_msg = "Could not execute query to $this->db_name database, wrong result id";
      return 0;
    }
    $row = mysql_fetch_object($this->res_id);
    return $row;
  }

  # Returns last inserted id
  function insert_id()
  {
    return mysql_insert_id($this->link);
  }

  # Returns last inserted id
  function num_rows()
  {
    return mysql_num_rows($this->res_id);
  }

  # Returns affected rows
  function affected_rows()
  {
    return mysql_affected_rows($this->link);
  }

}

?>