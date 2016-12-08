<?php

namespace MMC\SonataAdminBundle\Services;

use Sonata\BlockBundle\Block\BlockContextManagerInterface;
use Sonata\BlockBundle\Block\BlockServiceManagerInterface;
use Sonata\BlockBundle\Event\BlockEvent;

class BlockListener
{
    protected $blockServiceManager;

    protected $blockContextManager;

    public function __construct(
        BlockServiceManagerInterface $blockServiceManager,
        BlockContextManagerInterface $blockContextManager
    ) {
        $this->blockServiceManager = $blockServiceManager;
        $this->blockContextManager = $blockContextManager;
    }

    public function onBlock(BlockEvent $event)
    {
        $context = $event->getSetting('context', null);

        if ($context) {
            $services = $this->blockServiceManager->getServicesByContext($context);

            foreach ($services as $service) {
                $blockContext = $this->blockContextManager->get(['type' => $service->getName()]);

                $event->addBlock($blockContext->getBlock());
            }
        }
    }
}
