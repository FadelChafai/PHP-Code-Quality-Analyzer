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
namespace Lib\Fixers;

class Fixer
{

    private $file = null;

    private $phpmd;

    private $phpcs;

    private $phpcbf;

    private $phpcsfixer;

    private $phpmetrics;

    private $phpcpd;

    private $phploc;

    private $projectDir;

    private $standard;

    private $metric_report;
    
    private $coverageFolder;

    private $reportDir = false;
    
    private $userFile = null;

    const BIN_DIR = 'vendor/bin/';

    public function __construct($filename, $stdr)
    {
        $this->projectDir = realpath(dirname(__FILE__) . '/../../../../');
        
        $this->userFile = $this->projectDir . '/' . $filename;
        
        if (is_file($this->userFile) || is_dir($this->userFile)) {
            
            $this->reportDir = getcwd() . '/report';
            
            if (!is_writable($this->reportDir) && !is_dir($this->reportDir)) {
                if (!mkdir($this->reportDir, 0777, true)) {
                    return '<div class="alert alert-danger" role="alert"><b>Ooops :</b> Error, Failed to create report folders... </div>';
                }
            }
            
            $this->setFile($this->userFile);
            
            $this->standard = $stdr;
            
            $this->phpmd = $this::BIN_DIR . 'phpmd';
            
            $this->phpcs = $this::BIN_DIR . 'phpcs';
            
            $this->phpcsfixer = $this::BIN_DIR . 'php-cs-fixer';
            
            $this->phpcbf = $this::BIN_DIR . 'phpcbf';
            
            $this->phpmetrics = $this::BIN_DIR . 'phpmetrics';

            $this->phpcpd = $this::BIN_DIR . 'phpcpd';

            $this->phploc = $this::BIN_DIR . 'phploc';

            $this->phpunit = $this::BIN_DIR . 'phpunit';
        }
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * phpcs /path/to/file
     *
     * @return string
     */
    public function phpmd()
    {
        if (is_file($this->phpmd)) {
            try {
                $phpmdFile = 'phpmd-' . date('YmdGis') . '.html';
                
                shell_exec($this->phpmd . ' ' . $this->getFile() . ' html codesize,unusedcode,naming --strict > ' . $this->reportDir . '/' . $phpmdFile);
           
                $html =  file_get_contents($this->reportDir . '/' . $phpmdFile);
                
                $search = array('<table align="center" cellspacing="0" cellpadding="3">','bgcolor="lightgrey"','<center><h2>Problems found</h2></center>');
               
                $replace = array('<table class="table table-striped table-hover ">','','');
                 
                
                $html =  str_replace('', '', $html);
                
                $html =  str_replace($search, $replace, $html);
                
                
                
                return $html;
                
            } catch (Exception $e) {
                return '<div class="alert alert-danger" role="alert"><b>Ooops :</b> Error, ' . $e->getTraceAsString() . ' </div>';
            }
        } else {
            return '<div class="alert alert-danger" role="alert"><b>Ooops :</b> PHPMD not installed<br>Use : composer install</div>';
        }
    }

    /**
     * phpcs /path/to/file
     *
     * @return string
     */
    public function phpcs()
    {
        if (is_file($this->phpcs)) {
            try {
                $out = shell_exec($this->phpcs . ' ' . $this->getFile() . ' --standard=' . $this->standard);
                
                return $out;
            } catch (Exception $e) {
                return '<div class="alert alert-danger" role="alert"><b>Ooops :</b> Error, ' . $e->getTraceAsString() . ' </div>';
            }
        } else {
            return '<div class="alert alert-danger" role="alert"><b>Ooops :</b> PHPCS not installed<br>Use : composer install</div>';
        }
    }

    /**
     * php-cs-fixer fix /path/to/file --level=psr2
     *
     * @return string
     */
    public function phpcsfixer()
    {
        if (is_file($this->phpcsfixer)) {
            try {
                $csfixer = shell_exec($this->phpcsfixer . ' fix ' . $this->getFile() . ' --level=' . $this->standard);
                $csfixer = $this->phpcbf();
                $csAfter = $this->phpcs();
                
                return '<h2>Before</h2>' . $this->phpcs() . '<h2>Fixer</h2>' . $csfixer . '<h2>After</h2>' . $csAfter;
            } catch (Exception $e) {
                return '<div class="alert alert-danger" role="alert"><b>Ooops :</b> Error, ' . $e->getTraceAsString() . ' </div>';
            }
        } else {
            return '<div class="alert alert-danger" role="alert"><b>Ooops :</b> php-cs-fixer not installed<br>Use : composer install</div>';
        }
    }

    /**
     * phpcs /path/to/file
     *
     * @return string
     */
    public function phpcbf()
    {
        if (is_file($this->phpcbf)) {
            try {
                return shell_exec($this->phpcbf . ' ' . $this->getFile() . ' --standard=' . $this->standard);
            } catch (Exception $e) {
                return '<div class="alert alert-danger" role="alert"><b>Ooops :</b> Error, ' . $e->getTraceAsString() . ' </div>';
            }
        } else {
            return '<div class="alert alert-danger" role="alert"><b>Ooops :</b> PHPCBF not installed<br>Use : composer install</div>';
        }
    }

    /**
     * phpmetrics --report-html=myreport.html /path/of/your/sources
     *
     * @return string
     */
    public function phpmetrics()
    {
        if (is_file($this->phpmetrics)) {
            try {
                $this->metric_report = 'myreport-' . date('YmdGis') . '.html';
                
                return shell_exec($this->phpmetrics . ' --report-html=' . $this->reportDir . '/' . $this->metric_report . ' ' . $this->getFile());
            } catch (Exception $e) {
                return '<div class="alert alert-danger" role="alert"><b>Ooops :</b> Error, ' . $e->getTraceAsString() . ' </div>';
            }
        } else {
            return '<div class="alert alert-danger" role="alert"><b>Ooops :</b> PHPCBF not installed<br>Use : composer install</div>';
        }
    }

    /**
     * phpcpd /path/of/your/sources
     *
     * @return string
     */
    public function phpcpd()
    {
        if (is_file($this->phpcpd)) {
            try {
                return shell_exec($this->phpcpd . ' ' . $this->getFile());
            } catch (Exception $e) {
                return '<div class="alert alert-danger" role="alert"><b>Ooops :</b> Error, ' . $e->getTraceAsString() . ' </div>';
            }
        } else {
            return '<div class="alert alert-danger" role="alert"><b>Ooops :</b> PHPCPD not installed<br>Use : composer install</div>';
        }
    }

    /**
     * phploc /path/of/your/sources
     *
     * @return string
     */
    public function phploc()
    {
        if (is_file($this->phploc)) {
            try {
                return shell_exec($this->phploc . ' ' . $this->getFile());
            } catch (Exception $e) {
                return '<div class="alert alert-danger" role="alert"><b>Ooops :</b> Error, ' . $e->getTraceAsString() . ' </div>';
            }
        } else {
            return '<div class="alert alert-danger" role="alert"><b>Ooops :</b> PHPLOC not installed<br>Use : composer install</div>';
        }
    }

    /**
     * phpunit --coverage-html /path/of/report/folder
     *
     * @return string
     */
    public function phpcodecoverage()
    {
        if (is_file($this->phpunit)) {
            try {
                $this->coverageFolder = date('YmdGis');
                $coverageFolderPath = $this->reportDir . '/' . $this->coverageFolder;
                $projectDir = getcwd();
                // Change directory to the choosen one, PHPUNIT must be executed on the working directory
                chdir($this->getFile());
                return shell_exec($projectDir . '/' . $this->phpunit . ' --coverage-html '. $coverageFolderPath);
            } catch (Exception $e) {
                return '<div class="alert alert-danger" role="alert"><b>Ooops :</b> Error, ' . $e->getTraceAsString() . ' </div>';
            }
        } else {
            return '<div class="alert alert-danger" role="alert"><b>Ooops :</b> PHPUNIT not installed<br>Use : composer install</div>';
        }
    }

    public function chackFile()
    {
        if (! $this->getFile()) {
            return [
                false,
                '<div class="alert alert-danger" role="alert"><b>Ooops :</b>
            File not exist <br /> File path : '.$this->userFile.' </div>'
            ];
        } else {
            return [
                true,
                '<div class="alert alert-success" role="alert">
        <b>Good :</b> File checked  </div>'
            ];
        }
    }

    public function getMetricReportFile()
    {
        return $this->metric_report;
    }
    
    public function getCoverageFolder(){
        return $this->coverageFolder;
    }
}
