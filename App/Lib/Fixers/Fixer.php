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

    private $projectDir;

    private $standard;

    private $metric_report;

    private $reportDir = false;

    const BIN_DIR = 'vendor/bin/';

    public function __construct($filename, $stdr)
    {
        $this->projectDir = realpath(dirname(__FILE__) . '/../../../../');
        
        if (is_file($this->projectDir . '/' . $filename) || is_dir($this->projectDir . '/' . $filename)) {
            $this->reportDir = getcwd() . '/report';
            
            $this->setFile($this->projectDir . '/' . $filename);
            
            $this->standard = $stdr;
            
            $this->phpmd = $this::BIN_DIR . 'phpmd';
            
            $this->phpcs = $this::BIN_DIR . 'phpcs';
            
            $this->phpcsfixer = $this::BIN_DIR . 'php-cs-fixer';
            
            $this->phpcbf = $this::BIN_DIR . 'phpcbf';
            
            $this->phpmetrics = $this::BIN_DIR . 'phpmetrics';
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
                
                return file_get_contents($this->reportDir . '/' . $phpmdFile);
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

    public function chackFile()
    {
        if (! $this->getFile()) {
            return [
                false,
                '<div class="alert alert-danger" role="alert"><b>Ooops :</b>
            File not exist </div>'
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
}
