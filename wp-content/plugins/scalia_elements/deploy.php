<?php

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors',1);

define('DEPLOY_VERSION','1.0.0');
define('CONFIG_FILE','deploy_config.php');
define('FRONT_CONFIG_FILE','deploy_front_config.php');

define('FMT_ERROR_COLOR','red');
define('FMT_ERROR_BOLD',true);
define('FMT_SUCCESS_COLOR','lightgreen');
define('FMT_SUCCESS_BOLD',true);
define('FMT_ACTION_COLOR','yellow');
define('FMT_ACTION_BOLD',true);

class Config {
    private $configName;
    private $config;
    private $taskConfig;
    private $actionStack=array();

    public function __construct($config,$configName) {
        if (!isset($config[$configName])) {
            throw new Exception("config name '$configName' is not defined\n");
        }
        $this->configName=$configName;
        $this->config=$this->mergeRecursive($config['default'],$config[$configName]);
    }

    public function getFrontConfig($overrides=array()) {
        $data=$this->mergeRecursive($this->config,$overrides);

        // clear sensitive data from frontConfig
        $this->unsetMatchedRecursive('',$data,'%^\.('.
            // ftp authorization data
            'sync\.[^.]*\.(ftp.*)|'.
            // important security data
            'apiKeySeed|'.
            // don't needed data
            'script.*|defaultActions.*'.
            ')$%');

        return array('default'=>$data);
    }

    public function getConfigName() {
        return $this->configName;
    }

    private function changeActionAndTask($action,$task) {
        if (isset($this->config[$action])) {
            $this->taskConfig=$this->mergeRecursive($this->config[$action]['default'],$this->config[$action][$task]);
        } else {
            $this->taskConfig=array();
        }
    }

    public function enterAction($action,$task) {
        array_push($this->actionStack,array($action,$task));
        $this->changeActionAndTask($action,$task);
    }

    public function leaveAction() {
        $action=array_pop($this->actionStack);
        $this->changeActionAndTask($action[0],$action[1]);
    }

    public function param($paramName) {
        if (!isset($this->taskConfig[$paramName])) {
            throw new Exception("config param '$paramName' is not defined\n");
        }

        return $this->taskConfig[$paramName];
    }

    public function globalParam($paramName) {
        if (!isset($this->config[$paramName])) {
            throw new Exception("config global param '$paramName' is not defined\n");
        }

        return $this->config[$paramName];
    }

    private function mergeRecursive($array1,$array2) {
        if (!is_array($array1) || !is_array($array2)) return $array2;

        foreach($array2 as $k=>$v)
            $array1[$k]=$this->mergeRecursive($array1[$k],$v);

        return $array1;
    }

    private function unsetMatchedRecursive($root,&$arr,$unsetRegex) {
        foreach($arr as $k=>$v) {
            $key="$root.$k";
            if (preg_match($unsetRegex,$key)) {
                unset($arr[$k]);
                continue;
            }
            if (is_array($v)) {
                $this->unsetMatchedRecursive($key,$arr[$k],$unsetRegex);
            }
        }
    }
}


class FtpNonBlocking {
    private $workers;
    private $config;

    public function __construct($config) {
        $this->config=$config;

        $this->workers = array();
    }

    public function getActiveWorkersCount() {
        $activeWorkersCount=0;
        foreach($this->workers as $worker) {
            if ($worker->isActive()) $activeWorkersCount++;
        }

        return $activeWorkersCount;
    }

    public function poll() {
        usleep(1000000/10);
        foreach ($this->workers as $worker)
            $worker->poll();
    }

    public function uploadFile($localFile, $remoteFile, $rights) {
        $this->getFreeWorker()->startFileUpload($localFile, $remoteFile, $rights);
    }

    public function waitUnfinishedOperations() {
        while($this->getActiveWorkersCount()>0) {
            $this->poll();
        }
    }

    public function deleteFile($remoteFile) {
        $this->getFreeWorker()->startFileDelete($remoteFile);
    }

