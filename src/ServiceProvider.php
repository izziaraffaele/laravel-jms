<?php

/**
 * laravel-jms
 * @package Serializer
 * @version 0.1.0
 * @link https://github.com/izziaraffaele/laravel-jms
 * @author izziaraffaele <https://github.com/izziaraffaele>
 * @license https://github.com/izziaraffaele/laravel-jms/blob/master/LICENSE
 * @copyright Copyright (c) 2014, izziaraffaele 
 */
namespace IRWeb\LaravelJMS;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\Handler\HandlerRegistry;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Larave service provider class
 * @author izziaraffaele <https://github.com/izziaraffaele>
 * @since 0.1.0
 */
class ServiceProvider extends BaseServiceProvider
{ 

    protected $defer = true;
    
    /**
     * Boot service provider.
     */
    public function boot()
    {
        $this->publishes([
            $this->getConfigPath() => config_path('jms.php'),
        ], 'config');
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAnnotations();
        $this->registerSerializer();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Serializer::class
        ];
    }

    protected function registerCustomHandlers(SerializerBuilder $serializer, array $handlers)
    {
        foreach ($handlers as $handler) 
        {
            $serializer->configureHandlers(function(HandlerRegistry $registry) use ($handler){
                $registry->registerSubscribingHandler(new $handler());
            });
        }
    }
    protected function registerAnnotations()
    {
        AnnotationRegistry::registerLoader('class_exists');
    }
    protected function registerSerializer()
    {
        $this->app->singleton(Serializer::class, function ($app) {
            $config = config('jms');

            $serializer = $this->getSerializer()
              ->setCacheDir($config['cache'])
              ->addDefaultHandlers();

            $this->registerCustomHandlers($serializer, $config['handlers']);
            
            return $serializer->build();
        });

        $this->app->bind(SerializerInterface::class, Serializer::class);
    }

    /**
     * @return string
     */
    protected function getConfigPath()
    {
        return __DIR__ . '/../config/jms.php';
    }

    /**
     * Merge config
     */
    protected function mergeConfig()
    {
        $this->mergeConfigFrom(
            $this->getConfigPath(), 'jms'
        );
    }

    protected function getSerializer()
    {
        return SerializerBuilder::create()->setDebug(config('app.debug'));
    }
}
