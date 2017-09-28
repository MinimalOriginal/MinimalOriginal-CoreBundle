MinimalOriginal CoreBundle
========

The core bundle for Minimal

Command to launch
========
bin/console minimal_core:create-first-settings

Register bundle
========
$bundles = [
    ...
    new MinimalOriginal\CoreBundle\MinimalCoreBundle(),
];

Register routes
========
minimal_core:
    resource: "@MinimalCoreBundle/Resources/config/routing.yml"

Issues
========
- RoutingSubscriber : error if table not empty and others modules doesn't not exists : $objectManager->getReference($entity->getEntity(), $entity->getEntityId())
