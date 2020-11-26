<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\externalWebsites;

use Yii;
use humhub\modules\content\components\ContentContainerModule;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use humhub\modules\externalWebsites\models\Website;
use humhub\modules\externalWebsites\models\Page;


class Module extends ContentContainerModule
{
    
    /**
     * @var string defines the icon
     */
    public $icon = 'fa-external-link';

    /**
     * @var string defines path for resources, including the screenshots path for the marketplace
     */
    public $resourcesPath = 'resources';


    /**
     * @inheritdoc
     */
    public function getContentContainerTypes()
    {
        return [
            Space::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getPermissions($contentContainer = null)
    {
        // check permission management to customize access to this module
        return [];
    }


    /**
     * @inheritdoc
     */
    public function enable()
    {
        return parent::enable();
    }


    /**
     * @inheritdoc
     */
    public function disable()
    {
        foreach (Page::find()->all() as $page) {
            $page->delete();
        }

        foreach (Website::find()->all() as $website)
        {
            $website->delete();
        }

        return parent::disable();
    }


    /**
     * @inheritdoc
     */
    public function enableContentContainer(ContentContainerActiveRecord $container)
    {
        return parent::enableContentContainer($container);
    }


    /**
     * @inheritdoc
     */
    public function disableContentContainer(ContentContainerActiveRecord $container)
    {
        parent::disableContentContainer($container);

        foreach (Page::find()->contentContainer($container)->all() as $page) {
            $page->delete();
        }

        foreach (Website::findAll(['space_id' => $container['id']]) as $website)
        {
            $website->delete();
        }
    }


    /**
     * @inheritdoc
     */
    public function getContentContainerName(ContentContainerActiveRecord $container)
    {
        return Yii::t('ExternalWebsitesModule.base', 'External websites');
    }

    /**
     * @inheritdoc
     */
    public function getContentContainerDescription(ContentContainerActiveRecord $container)
    {
        if ($container instanceof Space) {
            return Yii::t('ExternalWebsitesModule.base', 'Creates a content for each external website page, enabling to have Humhub addons (comments, like, files, etc.) in theses pages.');
        }
    }
}