    private function getFreeWorker() {
        while (true) {
            foreach ($this->workers as $worker)
                if (!$worker->isActive()) {
                    return $worker;
                }
            if (count($this->workers)<$this->config->param('ftpMaxParallelUploads')) {
                $worker=new FtpNonBlockingWorker($this,$this->config);
                $this->workers[]=$worker;
                return $worker;
            }
            $this->poll();
        }
    }
}


class FtpNonBlockingWorker {
    private $ftp;
    private $currentDir='';
    private $active=false;
    private $localFile;
    private $remoteFile;
    private $rights;
    private $owner;

    public function __construct($owner,$config) {
        $this->owner=$owner;
        $this->config=$config;

        $this->ftp = ftp_connect($this->config->param('ftpHost'));

        if (!$this->ftp)
            throw new Exception("Can't connect to ftp server '".$this->config->param('ftpHost')."'");

        if (!ftp_login($this->ftp, $this->config->param('ftpUsername'), $this->config->param('ftpPassword')))
            throw new Exception("Can't login to ftp server '".$this->config->param('ftpHost')."' with username '".$this->config->param('ftpUsername')."'");

        if ($this->config->param('ftpPassiveMode') && !ftp_pasv($this->ftp, true)) {
            throw new Exception("Can't enter passive mode");
        }
    }

    public function __destruct() {
        if ($this->ftp) {
            ftp_close($this->ftp);
        }
    }

    public function isActive() {
        return $this->active;
    }

    private function finishFileUpload() {
        $this->active = false;

        $ftpChmodFile=$this->config->param('ftpChmodFile');
        if ($ftpChmodFile!==false) {
            $res = ftp_chmod($this->ftp, octdec($ftpChmodFile!==true ? $ftpChmodFile:$this->rights), basename($this->remoteFile));
            if (!$res)
                throw new Exception("Can't change file '{$this->remoteFile}' rights to {$this->rights}");
        }

        $this->owner->poll();
    }

    public function poll() {
        if (!$this->active)
            return;

        $res = ftp_nb_continue($this->ftp);
        switch ($res) {
            case FTP_FINISHED:
                $this->finishFileUpload();
                break;
            case FTP_FAILED:
                throw new Exception("failed to upload file '{$this->localFile}' to '{$this->remoteFile}'");
                break;
        }
    }

    public function startFileDelete($remoteFile) {
        $res=ftp_delete($this->ftp, '/'.$this->config->param('ftpRootDir').$remoteFile);
        if (!$res) throw new Exception("Can't delete file $remoteFile");
    }

    public function startFileUpload($localFile, $remoteFile, $rights) {
        $this->localFile = $localFile;
        $this->remoteFile = $remoteFile;
        $this->rights = $rights;

        preg_match('%^(.*?)/?([^/]*)$%', $remoteFile, $m);
        $remoteFileDir = $this->config->param('ftpRootDir').$m[1];
        $remoteFileName = $m[2];
        $dirParts = $remoteFileDir != '' ? explode('/', $remoteFileDir) : array();

        // detect existing categories in file path
        for ($i = count($dirParts); $i >= 0; $i--) {
            $dir = '/' . implode('/', array_slice($dirParts, 0, $i));
            // if current dir is equal, we assume that it still exist
            if ($dir!=$this->currentDir) {
                $res = @ftp_chdir($this->ftp, $dir);
                $this->currentDir=$dir;
                if ($res)
                    break;
            } else {
                $res=true;
                break;
            }
        }

        if (!$res)
            throw new Exception("Can't chdir to root dir");

        // create required categories
        if ($i != count($dirParts)) {
            for ($j = $i + 1; $j <= count($dirParts); $j++) {
                $dir = '/' . implode('/', array_slice($dirParts, 0, $j));
                $res = @ftp_mkdir($this->ftp, $dir);
                if (!$res)
                    throw new Exception("Can't create dir '$dir'");
            }
            $dir='/' . $remoteFileDir;
            $res = @ftp_chdir($this->ftp,$dir);
            $this->currentDir=$dir;
            if (!$res)
                throw new Exception("Can't change dir to '/$remoteFileDir'");
        }

        // start file upload
        if (!preg_match('/^[-_a-zA-Z0-9().,\\[\\]%]*$/',$remoteFileName))
            throw new Exception("filename '$remoteFileName' has invalid characters");

        $res = ftp_nb_put($this->ftp, $remoteFileName, $localFile, FTP_BINARY);

        $this->active = true;

        if ($res == FTP_FAILURE)
            throw new Exception("Can't upload file '$localFile' to '$remoteFileName'");
        if ($res == FTP_FINISHED)
            $this->finishFileUpload();
    }
}


