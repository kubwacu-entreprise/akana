<?php
  namespace Akana\ORM;

  use PDO;
  use PDOException;
  use ReflectionClass;
  use ReflectionProperty;

  class ORM {
    const DESC = "DESC";
    const ASC = "ASC";

    public function get_public_vars() {
      $names = [];
      $reflection = new ReflectionClass($this);
      $vars = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

      foreach ($vars as $public_var) {
        array_push($names, $public_var->getName());
      }  
      return $names;
    }

    protected static function typing($val) {
      $type = is_numeric($val)? "NaS" : "string";

      if($type == "string") {
        $val = "\"$val\"";
      }

      return $val;
    }

    protected static function get_cols($array) {
      $cols = "";

      $counter = 0;
      foreach($array as $k=>$v) {
        $cols .= ($counter != count($array)-1)? "$k," : "$k";
        $counter++;
      }
      return $cols;
    }

    protected static function get_vals($array) {
      $vals = "";

      $counter = 0;
      foreach($array as $k=>$v) {
        $v = ORM::typing($v);
        $vals .= ($counter != count($array)-1)? $v."," : $v;
        $counter++;
      }
      return $vals;
    }

    public static function get_dbcon($vars = NULL){
      $_vars = NULL;

      if($vars != NULL) {
        $_vars = $vars;
      } else {
        $db_config_file = __DIR__.'/../../config/db.yaml';

        if(!file_exists($db_config_file)) {
          throw new PDOException("config/db.yaml not found.");
        }
        $_vars = spyc_load_file($db_config_file);      
      }
      
      $db_url = $_vars['type'].':host='.$_vars['host'].''.$_vars['port'].'; dbname='.$_vars['name'];
      
      try{
        return new PDO($db_url, $_vars['login'], $_vars['password'], array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ));
      }
      catch(PDOException $e){
        throw new PDOException($e->getMessage());
      }
    }

    public static function query($query) {
      $results = [];
      $dbcon = self::get_dbcon();
      $qres = $dbcon->query($query);

      while($data = $qres->fetch()) {
        array_push($results, $data);
      }

      return $results;
    }

    public static function exec($query) {
      $results = [];
      $dbcon = self::get_dbcon();
      $dbcon->exec($query);
    }
  }