<pre>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';
$db_obj = new DatabaseAccess();

//variables
$minSupp  = 10;                  //minimal support
$minConf  = 33;                 //minimal confidence
$type     = Apriori::SRC_DB;    //data type

$data = array();
switch ($type) {
    case Apriori::SRC_PLAIN:
        //transactions
        $data = array(
            'bread, milk',
            'sugar, milk, beer',
            'bread',
            'bread, milk, beer',
            'sugar, milk, beer'
        ); //id(items)  
        //$data = 'plain.txt';
        break;
    case Apriori::SRC_DB:
        //database
        $data = array(
            100 => array(1, 'A'),
            101 => array(1, 'C'),
            102 => array(1, 'D'),
            200 => array(2, 'B'),
            201 => array(2, 'C'),
            202 => array(2, 'E'),
            300 => array(3, 'A'),
            301 => array(3, 'B'),
            302 => array(3, 'C'),
            303 => array(3, 'E'),
            400 => array(4, 'B'),
            401 => array(4, 'E')
        ); //id(user,item)     
        break;
    case Apriori::SRC_CSV:
        $data = array(
            'file' => '../data/transact.csv',
            'tid' => 'transactId',
            'item' => 'itemName',
            'delim' => "\t"
        );
        break;
}

try {
    
    $apriori = new Apriori($type, $data, $minSupp, $minConf);
    $apriori->displayTransactions()
            ->solve()
            ->generateRules();
    foreach ($apriori->getRules() as $X => &$rules) {
        foreach ($rules as $r) {
            $r['set']  = $X.Apriori::ITEM_SEP.$r['Y'];
            $r['set']  = Apriori::_explode($r['set']);
            natcasesort($r['set']);
            $temp_set  = Apriori::_join($r['set']);
            foreach ($rules as $set) {
                if ($temp_set == $set['set']) {

                    break;
                }
            }

            print $r['set']." (support: ".$r['supp'].", confidence:".$r['conf'].")\n";
        }
    }
    unset($apriori);
    
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
</pre>