class Deploy {
    const MSG_MODE_NORMAL='';
    const MSG_MODE_CONSOLE='console';
    const MSG_MODE_HTML='html';

    private $config;
    private $ftp;
    private $msgMode=MSG_MODE_NORMAL;

    public function __construct($config) {
        $this->config=$config;
        chdir(dirname(__FILE__).'/'.$this->config->globalParam('rootDir'));
        $this->ftp=new FtpNonBlocking($config);
    }

    private function getFilesInfo($dir='.') {
        $handle=opendir($dir);
        if (!$handle) throw new Exception("Can't open dir $dir");

        $ignoreFilesRegex='%^\.('.$this->config->param('ignoreFiles').')$%S';

        $res = array();
        while(false !== ($filename = readdir($handle)))
            if ($filename!='.' && $filename!='..') {
                $fullFilename=$dir.'/'.$filename;
                if (preg_match($ignoreFilesRegex,$fullFilename)) continue;
                if (is_dir($fullFilename)) {
                    $res=array_merge($res,$this->getFilesInfo($fullFilename));
                } else {

                    $res[substr($fullFilename,2)]=array(
                        'sha1sum'=>sha1_file($fullFilename),
                        'rights'=>substr(sprintf('%o', fileperms($fullFilename)), -3)
                    );
                }
            }

        return $res;
    }

    private function msg($msg, $newLine = true, $color='', $bold=false) {
        if ($newLine) $msg.="\n";

        if ($this->msgMode==='html') {
            $msg=htmlentities($msg);
            $msg=preg_replace("%(\\n|\\r)+%",'<br/>',$msg);

            $style='';
            if ($color!='') $style.="color:$color;";
            if ($bold) $style.="font-weight:bold;";
            if ($style) $msg="<span style='$style'>$msg</span>";

            $msg=str_replace("'","\\'",$msg);

            $msg="\n<script>o('$msg','');</script>\n";

            // add spaces to make $msg 1kb length
            $msg=str_pad($msg,1024,' ');
        }
        if ($this->msgMode=='console') {
            $colors=array(
                'black'=>'0;30',
                'darkgray'=>'1;30',
                'blue'=>'0;34',
                'lightblue'=>'1;34',
                'green'=>'0;32',
                'lightgreen'=>'1;32',
                'cyan'=>'0;36',
                'lightcyan'=>'1;36',
                'red'=>'0;31',
                'lightred'=>'1;31',
                'purple'=>'0;35',
                'lightpurple'=>'1;35',
                'brown'=>'0;33',
                'yellow'=>'1;33',
                'lightgray'=>'0;37',
                'white'=>'1;37',
            );

            if ($color) $msg="\033[{$colors[$color]}m$msg\033[0m";
        }

        echo $msg;
    }

    private function error($msg, $fatal = true) {
        $this->msg("\n===FATAL ERROR===\n" . $msg ,true,FMT_ERROR_COLOR,FMT_ERROR_BOLD);
        ob_flush();
        if ($fatal) {
            $this->msg("Waiting ftp operations to finish");
            $this->ftp->waitUnfinishedOperations();
            exit(1);
        }
    }

    private function frontAPICall($action,$params=array()) {
        $res=$this->tryFrontAPICall($action,$params);
        if (is_array($res)) return $res;

        $this->msg("frontAPI call failed:",true);
        $this->msg($res,false,FMT_ERROR_COLOR,FMT_ERROR_BOLD);
        $this->msg("try reupload deployer");

        $this->config->enterAction('sync','default');

        // build front config
        $this->buildFrontConfigFile();

        // upload deploy.php and deploy_front_config.php
        $filesToUpload=array(
            $this->config->globalParam('scriptDir').basename(__FILE__)=>array('sha1sum'=>'','rights'=>644),
            $this->config->globalParam('scriptDir').FRONT_CONFIG_FILE=>array('sha1sum'=>'','rights'=>644),
        );

        $this->syncFiles($filesToUpload,array());

        $this->config->leaveAction();

        $res=$this->tryFrontAPICall($action,$params);
        if (is_array($res)) return $res;

        $this->error("got invalid response:\n$res");
    }

