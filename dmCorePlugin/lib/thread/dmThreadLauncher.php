<?php

class dmThreadLauncher extends dmConfigurable
{
  protected
  $filesystem,
  $options;
  
  public function __construct(dmFilesystem $filesystem, array $options = array())
  {
    $this->filesystem = $filesystem;
    
    $this->initialize($options);
  }
  
  public function execute($threadClass, array $threadOptions = array())
  {
    $command = sprintf('%s "%s" %s \'%s\'',
      sfToolkit::getPhpCli(),
      $this->options['cli_file'],
      $threadClass,
      serialize($threadOptions)
    );
    
    return $this->filesystem->exec($command);
  }
  
  public function getLastExec($name = null)
  {
    return $this->filesystem->getLastExec($name);
  }
  
  protected function initialize($options)
  {
    $this->configure($options);
    
    $this->options['cli_file'] = dmProject::rootify($this->options['cli_file']);
    
    $this->checkCliFile();
  }
  
  public function getDefaultOptions()
  {
    return array(
      'app'       => sfConfig::get('sf_app'),
      'env'       => sfConfig::get('sf_environment'),
      'debug'     => false,
      'cli_file'  => 'cache/dm/cli.php'
    );
  }
  
  protected function checkCliFile()
  {
    $file = $this->options['cli_file'];
    
    if (!file_exists($file) || file_get_contents($file) != $this->getCliFileContent())
    {
      $this->filesystem->mkdir(dirname($file));
      
      file_put_contents($file, $this->getCliFileContent());
    }
    
    if (!is_executable($file))
    {
      chmod($file, 0777);
    }
    
    if (!is_executable($file))
    {
      throw new dmException('Can not make '.dmProject::unRootify($file).' executable');
    }
  }
  
  protected function getCliFileContent()
  {
    return "<?php
    
require_once('".sfConfig::get('sf_root_dir')."/config/ProjectConfiguration.class.php');

\$configuration = ProjectConfiguration::getApplicationConfiguration('{$this->options['app']}', '{$this->options['env']}', ".($this->options['debug'] ? 'true' : 'false').", '".sfConfig::get('sf_root_dir')."');

\$threadClass = \$argv[1];
\$threadOptions = unserialize(\$argv[2]);

\$thread = new \$threadClass(\$configuration, \$threadOptions);

\$thread->execute();

return 0;";
  }
}