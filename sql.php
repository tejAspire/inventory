<?php
  require_once('includes/load.php');

/*--------------------------------------------------------------*/
/* Function for find all database table rows by table name
/*--------------------------------------------------------------*/
function find_all($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM ".$db->escape($table));
   }
}
/*--------------------------------------------------------------*/
/* Function for Perform queries
/*--------------------------------------------------------------*/
function find_by_sql($sql)
{
  global $db;
  $result = $db->query($sql);
  $result_set = $db->while_loop($result);
 return $result_set;
}
/*--------------------------------------------------------------*/
/*  Function for Find data from table by id
/*--------------------------------------------------------------*/
function find_by_id($table,$id)
{
  global $db;
  $id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}
/*--------------------------------------------------------------*/
/* Function for Delete data from table by id
/*--------------------------------------------------------------*/
function delete_by_id($table,$id)
{
  global $db;
  if(tableExists($table))
   {
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE id=". $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}
function count_by_id($table){
    global $db;
    if(tableExists($table))
    {
      $sql    = "SELECT COUNT(id) AS total FROM ".$db->escape($table);
      $result = $db->query($sql);
       return($db->fetch_assoc($result));
    }
  }
  /*--------------------------------------------------------------*/
/* Determine if database table exists
/*--------------------------------------------------------------*/
function tableExists($table){
    global $db;
    $table_exit = $db->query('SHOW TABLES FROM '.DB_NAME.' LIKE "'.$db->escape($table).'"');
        if($table_exit) {
          if($db->num_rows($table_exit) > 0)
                return true;
           else
                return false;
        }
    }
    /*--------------------------------------------------------------*/
 /* Login with the data provided in $_POST,
 /* coming from the login form.
/*--------------------------------------------------------------*/
function authenticate($username='', $password='') {
    global $db;
    $username = $db->escape($username);
    $password = $db->escape($password);
    $sql  = sprintf("SELECT login_id,name,password,user_level FROM login WHERE name ='%s' LIMIT 1", $username);
    $result = $db->query($sql);
    if($db->num_rows($result)){
        $user = $db->fetch_assoc($result);
        if($password === $user['password'] ){
            // Check user level
            if ($user['user_level'] == 1) {
                return 1; // Admin
            } elseif ($user['user_level'] == 2) {
                return 2; // User
            }
        } else {
            return "Error: Wrong password!";
        }
    }
    return "Error: User not found!";
}
  /*--------------------------------------------------------------*/
  /* Function for Finding all product name
  /* Request coming from ajax.php for auto suggest
  /*--------------------------------------------------------------*/

  function find_product_by_title_admin($name){
    global $db;
    $p_name = remove_junk($db->escape($name));
    $sql = "SELECT product_name FROM warehouse WHERE name like '%$p_name%' LIMIT 5";
    $result = find_by_sql($sql);
    return $result;
  }
  /*--------------------------------------------------------------*/
  /* Function for Finding all product info by product title
  /* Request coming from ajax.php
  /*--------------------------------------------------------------*/
  function find_all_admin_product_info_by_title($title){
    global $db;
    $sql  = "SELECT * FROM orders ";
    $sql .= " WHERE product_id = (select product_id from warehouse where product_name='{$title}'";
    $sql .=" LIMIT 1)";
    return find_by_sql($sql);
  }
  /*--------------------------------------------------------------*/
  /* Function for Update product quantity in ware-house
  /*--------------------------------------------------------------*/
  function update_product_qty($qty,$p_id){
    global $db;
    $qty = (int) $qty;
    $id  = (int)$p_id;
    $sql = "UPDATE warehouse SET quantity=quantity +'{$qty}' WHERE product_id = '{$id}'";
    $result = $db->query($sql);
    return($db->affected_rows() === 1 ? true : false);

  }

  /*--------------------------------------------------------------*/
 /* Function for Find Highest saleing Product
 /*--------------------------------------------------------------*/
 function find_higest_saleing_product($limit){
    global $db;
    $sql  = "SELECT p.product_id, s.quantity AS totalQty";
    $sql .= " FROM sales s";
    $sql .= " ORDER BY s.quantity DESC LIMIT ".$db->escape((int)$limit);
    return $db->query($sql);
  }
  /*--------------------------------------------------------------*/
 /* Function for find all sales
 /*--------------------------------------------------------------*/
 function find_all_sale(){
    global $db;
    $sql  = "SELECT *";
    $sql .= " FROM sales s";
    $sql .= " ORDER BY s.quantity DESC";
    return find_by_sql($sql);
  }
   /*--------------------------------------------------------------*/
 /* Function for total amt
 /*--------------------------------------------------------------*/
 function getTotalSalesSum() {
    global $db; // Assuming $db is your database connection object
    
    // Query to calculate the total sum considering the quantity
    $query = "SELECT SUM(buy_price * quantity) AS total_sum FROM sales";
    
    // Execute the query
    $result = $db->query($query);
    
    // Check if the query was successful
    if ($result) {
        // Fetch the result row
        $row = $db->fetch_assoc($result);
        
        // Extract the total sum from the result row
        $totalSum = $row['total_sum'];
        
        // Free the result set
        $db->free_result($result);
        
        // Return the total sum
        return $totalSum;
    } else {
        // Query execution failed, return an error message or handle the error as needed
        return "Error: Unable to fetch total sales sum";
    }
}

