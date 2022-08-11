<?php

/**
 * 2007-2022 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2022 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class Mobytic_custom_blocks_5 extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'mobytic_custom_blocks_5';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Mobytic';
        $this->need_instance = 1;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Mobytic - Custom - 5 Blocks');
        $this->description = $this->l('Personnaliser 5 blocks');

        $this->confirmUninstall = $this->l('');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        $this->installTab();

        Configuration::updateValue('MOBYTIC_CUSTOM_BLOCKS_5_LIVE_MODE', false);

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayHome');
    }

    public function installTab()
    {
        $response = true;

        // First check for parent tab
        $parentTabID = Tab::getIdFromClassName('AdminMobytic');

        if ($parentTabID) {
            $parentTab = new Tab($parentTabID);
        } else {
            $parentTab = new Tab();
            $parentTab->active = 1;
            $parentTab->name = array();
            $parentTab->class_name = "AdminMobytic";
            foreach (Language::getLanguages() as $lang) {
                $parentTab->name[$lang['id_lang']] = "Mobytic";
            }
            $parentTab->id_parent = 0;
            $parentTab->module = $this->name;
            $response &= $parentTab->add();
        }

        // Check for parent tab2
        $parentTab_2ID = Tab::getIdFromClassName('AdminMobyticThemeCustom');
        if ($parentTab_2ID) {
            $parentTab_2 = new Tab($parentTab_2ID);
        } else {
            $parentTab_2 = new Tab();
            $parentTab_2->active = 1;
            $parentTab_2->name = array();
            $parentTab_2->class_name = "AdminMobyticThemeCustom";
            foreach (Language::getLanguages() as $lang) {
                $parentTab_2->name[$lang['id_lang']] = "Theme Custom";
            }
            $parentTab_2->id_parent = $parentTab->id;
            $parentTab_2->module = $this->name;
            $response &= $parentTab_2->add();
        }

        // Created tab
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'Admin' . $this->name;
        $tab->name = array();
        foreach (Language::getLanguages() as $lang) {
            $tab->name[$lang['id_lang']] = "5 blocs";
        }
        $tab->id_parent = $parentTab_2->id;
        $tab->module = $this->name;
        $response &= $tab->add();

        return $response;
    }

    public function uninstall()
    {
        $this->uninstallTab();

        Configuration::deleteByName('MOBYTIC_CUSTOM_BLOCKS_5_LIVE_MODE');

        return parent::uninstall();
    }

    public function uninstallTab()
    {
        $id_tab = Tab::getIdFromClassName('Admin' . $this->name);
        $tab = new Tab($id_tab);
        $tab->delete();
        return true;
    }


    // ****************************************************
    // Load the configuration form
    // ****************************************************
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */

        $output = null;

        $output .= $this->uploadFileConditions('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_1_IMG');
        $output .= $this->uploadFileConditions('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_2_IMG');
        $output .= $this->uploadFileConditions('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_3_IMG');
        $output .= $this->uploadFileConditions('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_4_IMG');
        $output .= $this->uploadFileConditions('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_5_IMG');

        $this->context->smarty->assign('module_dir', $this->_path);

        $output .= $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

        $output .= $this->renderForm($this->getConfigForm(), $this->getConfigFormValues(), 'submitMobytic_custom_blocks_5Module');
        $output .= $this->renderForm($this->getConfigForm_block_1(), $this->getConfigFormValues_1(), 'submitMobytic_custom_blocks_5Module_1');
        $output .= $this->renderForm($this->getConfigForm_block_2(), $this->getConfigFormValues_2(), 'submitMobytic_custom_blocks_5Module_2');
        $output .= $this->renderForm($this->getConfigForm_block_3(), $this->getConfigFormValues_3(), 'submitMobytic_custom_blocks_5Module_3');
        $output .= $this->renderForm($this->getConfigForm_block_4(), $this->getConfigFormValues_4(), 'submitMobytic_custom_blocks_5Module_4');
        $output .= $this->renderForm($this->getConfigForm_block_5(), $this->getConfigFormValues_5(), 'submitMobytic_custom_blocks_5Module_5');

        return $output;
    }



    // ****************************************************
    // RENDER FORM
    // ****************************************************
    protected function renderForm($getConfigForm, $getConfigFormValues, $submit_action)
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = $submit_action;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'uri' => $this->getPathUri(),
            'fields_value' => $getConfigFormValues, /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($getConfigForm));
    }


    // ****************************************************
    // Create the structure of your form.
    // ****************************************************
    /**
     *  CONFIG
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Paramètres'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Afficher'),
                        'name' => 'MOBYTIC_CUSTOM_BLOCKS_5_LIVE_MODE',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Activé')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Désactivé')
                            )
                        ),
                    ),
                    // array(
                    //     'col' => 3,
                    //     'type' => 'text',
                    //     'prefix' => '<i class="icon icon-envelope"></i>',
                    //     'desc' => $this->l('Enter a valid email address'),
                    //     'name' => 'MOBYTIC_CUSTOM_BLOCKS_5_ACCOUNT_EMAIL',
                    //     'label' => $this->l('Email'),
                    // ),
                    // array(
                    //     'type' => 'password',
                    //     'name' => 'MOBYTIC_CUSTOM_BLOCKS_5_ACCOUNT_PASSWORD',
                    //     'label' => $this->l('Password'),
                    // ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }


    /**
     *  BLOCK 1
     */
    protected function getConfigForm_block_1()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Block 1'),
                    'icon' => 'icon-edit-sign',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'name' => 'MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_1_TITLE',
                        'prefix' => '<i class="icon icon-text-width"></i>',
                        'label' => $this->l('Titre'),
                    ),
                    array(
                        'type' => 'file',
                        'name' => 'MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_1_IMG',
                        'label' => $this->l('Image'),
                        'display_image' => true,
                        'image' => $this->displayImgInForm('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_1_IMG'),
                    ),
                    array(
                        'type' => 'text',
                        'name' => 'MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_1_URL',
                        'prefix' => '<i class="icon icon-link"></i>',
                        'label' => $this->l('URL'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     *  BLOCK 2
     */
    protected function getConfigForm_block_2()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Block 2'),
                    'icon' => 'icon-edit-sign',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'name' => 'MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_2_TITLE',
                        'prefix' => '<i class="icon icon-text-width"></i>',
                        'label' => $this->l('Titre'),
                    ),
                    array(
                        'type' => 'file',
                        'name' => 'MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_2_IMG',
                        'label' => $this->l('Image'),
                        'display_image' => true,
                        'image' => $this->displayImgInForm('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_2_IMG'),
                    ),
                    array(
                        'type' => 'text',
                        'name' => 'MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_2_URL',
                        'prefix' => '<i class="icon icon-link"></i>',
                        'label' => $this->l('URL'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     *  BLOCK 3
     */
    protected function getConfigForm_block_3()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Block 3'),
                    'icon' => 'icon-edit-sign',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'name' => 'MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_3_TITLE',
                        'prefix' => '<i class="icon icon-text-width"></i>',
                        'label' => $this->l('Titre'),
                    ),
                    array(
                        'type' => 'file',
                        'name' => 'MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_3_IMG',
                        'label' => $this->l('Image'),
                        'display_image' => true,
                        'image' => $this->displayImgInForm('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_3_IMG'),
                    ),
                    array(
                        'type' => 'text',
                        'name' => 'MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_3_URL',
                        'prefix' => '<i class="icon icon-link"></i>',
                        'label' => $this->l('URL'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     *  BLOCK 4
     */
    protected function getConfigForm_block_4()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Block 4'),
                    'icon' => 'icon-edit-sign',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'name' => 'MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_4_TITLE',
                        'prefix' => '<i class="icon icon-text-width"></i>',
                        'label' => $this->l('Titre'),
                    ),
                    array(
                        'type' => 'file',
                        'name' => 'MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_4_IMG',
                        'label' => $this->l('Image'),
                        'display_image' => true,
                        'image' => $this->displayImgInForm('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_4_IMG'),
                    ),
                    array(
                        'type' => 'text',
                        'name' => 'MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_4_URL',
                        'prefix' => '<i class="icon icon-link"></i>',
                        'label' => $this->l('URL'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     *  BLOCK 5
     */
    protected function getConfigForm_block_5()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Block 5'),
                    'icon' => 'icon-edit-sign',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'name' => 'MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_5_TITLE',
                        'prefix' => '<i class="icon icon-text-width"></i>',
                        'label' => $this->l('Titre'),
                    ),
                    array(
                        'type' => 'file',
                        'name' => 'MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_5_IMG',
                        'label' => $this->l('Image'),
                        'display_image' => true,
                        'image' => $this->displayImgInForm('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_5_IMG'),
                    ),
                    array(
                        'type' => 'text',
                        'name' => 'MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_5_URL',
                        'prefix' => '<i class="icon icon-link"></i>',
                        'label' => $this->l('URL'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }




    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'MOBYTIC_CUSTOM_BLOCKS_5_LIVE_MODE' => Configuration::get('MOBYTIC_CUSTOM_BLOCKS_5_LIVE_MODE', true),
        );
    }
    protected function getConfigFormValues_1()
    {
        return array(
            'MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_1_TITLE' => Configuration::get('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_1_TITLE'),
            'MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_1_URL' => Configuration::get('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_1_URL'),
        );
    }
    protected function getConfigFormValues_2()
    {
        return array(

            'MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_2_TITLE' => Configuration::get('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_2_TITLE'),
            'MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_2_URL' => Configuration::get('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_2_URL'),
        );
    }
    protected function getConfigFormValues_3()
    {
        return array(
            'MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_3_TITLE' => Configuration::get('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_3_TITLE'),
            'MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_3_URL' => Configuration::get('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_3_URL'),
        );
    }
    protected function getConfigFormValues_4()
    {
        return array(
            'MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_4_TITLE' => Configuration::get('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_4_TITLE'),
            'MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_4_URL' => Configuration::get('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_4_URL'),
        );
    }
    protected function getConfigFormValues_5()
    {
        return array(
            'MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_5_TITLE' => Configuration::get('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_5_TITLE'),
            'MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_5_URL' => Configuration::get('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_5_URL'),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess($getConfigFormValues)
    {
        $form_values = $getConfigFormValues;

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }




    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
            $this->context->controller->addCSS($this->_path . 'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path . '/views/js/front.js');
        $this->context->controller->addCSS($this->_path . '/views/css/front.css');
    }

    public function hookDisplayHome()
    {
        /* Place your code here. */

        for ($i = 1; $i <= 5; $i++) {
            $this->context->smarty->assign([
                'title_' . $i => Configuration::get('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_' . $i . '_TITLE', Tools::getValue('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_' . $i . '_TITLE')),
                'img_' . $i => $this->getImgURL('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_' . $i . '_IMG'),
                'url_' . $i => Configuration::get('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_' . $i . '_URL', Tools::getValue('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_' . $i . '_URL')),
            ]);
        }
        // $this->context->smarty->assign([
        //     'title_1' => Configuration::get('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_1_TITLE'),
        //     'img_1' => $this->getImgURL('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_1_IMG'),
        //     'url_1' => Configuration::get('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_1_URL'),

        //     'title_2' => Configuration::get('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_2_TITLE'),
        //     'img_2' => $this->getImgURL('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_2_IMG'),
        //     'url_2' => Configuration::get('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_2_URL'),

        //     'title_3' => Configuration::get('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_3_TITLE'),
        //     'img_3' => $this->getImgURL('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_3_IMG'),
        //     'url_3' => Configuration::get('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_3_URL'),

        //     'title_4' => Configuration::get('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_4_TITLE'),
        //     'img_4' => $this->getImgURL('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_4_IMG'),
        //     'url_4' => Configuration::get('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_4_URL'),

        //     'title_5' => Configuration::get('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_5_TITLE'),
        //     'img_5' => $this->getImgURL('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_5_IMG'),
        //     'url_5' => Configuration::get('MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_5_URL'),
        // ]);

        if (Configuration::get('MOBYTIC_CUSTOM_BLOCKS_5_LIVE_MODE') == true) {
            return $this->display(__FILE__, 'mobytic_block_5.tpl');
        }
    }




















    // ****************************************************
    // FONCTIONS
    // ****************************************************
    protected function uploadFileConditions($img_uploaded)
    {
        if (((bool)Tools::isSubmit('submitMobytic_custom_blocks_5Module')) == true) {
            $this->postProcess($this->getConfigFormValues());
        }

        if (((bool)Tools::isSubmit('submitMobytic_custom_blocks_5Module_1')) == true) {
            $this->postProcess($this->getConfigFormValues_1());
            return $this->checkUploadFile($img_uploaded);
        }

        if (((bool)Tools::isSubmit('submitMobytic_custom_blocks_5Module_2')) == true) {
            $this->postProcess($this->getConfigFormValues_2());
            return $this->checkUploadFile($img_uploaded);
        }

        if (((bool)Tools::isSubmit('submitMobytic_custom_blocks_5Module_3')) == true) {
            $this->postProcess($this->getConfigFormValues_3());
            return $this->checkUploadFile($img_uploaded);
        }

        if (((bool)Tools::isSubmit('submitMobytic_custom_blocks_5Module_4')) == true) {
            $this->postProcess($this->getConfigFormValues_4());
            return $this->checkUploadFile($img_uploaded);
        }

        if (((bool)Tools::isSubmit('submitMobytic_custom_blocks_5Module_5')) == true) {
            $this->postProcess($this->getConfigFormValues_5());
            return $this->checkUploadFile($img_uploaded);
        }
    }

    protected function checkUploadFile($img_uploaded)
    {
        if (isset($_FILES[$img_uploaded])) {
            $file = $_FILES[$img_uploaded];

            // File properties
            $file_name = $file['name'];
            $file_tpm = $file['tmp_name'];
            $file_size = $file['size'];
            $file_error = $file['error'];

            // Work out the file extension
            $file_ext = explode('.', $file_name);
            $file_ext = strtolower(end($file_ext));

            $allowed = array('jpg', 'png');

            if (in_array($file_ext, $allowed)) {
                move_uploaded_file($_FILES[$img_uploaded]['tmp_name'], dirname(__FILE__) . DIRECTORY_SEPARATOR . 'views\img' . DIRECTORY_SEPARATOR . $_FILES["MOBYTIC_CUSTOM_BLOCKS_5_BLOCK_1_IMG"]["name"]);
                Configuration::updateValue($img_uploaded, Tools::getValue($img_uploaded));
                return $this->displayConfirmation($this->l('Mise à jour réussie'));
            } else {
                return $this->displayError($this->l('Mauvais format'));
            }
        }
    }

    protected function displayImgInForm($img_uploaded)
    {
        $img_name = Configuration::get($img_uploaded);
        $img_url = $this->context->link->protocol_content . Tools::getMediaServer($img_name) . $this->_path . 'views/img/' . $img_name;
        return $img = $img_name ? '<div class="col-lg-6"><img src="' . $img_url . '" class="img-thumbnail" width="200"></div>' : "";
    }

    protected function getImgURL($img_uploaded)
    {
        $img_name = Configuration::get($img_uploaded);
        return $img_name ? $this->context->link->protocol_content . Tools::getMediaServer($img_name) . $this->_path . 'views/img/' . $img_name : '';
    }
}
