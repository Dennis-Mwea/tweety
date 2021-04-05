<?php

namespace App\Providers;

use HTMLPurifier;
use HTMLPurifier_Config;
use HTMLPurifier_HTMLDefinition;
use Illuminate\Support\ServiceProvider;
use Stevebauman\Purify\Facades\Purify;

class PurifySetupProvider extends ServiceProvider
{
    const DEFINITION_ID = 'tweety-editor';
    const DEFINITION_REV = 1;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /** @var HTMLPurifier $purifier */
        $purifier = Purify::getPurifier();

        /** @var HTMLPurifier_Config */
        $config = $purifier->config;

        $config->set('HTML.DefinitionID', static::DEFINITION_ID);
        $config->set('HTML.DefinitionRev', static::DEFINITION_REV);
        $config->set('Attr.AllowedFrameTargets', array('_blank'));

        if ($def = $config->maybeGetRawHTMLDefinition()) {
            $this->setupDefinitions($def);
        }

        $purifier->config = $config;
    }

    /**
     * Adds elements and attributes to the HTML purifier
     * definition required by the trix editor.
     *
     * @param HTMLPurifier_HTMLDefinition $def
     */
    protected function setupDefinitions(HTMLPurifier_HTMLDefinition $def)
    {
        $def->addAttribute('a', 'target', 'Text');
    }
}