    private function tryFrontAPICall($action,$params) {
        $postData = 'frontAction='.rawurlencode($action).'&apiKey='.rawurlencode($this->getApiKey());
        foreach($params as $k=>$v)
            $postData.='&params['.rawurlencode($k).']='.rawurlencode($v);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->config->globalParam('scriptUrl'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // accept any compression (gzip for example)
        curl_setopt($ch,CURLOPT_ENCODING , "");

        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result,true);

        return is_array($data) ? $data:$result;
    }

    private function determineFilesToUpload($localFilesInfo, $remoteFilesInfo) {
        $this->msg("Determine files to upload... ", false);

        $filesToUpload = array();
        foreach ($localFilesInfo as $file => $data) {
            if ($remoteFilesInfo[$file]['sha1sum'] != $data['sha1sum'] /*||
                    $remoteFilesInfo[$file]['rights'] != $data['rights']*/) {
                // type of file update
                $data['type']=isset($remoteFilesInfo[$file]) ? 'updated':'new';
                $filesToUpload[$file] = $data;
            }
        }
        $this->msg(count($filesToUpload) . " files must be uploaded");

        return $filesToUpload;
    }

    private function determineFilesToDelete($localFilesInfo, $remoteFilesInfo) {
        $filesToDelete = array();
        foreach($remoteFilesInfo as $file=>$data)
            if (!isset($localFilesInfo[$file]))
                $filesToDelete[$file]=$data;

        return $filesToDelete;
    }

