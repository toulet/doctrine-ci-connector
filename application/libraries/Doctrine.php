<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Doctrine interface for CodeIgniter
 *
 * @author Cyrille TOULET <cyrille.toulet@linux.com>
 * @see http://www.doctrine-project.org/
 */
class Doctrine
{
    // the Doctrine entity manager
    public $em = null;


    /**
     * Initialize the Doctrine engine
     */
    public function __construct()
    {
        // Include the CodeIgniter database configuration
        include APPPATH . 'config/database.php';
        
        // Include the Doctrine's classloader
        require_once APPPATH . 'libraries/Doctrine/Common/ClassLoader.php';

        // Load the Doctrine's classes
        $doctrineClassLoader = new \Doctrine\Common\ClassLoader('Doctrine', 
            APPPATH . 'libraries');
        $doctrineClassLoader->register();
        
        // Load Symfony2 helpers (for YAML mapping files)
        $symfonyClassLoader = new \Doctrine\Common\ClassLoader('Symfony', 
            APPPATH . 'libraries/Doctrine');
        $symfonyClassLoader->register();

        // Load the entities
        $entityClassLoader = new \Doctrine\Common\ClassLoader('Entities', 
            APPPATH . 'models');
        $entityClassLoader->register();

        // Load the proxy entities
        $proxyClassLoader = new \Doctrine\Common\ClassLoader('Proxies', 
            APPPATH . 'models');
        $proxyClassLoader->register();

        // Set up configuration 
        $config = new \Doctrine\ORM\Configuration;
    
        if(ENVIRONMENT == 'development')
            // Set up simple array caching for development mode
            $cache = new \Doctrine\Common\Cache\ArrayCache;
        else
            // Set up caching with APC for production mode
            $cache = new \Doctrine\Common\Cache\ApcCache;

        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);

        // Set up proxy configuration
        $config->setProxyDir(APPPATH . 'models/Proxies');
        $config->setProxyNamespace('Proxies');
        
        // Auto-generate proxy classes if we are in development mode
        $config->setAutoGenerateProxyClasses(ENVIRONMENT == 'development');

        // Set up annotation driver
        $yamlDriver = new \Doctrine\ORM\Mapping\Driver\YamlDriver(
            APPPATH . 'models/Mappings');
        $config->setMetadataDriverImpl($yamlDriver);

        // Set database connection informations
        $connectionOptions = array(
            'driver' => 'pdo_mysql',
            'user' => $db[$active_group]['username'],
            'password' => $db[$active_group]['password'],
            'host' => $db[$active_group]['hostname'],
            'dbname' => $db[$active_group]['database']
        );
        
        // Create the EntityManager
        $this->em = \Doctrine\ORM\EntityManager::create($connectionOptions, 
            $config);
    }
}

