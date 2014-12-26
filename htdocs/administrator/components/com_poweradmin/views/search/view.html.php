<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: view.html.php 16460 2012-09-26 09:52:25Z hiepnv $
-------------------------------------------------------------------------*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.view');

require_once JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_poweradmin' . DS . 'defines.poweradmin.php' ;
JSNFactory::localimport('libraries.joomlashine.document.media');

class PoweradminViewSearch extends JView
{
	/**
	 * @var JApplication
	 */
	protected $app;

	/**
	 * @var JLanguage
	 */
	protected $language;

	/**
	 * @var JUser
	 */
	protected $user;

	public function display($tpl = null)
	{
		$this->app = JFactory::getApplication();
		$this->user = JFactory::getUser();
		$this->document = JFactory::getDocument();

		$this->language = JFactory::getLanguage();
		$this->language->load('plg_system_jsnpoweradmin');

		$this->keyword = $this->app->getUserStateFromRequest('search.keyword', 'keyword', '');
		$this->coverage = $this->app->getUserStateFromRequest('search.coverage', 'coverages', '');

		// Create coverages select box
		$this->coverages = JHTML::_('select.genericlist', $this->getCoverages(), 'coverages', null, 'value', 'text', $this->coverage);
		$this->state = $this->get('state');

		$this->document->addStyleSheet('components/com_poweradmin/assets/css/styles.css');

		$this->addToolbar();
		$this->populateSearch();

		parent::display($tpl);
	}

	/**
	 * Add toolbar to page
	 * @return void
	 */
	private function addToolbar () {
		JToolBarHelper::title(JText::_('JSN_SITE_SEARCH_TITLE'), 'poweradmin-search');
		//JToolBarHelper::help('JSNPOWERADMIN_HELP_SITESEARCH');
	}

	/**
	 * Return a list of coverages that use to generate select box
	 * @return Array
	 */
	private function getCoverages () {
		$coverages = array();
		foreach (PoweradminHelper::getSearchCoverages() as $coverage) {
			if ($coverage == 'adminmenus')
				continue;

			if ($coverage == 'k2' && !defined('K2_JVERSION'))
				continue;

			$coverages[] = array(
				'value' => $coverage,
				'text'	=> JText::_('PLG_JSNADMINBAR_SEARCH_COVERAGE_' . strtoupper($coverage),true)
			);
		}

		return $coverages;
	}