    private function syncFiles($filesToUpload,$filesToDelete) {
        try {
            $total = count($filesToUpload);

            if ($total>0) {
                $current = 0;
                $this->msg("Uploading $total files:");

                foreach ($filesToUpload as $file => $data) {
                    $current++;
                    $this->msg("[$current/$total](" . $this->ftp->getActiveWorkersCount() . ") Uploading $file");
                    $this->ftp->uploadFile($file, $file, $data['rights']);
                }

                $this->msg("Uploading completed");
            }

            $total = count($filesToDelete);

            if ($total>0) {
                $current = 0;
                $this->msg("Deleting $total files:");
                foreach ($filesToDelete as $file => $data) {
                    $current++;
                    $this->msg("[$current/$total] Deleting $file");
                    $this->ftp->deleteFile($file);
                }

                $this->msg("Deleting completed");
            }
            $this->msg("Waiting ftp operations to finish");

            $this->ftp->waitUnfinishedOperations();
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function runActions($actions) {
        foreach($actions as $action) {
            preg_match('%^([^:]+)(:(.*))?$%',$action,$m);

            if ($m[3]=='') {
                $this->config->enterAction($m[1],'default');
                $tasks=$this->config->param('defaultTasks');
                $this->config->leaveAction();
            } else {
                $tasks=explode(':',$m[3]);
            }

            foreach($tasks as $task) {
                $this->msg("===================================\nCfg ".$this->config->getConfigName().
                    ": run action $action:$task\n===================================",true,FMT_ACTION_COLOR,FMT_ACTION_BOLD);
                $this->config->enterAction($m[1],$task);

                $methodName='action'.ucfirst($m[1]);
                $this->$methodName($task);

                $this->config->leaveAction();
            }
        }
        $this->msg('Done',true,FMT_SUCCESS_COLOR,FMT_SUCCESS_BOLD);
    }

    private function getApiKey() {
        $hashVal='';
        $hashVal.=sha1_file(dirname(__FILE__).'/'.CONFIG_FILE);
        $hashVal.=$this->config->globalParam('apiKeySeed');
        return sha1($hashVal);

    }

    private function buildFrontConfigFile() {
        $frontConfig=$this->config->getFrontConfig(array('apiKey'=>$this->getApiKey()));

        ob_start();
        var_export($frontConfig);
        $frontConfigStr=ob_get_clean();

        $content="<?php\n// this file is autogenerated by deploy script\n\nreturn $frontConfigStr;";

        $filename=dirname(__FILE__).'/'.FRONT_CONFIG_FILE;
        $res=file_put_contents($filename,$content);

        if ($res===false) {
            $this->error("Can't save file '$filename'");
        }
    }

    public function runFrontAction($apiKey,$action,$params) {
        ob_start("ob_gzhandler");

        if ($this->config->globalParam('apiKey')!==$apiKey) {
            throw new Exception('Invalid apiKey');
        }

        preg_match('%^([^:]+)(:(.*))?$%',$action,$m);
        $task=$m[3]!='' ? $m[3]:'default';

        $this->config->enterAction($m[1],$task);
        $methodName='frontAction'.ucfirst($m[1]);
        $data=$this->$methodName($task,$params);
        $this->config->leaveAction();

        echo json_encode($data);
    }

    public function setMsgMode($mode) {
        $this->msgMode=$mode;
    }

    private function actionClear($task) {
        $data=$this->frontAPICall("clear:$task");
        $this->msg("Unlinked {$data['filesCount']} files and {$data['dirCount']} directories, ".number_format($data['size'],0)." bytes freed");
    }

    private function frontActionClear($task,$params) {
        $unlinkedFilesCount=0;
        $unlinkedDirCount=0;
        $unlinkedSize=0;
        $globs=$this->config->param('glob');
        if (is_string($globs)) $globs=array($globs);

        foreach($globs as $glob) {
            foreach (glob($glob) as $filename) {
                if (!is_dir($filename)) {
                    $unlinkedSize+=filesize($filename);
                    $unlinkedFilesCount++;
                    if (!unlink($filename)) $this->error("can't unlink file '$filename'");
                } else {
                    $unlinkedDirCount++;
                    if (!rmdir($filename)) $this->error("can't unlink dir '$filename'");
                }
            }
        }
        return array('filesCount'=>$unlinkedFilesCount,'dirCount'=>$unlinkedDirCount,'size'=>$unlinkedSize);
    }

    private function actionSyncBackup($task,$filesToUpload,$filesToDelete) {
        // backup files that will be deleted
        $files=$filesToDelete;

        // backup files that will be updated (exclude new files)
        foreach ($filesToUpload as $fileName=>$fileData)
            if ($fileData['type']!='new') {
                $files[$fileName]=$fileData;
            }

        // don't backup deploy script and its config
        $ignoreFiles=array(
            $this->config->globalParam('scriptDir').basename(__FILE__),
            $this->config->globalParam('scriptDir').FRONT_CONFIG_FILE,
        );

        foreach($ignoreFiles as $file) {
            unset($files[$file]);
        }

        if (empty($files)) return;

        $this->msg('Backuping '.count($files).' files... ',false);

        $data=$this->frontAPICall("sync:$task",array(
            "subaction"=>"backup",
            "files"=>json_encode(array_keys($files))
        ));

        $this->msg("done: {$data['filesCount']} files, ".number_format($data['filesSize'],0)." bytes");
    }

    private function actionSync($task) {
        $this->msg("Receiving local files info... ", false);
        $localFilesInfo = $this->getFilesInfo();
        $this->msg("got ".count($localFilesInfo)." files");

        $this->msg("Receiving remote files info... ", false);

        $remoteFilesInfo=$this->frontAPICall("sync:$task",array("subaction"=>"getFilesInfo"));

        $this->msg("got ".count($remoteFilesInfo)." files");

        $filesToUpload = $this->determineFilesToUpload($localFilesInfo, $remoteFilesInfo);
        $filesToDelete = $this->determineFilesToDelete($localFilesInfo, $remoteFilesInfo);

        if ($this->config->param('doBackup')) {
            $this->actionSyncBackup($task,$filesToUpload,$filesToDelete);
        }

        $this->syncFiles($filesToUpload,$filesToDelete);
    }

    private function frontActionSync($task,$params) {
        switch ($params['subaction']) {
            case 'getFilesInfo':
                return $this->getFilesInfo();
            case 'backup':
                $files=json_decode($params['files'],true);
                return $this->frontActionSyncBackup($task,$files);
            default:
                $this->error("Unknown subaction {$params['subaction']}");
        }
    }

    private function frontActionSyncBackup($task,$files) {
        $subfolder=date('Y-m-d_H_i_s__').rand(100000,999999).'_tmp';
        $tmpFolder=$this->config->param('backupDir').$subfolder;

        $filesCount=0;
        $filesSize=0;

        foreach($files as $file) {
            $destFile=$tmpFolder.'/'.$file;
            $destDir=dirname($destFile);
            if (!file_exists($destDir)) {
                if (!mkdir($destDir,0755,true)) {
                    $this->error("Can't create dir '$destDir'");
                }
            }

            if (!copy($file,$destFile)) {
                $this->error("Can't copy file '$file' to '$destFile'");
            }

            if (!touch($destFile,filemtime($file))) {
                $this->error("Can't set mtime for file '$destFile'");
            }

            $filesCount++;
            $filesSize+=filesize($destFile);
        }

        $dstFolder=preg_replace('%_tmp$%','',$tmpFolder);
        if (!rename($tmpFolder,$dstFolder)) {
            $this->error("Can't rename dir '$tmpFolder' to '$dstFolder'");
        }

        return array('filesCount'=>$filesCount,'filesSize'=>$filesSize);
    }

}

try {
    // if script is runned from command line
    if (!empty($argv)) {
        $config = new Config(require(CONFIG_FILE),$argv[1]);
        $deploy = new Deploy($config);
        $deploy->setMsgMode(Deploy::MSG_MODE_CONSOLE);
        $actions=array_slice($argv,2);
        if (empty($actions)) $actions=$config->globalParam('defaultActions');
        $deploy->runActions($actions);
        exit;
    }

    // if script is runned on deploying destination site
    if ($_REQUEST['frontAction']) {
        $config = new Config(require(FRONT_CONFIG_FILE),'default');
        $deploy = new Deploy($config);
        $deploy->runFrontAction($_REQUEST['apiKey'],$_REQUEST['frontAction'],$_REQUEST['params']);
        exit;
    }

    // web interface
    $config = new Config(require(CONFIG_FILE),'default');
    $domainRegex='%^'.$config->globalParam('allowWebInterfaceOnDomains').'$%';
    if (!preg_match($domainRegex,$_SERVER['HTTP_HOST'])) {
        throw new Exception('web interface on this domain is forbidden');
    }

    if (isset($_REQUEST['run'])) {
        set_time_limit(3600);

        $argv=preg_split('%\s+%',trim($_REQUEST['run']));
        $config = new Config(require(CONFIG_FILE),$argv[0]);
        $deploy = new Deploy($config);
        $deploy->setMsgMode(Deploy::MSG_MODE_HTML);

        $actions=array_slice($argv,1);
        if (empty($actions)) $actions=$config->globalParam('defaultActions');

        echo '<style>body {font-family: Courier;font-size: 12px;color:white;background-color:black;}</style><script>function o(msg) {document.write(msg);window.scrollTo(0,document.body.scrollHeight);}</script>';

        // prevent buffering
        header('Cache-Control: no-cache');
        ini_set('output_buffering', 'off');
        ini_set('zlib.output_compression', false);
        while (@ob_end_flush());
        ini_set('implicit_flush', true);
        ob_implicit_flush(true);

        $deploy->runActions($actions);
        exit;
    } else {
        echo '<html><head><title>Deployer</title></head><body>
        <table width="100%" height="100%"><tr><td height="20">
        <form action="?" method="post" target="log">
            <input type="text" name="run" value=""/>
            <input type="submit" value="run">
        </form>
        </td></tr><tr><td>
        <iframe src="about:blank" name="log" style="width:100%;height:100%;"></iframe>
        </td></tr></table>
        </body></html>';
    }

} catch (Exception $e) {
    echo $e->getMessage();
}