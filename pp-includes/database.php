<?php
class Database {
 
    private $host;
    private $user;
    private $pass;
    private $name;
    private $link;
    private $error;
    private $errno;
    private $query;
 
    function __construct($host, $user, $pass, $name = "", $conn = 1) {
        $this -> host = $host;
        $this -> user = $user;
        $this -> pass = $pass;
        if (!empty($name)) $this -> name = $name;      
        if ($conn == 1) $this -> connect();
    }
 
    function __destruct() {
        @mysql_close($this->link);
    }
 
    public function connect() {
        if ($this -> link = mysql_connect($this -> host, $this -> user, $this -> pass)) {
            if (!empty($this -> name)) {
                if (!mysql_select_db($this -> name)) $this -> exception("Could not connect to the database!");
            }
        } else {
            $this -> exception("Could not create database connection!");
        }
    }
 
    public function close() {
        @mysql_close($this->link);
    }
 
    public function query($sql) {
        if ($this->query = @mysql_query($sql)) {
            return $this->query;
        } else {
            $this->exception("Could not query database!");
            return false;
        }
    }
 
    public function num_rows($qid) {
        if (empty($qid)) {         
            $this->exception("Could not get number of rows because no query id was supplied!");
            return false;
        } else {
            return mysql_num_rows($qid);
        }
    }
 
    public function fetch_array($qid) {
        if (empty($qid)) {
            $this->exception("Could not fetch array because no query id was supplied!");
            return false;
        } else {
            $data = mysql_fetch_array($qid);
        }
        return $data;
    }
 
    public function fetch_array_assoc($qid) {
        if (empty($qid)) {
            $this->exception("Could not fetch array assoc because no query id was supplied!");
            return false;
        } else {
            $data = mysql_fetch_array($qid, MYSQL_ASSOC);
        }
        return $data;
    }
 
    public function fetch_all_array($sql, $assoc = true) {
        $data = array();
        if ($qid = $this->query($sql)) {
            if ($assoc) {
                while ($row = $this->fetch_array_assoc($qid)) {
                    $data[] = $row;
                }
            } else {
                while ($row = $this->fetch_array($qid)) {
                    $data[] = $row;
                }
            }
        } else {
            return false;
        }
        return $data;
    }
 
    public function last_id() {
        if ($id = mysql_insert_id()) {
            return $id;
        } else {
            return false;
        }
    }
 
    private function exception($message) {
        if ($this->link) {
            $this->error = mysql_error($this->link);
            $this->errno = mysql_errno($this->link);
        } else {
            $this->error = mysql_error();
            $this->errno = mysql_errno();
        }
        if (PHP_SAPI !== 'cli') {
        ?>
 
            <div class="alert-bad">
                <div>
                    Database Error
                </div>
                <div>
                    Message: <?php echo $message; ?>
                </div>
                <?php if (strlen($this->error) > 0): ?>
                    <div>
                        <?php echo $this->error; ?>
                    </div>
                <?php endif; ?>
                <div>
                    Script: <?php echo @$_SERVER['REQUEST_URI']; ?>
                </div>
                <?php if (strlen(@$_SERVER['HTTP_REFERER']) > 0): ?>
                    <div>
                        <?php echo @$_SERVER['HTTP_REFERER']; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php
        } else {
                    echo "MYSQL ERROR: " . ((isset($this->error) && !empty($this->error)) ? $this->error:'') . "\n";
        };
    }

    public function write_table() {
        
    }
 
}

/* USAGE

// Start Database connection
$db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);
// set db query
$q = "SELECT * FROM content ORDER BY id DESC";
$r = $db->query($q);
// if we have a result loop over the result
if ($db->num_rows($r) > 0) {
  while ($a = $db->fetch_array_assoc($r)) {
    echo "{$a['user_id']} wrote {$a['title']}\n";
  }
}
 
// fetch array of articles with less code
$q = "SELECT * FROM articles ORDER BY id DESC";
$a = $db->fetch_all_array($q);
if (!empty($a)) {
  foreach ($a as $k => $v) {
    echo "{$v['author']} wrote {$v['title']}\n";
  }
}

*/
?>