	/**
	 * Retrieve search coverage configuration
	 * @param String $coverage
	 * @return Array
	 */
	private function getConfiguration ($coverage) {
		$configurations = array(
			'articles' => array(
				'language' 		=> 'com_content',
				'file'			=> 'components/com_content/models/articles.php',
				'view'			=> 'articles',
				'name'			=> 'ContentModelArticles',
				'order'			=> 'a.title'
			),

			'components'		=> array(
				'tabs'	=> array(
					'com_banners' => array(
						'title'			=> 'Banners',
						'language' 		=> 'com_banners',
						'file'			=> 'components/com_banners/models/banners.php',
						'view'			=> 'banners_items',
						'name'			=> 'BannersModelBanners',
						'order'			=> 'name'
					),

					'com_banners_categories' => array(
						'title'			=> 'Banners Categories',
						'language' 		=> 'com_categories',
						'file'			=> 'components/com_categories/models/categories.php',
						'view'			=> 'categories',
						'name'			=> 'CategoriesModelCategories',
						'order'			=> 'a.lft',
						'filters'		=> array(
							'filter.extension' => 'com_banners'
						)
					),

					'com_banners_clients' => array(
						'title'			=> 'Banners Clients',
						'language' 		=> 'com_banners',
						'file'			=> 'components/com_banners/models/clients.php',
						'view'			=> 'banners_clients',
						'name'			=> 'BannersModelClients',
						'order'			=> 'a.name'
					),

					'com_contact' => array(
						'title'			=> 'Contacts',
						'language' 		=> 'com_contact',
						'file'			=> 'components/com_contact/models/contacts.php',
						'view'			=> 'contacts_items',
						'name'			=> 'ContactModelContacts',
						'order'			=> 'name'
					),

					'com_contact_categories' => array(
						'title'			=> 'Contacts Categories',
						'language' 		=> 'com_categories',
						'file'			=> 'components/com_categories/models/categories.php',
						'view'			=> 'categories',
						'name'			=> 'CategoriesModelCategories',
						'order'			=> 'a.lft',
						'filters'		=> array(
							'filter.extension' => 'com_contact'
						)
					),

					'com_messages' => array(
						'title'			=> 'Messages',
						'language' 		=> 'com_messages',
						'file'			=> 'components/com_messages/models/messages.php',
						'view'			=> 'messages',
						'name'			=> 'MessagesModelMessages',
						'order'			=> 'a.date_time'
					),

					'com_newsfeeds' => array(
						'title'			=> 'Feeds',
						'language' 		=> 'com_newsfeeds',
						'file'			=> 'components/com_newsfeeds/models/newsfeeds.php',
						'view'			=> 'feeds',
						'name'			=> 'NewsfeedsModelNewsfeeds',
						'order'			=> 'a.name'
					),

					'com_newsfeeds_categories' => array(
						'title'			=> 'Feeds Categories',
						'language' 		=> 'com_categories',
						'file'			=> 'components/com_categories/models/categories.php',
						'view'			=> 'categories',
						'name'			=> 'CategoriesModelCategories',
						'order'			=> 'a.lft',
						'filters'		=> array(
							'filter.extension' => 'com_newsfeeds'
						)
					),

					'com_weblinks' => array(
						'title'			=> 'Web Links',
						'language' 		=> 'com_weblinks',
						'file'			=> 'components/com_weblinks/models/weblinks.php',
						'view'			=> 'weblinks',
						'name'			=> 'WeblinksModelWeblinks',
						'order'			=> 'a.title',
					),

					'com_weblinks_categories' => array(
						'title'			=> 'Web Links Categories',
						'language' 		=> 'com_categories',
						'file'			=> 'components/com_categories/models/categories.php',
						'view'			=> 'categories',
						'name'			=> 'CategoriesModelCategories',
						'order'			=> 'a.lft',
						'filters'		=> array(
							'filter.extension' => 'com_weblinks'
						)
					)
				)
			),

			'categories' => array(
				'language' 		=> 'com_categories',
				'file'			=> 'components/com_categories/models/categories.php',
				'view'			=> 'categories',
				'name'			=> 'CategoriesModelCategories',
				'order'			=> 'a.lft'
			),

			'modules' => array(
				'language' 		=> 'com_modules',
				'file'			=> 'components/com_modules/models/modules.php',
				'view'			=> 'modules',
				'name'			=> 'ModulesModelModules',
				'order'			=> 'a.title'
			),

			'plugins' => array(
				'language' 		=> 'com_plugins',
				'file'			=> 'components/com_plugins/models/plugins.php',
				'view'			=> 'plugins',
				'name'			=> 'PluginsModelPlugins',
				'order'			=> 'a.title'
			),

			'menus' => array(
				'language' 		=> 'com_menus',
				'file'			=> 'components/com_poweradmin/models/menusearch.php',
				'view'			=> 'menus',
				'name'			=> 'PowerAdminModelMenuSearch',
				'order'			=> 'a.lft'
			),

			'templates' => array(
				'language' 		=> 'com_templates',
				'file'			=> 'components/com_templates/models/styles.php',
				'view'			=> 'templates',
				'name'			=> 'TemplatesModelStyles',
				'order'			=> 'a.title'
			),

			'users' => array(
				'language' 		=> 'com_users',
				'file'			=> 'components/com_users/models/users.php',
				'view'			=> 'users',
				'name'			=> 'UsersModelUsers',
				'order'			=> 'a.name'
			),

			'k2' => array(
				'tabs' => array(
					'com_k2' => array(
						'title'			=> 'K2 Items',
						'language' 		=> 'com_k2',
						'file'			=> 'components/com_poweradmin/models/extensions/k2itemsearch.php',
						'view'			=> 'extensions/k2_items',
						'name'			=> 'PoweradminModelK2ItemSearch',
						'order'			=> 'i.id'
					),

					'com_k2_categories' => array(
						'title'			=> 'K2 Categories',
						'language' 		=> 'com_k2',
						'file'			=> 'components/com_poweradmin/models/extensions/k2categorysearch.php',
						'view'			=> 'extensions/k2_categories',
						'name'			=> 'PoweradminModelK2CategorySearch',
						'order'			=> 'c.ordering'
					)
				)
			),
			
			'zoo' => array(
					'tabs' => array(
							'com_zoo' => array(
									'title'			=> 'Zoo Items',
									'language' 		=> 'com_zoo',
									'file'			=> 'components/com_poweradmin/models/extensions/zooitemsearch.php',
									'view'			=> 'extensions/zoo_items',
									'name'			=> 'PoweradminModelZooItemSearch',
									'order'			=> 'a.id'
							),
							
							'com_zoo_categories'	=> array(
										'title'			=> 'Zoo Categories',
										'language' 		=> 'com_zoo',
										'file'			=> 'components/com_poweradmin/models/extensions/zoocategorysearch.php',
										'view'			=> 'extensions/zoo_categories',
										'name'			=> 'PoweradminModelZooCategorySearch',
										'order'			=> 'i.id'
							)
										
					)
			),
			
			'easyblog' => array(
					'tabs' => array(
							'com_easyblog' => array(
									'title'			=> 'Easyblog Posts',
									'language' 		=> 'com_easyblog',
									'file'			=> 'components/com_poweradmin/models/extensions/easyblogitemsearch.php',
									'view'			=> 'extensions/easyblog_items',
									'name'			=> 'PoweradminModelEasyblogItemSearch',
									'order'			=> 'a.id'
							),
							
							'com_easyblog_categories'	=> array(
										'title'			=> 'Easyblog Categories',
										'language' 		=> 'com_easyblog',
										'file'			=> 'components/com_poweradmin/models/extensions/easyblogcategorysearch.php',
										'view'			=> 'extensions/easyblog_categories',
										'name'			=> 'PoweradminModelEasyblogCategorySearch',
										'order'			=> 'i.id'
							)
										
					)
			),
			
			'virtuemart' => array(
					'tabs' => array(
							'com_vituemart' => array(
									'title'			=> 'Vituemart Products',
									'language' 		=> 'com_virtuemart',
									'file'			=> 'components/com_poweradmin/models/extensions/vmproductsearch.php',
									'view'			=> 'extensions/vm_product',
									'name'			=> 'PoweradminModelVmProductSearch',
									'order'			=> 'a.id'
							),
							'com_vituemart_categories' => array(
									'title'			=> 'Vituemart Categories',
									'language' 		=> 'com_virtuemart',
									'file'			=> 'components/com_poweradmin/models/extensions/vmcategorysearch.php',
									'view'			=> 'extensions/vm_categories',
									'name'			=> 'PoweradminModelVmCategorySearch',
									'order'			=> 'a.id'
							)
										
					)
			)
		);

		if (!isset($configurations[$coverage]))
			return null;

		$config = $configurations[$coverage];
		if (!isset($config['tabs']))
			return $config;

		$installedComponents = PoweradminHelper::getInstalledComponents();
		$this->tabs = array();
		foreach ($config['tabs'] as $key => $tab) {
			if ($coverage == 'components' && !in_array($tab['language'], $installedComponents))
				continue;

			$model = $this->getItemModel($tab, $this->state, $key);
			$total = $model->getTotal();
			
			if ($total > 0) {
				$this->tabs[$key] = array(
					'title' => "{$tab['title']} ({$total})",
					'selected' => false
				);
			}
		}

		$selectedTab = $this->app->getUserStateFromRequest("components.selected", 'tab', null);
		if ($selectedTab == null || !isset($this->tabs[$selectedTab])) {
			$tabKeys = array_keys($this->tabs);
			$selectedTab = array_shift($tabKeys);

			$this->app->setUserState('components.selected', $selectedTab);
		}

		if (empty($this->tabs))
			return null;

		$this->tabs[$selectedTab]['selected'] = true;
		return $config['tabs'][$selectedTab];
	}

