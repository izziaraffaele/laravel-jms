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

use IRWeb\LaravelJMS\Console\Commands\CacheClearCommand;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Larave service provider class
 * @author izziaraffaele <https://github.com/izziaraffaele>
 * @since 0.1.0
 */
class ServiceProvider extends BaseServiceProvider
{ 
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
        $this->registerConsoleCommands();
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
            $serializer = $this->getSerializer()
              ->setDebug(config('app.debug'))
              ->setCacheDir(config('jms.cache'))
              ->setPropertyNamingStrategy(new SerializedNameAnnotationStrategy(new IdenticalPropertyNamingStrategy()))
              ->addDefaultHandlers();

            $this->registerCustomHandlers($serializer, config('jms.handlers'));
            
            return $serializer->build();
        });

        $this->app->bind(SerializerInterface::class, Serializer::class);
    }

    /**
     * Register console commands
     */
    protected function registerConsoleCommands()
    {
        $this->commands([
            CacheClearCommand::class,
        ]);
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
        return SerializerBuilder::create();
    }
}
