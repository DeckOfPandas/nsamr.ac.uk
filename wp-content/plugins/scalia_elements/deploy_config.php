<?php

return array(
    'default'=>array(
        // regex, on which domains deploy.php can show webinterface
        'allowWebInterfaceOnDomains'=>'scalia-wptheme.loc',
        'apiKeySeed'=>'hnEFnvQUvWwzhRuNY9n6NKmg',
        // relative path to project root
        'rootDir'=>'',

        // syncronization action config
        'sync'=>array(
            'default'=>array(
                'defaultTasks'=>array('default'),
                'ftpMaxParallelUploads'=>3,
                // change rights on file after upload
                // can be FALSE (no chmod), TRUE (as local file), '644' - set these rights
                'ftpChmodFile'=>false,
                // use ftp passive mode
                'ftpPassiveMode'=>true,
                'ignoreFiles'=>
                    // main deploy config
                    '/deploy_config.php|'.
                    // vcs files
                    '/\.git|.*/\.(gitignore|svn)|'.
                    // ide files
                    '/\.(idea|settings|buildpath|project)|'.
                    // npm/grunt files
                    '/\.grunt|/node_modules|/package.json|/Gruntfile.js|'.
                    // os thumbs caching files
                    '.*/(Thumbs\.db|\.DS_Store)|'.
                    // ide backup files
                    '.*~|'.
                    //project specific settings
                    '/deploy_backups|'.
                    //custom.css
                    '/css/custom.css'
                ,
            )
        ),
    ),

    'dev'=>array(
        'scriptUrl'=>'http://codex-themes.com/scalia/wp-content/plugins/scalia_elements/deploy.php',
        // scriptDir relative to ftpRootDir
        'scriptDir'=>'',

        // actions to run when no actions specified in command line
        'defaultActions'=>array('sync') ,

        // syncronization action config
        'sync'=>array(
            'default'=>array(
                'ftpHost'=>'ftp.loc',
                'ftpUsername'=>'deploy-wpk',
                'ftpPassword'=>'deploy',
                // use '' for root or something like 'www/htdocs/'
                'ftpRootDir'=>'scalia/wp-content/plugins/scalia_elements/',
                'doBackup'=>true,
                // path relative to project root
                'backupDir'=>'deploy_backups/',
            )
        )
    ),

    'dev-one-page'=>array(
        'scriptUrl'=>'http://codex-themes.com/scalia/one-page/wp-content/plugins/scalia_elements/deploy.php',
        // scriptDir relative to ftpRootDir
        'scriptDir'=>'',

        // actions to run when no actions specified in command line
        'defaultActions'=>array('sync') ,

        // syncronization action config
        'sync'=>array(
            'default'=>array(
                'ftpHost'=>'ftp.loc',
                'ftpUsername'=>'deploy-wpk',
                'ftpPassword'=>'deploy',
                // use '' for root or something like 'www/htdocs/'
                'ftpRootDir'=>'scalia/one-page/wp-content/plugins/scalia_elements/',
                'doBackup'=>true,
                // path relative to project root
                'backupDir'=>'deploy_backups/',
            )
        )
    ),

    'dev-zaro'=>array(
        'scriptUrl'=>'http://codex-themes.com/scalia/zaro/wp-content/plugins/scalia_elements/deploy.php',
        // scriptDir relative to ftpRootDir
        'scriptDir'=>'',

        // actions to run when no actions specified in command line
        'defaultActions'=>array('sync') ,

        // syncronization action config
        'sync'=>array(
            'default'=>array(
                'ftpHost'=>'ftp.loc',
                'ftpUsername'=>'deploy-wpk',
                'ftpPassword'=>'deploy',
                // use '' for root or something like 'www/htdocs/'
                'ftpRootDir'=>'scalia/zaro/wp-content/plugins/scalia_elements/',
                'doBackup'=>true,
                // path relative to project root
                'backupDir'=>'deploy_backups/',
            )
        )
    ),

    'dev-scrollex'=>array(
        'scriptUrl'=>'http://codex-themes.com/scalia/scrollex/wp-content/plugins/scalia_elements/deploy.php',
        // scriptDir relative to ftpRootDir
        'scriptDir'=>'',

        // actions to run when no actions specified in command line
        'defaultActions'=>array('sync') ,

        // syncronization action config
        'sync'=>array(
            'default'=>array(
                'ftpHost'=>'ftp.loc',
                'ftpUsername'=>'deploy-wpk',
                'ftpPassword'=>'deploy',
                // use '' for root or something like 'www/htdocs/'
                'ftpRootDir'=>'scalia/scrollex/wp-content/plugins/scalia_elements/',
                'doBackup'=>true,
                // path relative to project root
                'backupDir'=>'deploy_backups/',
            )
        )
    ),

    'dev-dear-diary'=>array(
        'scriptUrl'=>'http://codex-themes.com/scalia/dear-diary/wp-content/plugins/scalia_elements/deploy.php',
        // scriptDir relative to ftpRootDir
        'scriptDir'=>'',

        // actions to run when no actions specified in command line
        'defaultActions'=>array('sync') ,

        // syncronization action config
        'sync'=>array(
            'default'=>array(
                'ftpHost'=>'ftp.loc',
                'ftpUsername'=>'deploy-wpk',
                'ftpPassword'=>'deploy',
                // use '' for root or something like 'www/htdocs/'
                'ftpRootDir'=>'scalia/dear-diary/wp-content/plugins/scalia_elements/',
                'doBackup'=>true,
                // path relative to project root
                'backupDir'=>'deploy_backups/',
            )
        )
    ),
);

