<?php
/**             
*        DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
*                     Version 2015
*
*  Copyright (C) 2015 Fadel Chafai <fadelchafai@gmail.com>
*
*  Everyone is permitted to copy and distribute verbatim or modified
*  copies of this license document, and changing it is allowed as long
*  as the name is changed.
*
*             DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
*    TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION
*   0. You just DO WHAT THE FUCK YOU WANT TO.
*/
error_reporting(E_ALL);

use \Lib\Fixers\Fixer;

require 'vendor/autoload.php';
 

if(!empty($_GET['file'])){

    if(empty($_GET['stdr']))
        $_GET['stdr'] = 'PSR2';
    
    $fixer = new Fixer($_GET['file'], $_GET['stdr']);

    $checker = $fixer->chackFile();

    if(!empty($_GET['checker'])){

        if(!$checker[0]) {
            echo $checker[1];
            return;
        }

        switch ($_GET['checker']){

            case 'phpmd':
                echo $fixer->phpmd();
                break;
            case 'phpcs':
                echo '<b>Standard : '.$_GET['stdr'].'</b><br><pre>'.$fixer->phpcs().'</pre>';
                break;
            case 'phpcsfixer' :
                echo '<b>Standard : '.$_GET['stdr'].'</b><br><pre>'.$fixer->phpcsfixer().'</pre>';
                break;
            case 'phpmetrics':
                echo '<pre>'.$fixer->phpmetrics().'</pre><br><h4>Metric Report</h4>';
                echo '<a href="report/'.$fixer->getMetricReportFile().'" target="_blank">Show report</a>';
                break;
            case 'phpcpd':
                echo '<b>Standard : '.$_GET['stdr'].'</b><br><pre>'.$fixer->phpcpd().'</pre>';
                break;
        }

    }else{

        echo $checker[1];

    }


}else{

    echo '<div class="alert alert-danger" role="alert"><b>Ooops :</b> Empty file path</div>';
     
}