	/**
	 * Load model instance that determined by coverage.
	 * Retrieve results and assign it to view
	 * @return void
	 */
	private function populateSearch () {
		$this->config = $this->getConfiguration($this->coverage);
		if ($this->config == null)
			return;	
		// Create model instance
		$model = $this->getItemModel($this->config, $this->state, $this->coverage);

		$this->items = $model->getItems();
		$this->pagination = $model->getPagination();
	}

	/**
	 * Return an instance of a model that loaded base on configuration
	 * @param mixed $config
	 * @param object $state
	 * @param string $coverage
	 * @return JModelList
	 */
	private function getItemModel ($config, $state, $coverage) {
		// Load model file
		require_once JPATH_ADMINISTRATOR.DS.$config['file'];

		// Load component language
		$this->language->load($config['language'], JPATH_ADMINISTRATOR);

		// Create model instance
		$model = new $config['name'](array('state' => $state));
		$model->getState('filter.search');

		$currentCoverage = $this->app->getUserStateFromRequest('components.selected', 'tab');
		if ($currentCoverage == $coverage) {
			$order = $this->app->getUserStateFromRequest("{$currentCoverage}.{$config['name']}.order", 'filter_order', $config['order']);
			$orderDir = $this->app->getUserStateFromRequest("{$currentCoverage}.{$config['name']}.orderDir", 'filter_order_Dir', 'asc');

			$state->set('list.ordering', $order);
			$state->set('list.direction', $orderDir);
		}

		$state->set('filter.search', $this->keyword);
		$state->set('filter.published', 'all');
		$state->set('filter.state', 'all');

		if (isset($config['filters']) && is_array($config['filters'])) {
			foreach ($config['filters'] as $key => $value) {
				$state->set($key, $value);
			}
		}

		return $model;
	}
}