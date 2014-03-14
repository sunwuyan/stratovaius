<?PHP
    set_time_limit(0);
    header("Content-type: text/html; charset=utf-8");
    include 'CDebug.php';
    include 'yii.php';
    
    date_default_timezone_set('prc');
    function p($var){
        CDebug::dump($var);
    }
    
    function parseDate($dateStr){
        $year=substr($dateStr,0,4);
        $month=substr($dateStr,4,2);
        $day=substr($dateStr,6,2);
        $hour=substr($dateStr,8,2);
        $min=substr($dateStr,10,2);
        $sec=substr($dateStr,-2);
        return $year.'-'.$month.'-'.$day.' '.$hour.':'.$min.':'.$sec;
    }
    
    if(empty($_GET)){
        p("参数错误！");
        die();
    }
    $ip=$_GET['ip'];
    $from=parseDate($_GET['from']);
    $to=parseDate($_GET['to']);
    
    
    $connection=new CDbConnection('mysql:host=10.235.168.95;dbname=wmock','root','alex121');

    $logs = $connection->createCommand()
                  ->select('remote_addr, create_time, response_content_length')
                  ->from('http_log_record')
                  ->where('remote_addr=:ip and create_time between :from and :to ', array(':ip'=>$ip,':from'=>$from,':to'=>$to))
                  ->order('create_time desc')
                  ->queryAll();
    
    foreach($logs as $log){
        if(!empty($log['response_content_length']))
            $total+=(int)$log['response_content_length'];
    }
    
    $to=strtotime($logs[0]['create_time']);
    $from=strtotime($logs[count($logs)-1]['create_time']);
    $time=$to-$from;

    p($total/1024.0/1024);
    
    