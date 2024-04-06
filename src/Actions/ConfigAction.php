<?php

namespace Juzaweb\Ecommerce\Actions;

use Juzaweb\CMS\Abstracts\Action;

class ConfigAction extends Action
{
    public function handle(): void
    {
        $this->addAction(Action::INIT_ACTION, [$this, 'registerConfigs']);
    }

    public function registerConfigs(): void
    {
        $this->registerConfig(
            [
                'ecom_store_address1',
                'ecom_store_address2',
                'ecom_city',
                'ecom_country',
                'ecom_zipcode',
            ]
        );
    }
}
