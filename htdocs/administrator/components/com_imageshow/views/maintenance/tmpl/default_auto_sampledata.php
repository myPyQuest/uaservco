<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_auto_sampledata.php 13974 2012-07-13 09:35:02Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
?>

<div id="jsn-sample-data">
	<div class="alert alert-warning" id="jsn-sample-data-text-alert">
		<p><span class="label label-important"><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_WARNING'); ?></span></p>
		<?php echo JText::_('MAINTENANCE_SAMPLE_DATA_INSTALL_SUGGESTION'); ?>
	</div>
	<div id="jsn-sample-data-install">
		<form action="index.php?option=com_imageshow&controller=maintenance&type=sampledata" method="post" enctype="multipart/form-data">
			<div id="jsn-start-installing-sampledata">
				<div class="control-group">
					<label for="agree_install_sample_local" class="checkbox">
						<input onclick="return setButtonState(this.form);" type="checkbox" name="agree_install_sample" id="agree_install_sample_local" value="1" />
						<strong><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_AGREE_INSTALL_SAMPLE_DATA'); ?></strong>
					</label>
				</div>
				<div class="form-actions">
					<button class="btn btn-primary disabled agree_install_sample_local" type="button" name="button_installation_data" onclick="JSNISSampleData.installSampleData();" disabled="disabled"><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_INSTALL_SAMPLE_DATA');?></button>
				</div>
			</div>
			<div id="jsn-installing-sampledata">
				<p><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_AFTER_DOWNLOAD_SUGGESTION'); ?></p>
				<ul>
					<li id="jsn-download-sample-data-package-title"><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_DOWNLOAD_SAMPLE_DATA_PACKAGE'); ?>.
						<span class="jsn-icon16 icon-loading" id="jsn-downloading-sampledata"></span>
						<span class="jsn-icon16 icon-check" id="jsn-download-sampledata-success"></span>
						<span class="jsn-icon16 icon-failed" id="jsn-span-unsuccessful-downloading-sampledata"></span>
						<br />
						<p id="jsn-span-unsuccessful-downloading-sampledata-message" class="jsn-text-important"></p>
					</li>
					<li id="jsn-install-sample-data-package-title"><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_INSTALL_SAMPLE_DATA'); ?>.
						<span class="jsn-icon jsn-icon-loading" id="jsn-span-installing-sampledata-state"></span>
						<span class="jsn-icon jsn-icon-check" id="jsn-span-successful-installing-sampledata"></span>
						<span class="jsn-icon jsn-icon-failed" id="jsn-install-sampledata-unsuccessful"></span>
						<br />
						<p id="jsn-span-unsuccessful-installing-sampledata-message" class="jsn-text-important"></p>
						<div class="jsn-sampledata-warnings-text" id="jsn-sampledata-warnings">
							<ul id="jsn-sampledata-ul-warnings">
							</ul>
							<p id="jsn-sampledata-link-install-all-requried-plugins"><a id="jsn-sampledata-a-link-install-all-requried-plugins" rel="{handler: 'iframe', size: {x: 450, y: 250}}" onclick="JSNISSampleData.installAllRequiredPlugins(false);" class="jsn-link-action"><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_INSTALL_ALL_REQUIRED_PLUGINS'); ?></a></p>
						</div>
					</li>
				</ul>
			</div>
			<div id="jsn-installing-sampledata-unsuccessfully">
				<div class="form-actions">
					<button class="btn btn-primary" type="button" name="button_installation_sampledata_unsuccessfully" onclick="window.top.location='index.php?option=com_imageshow&controller=maintenance&type=data&tab=0';"><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_CANCEL');?></button>
				</div>
			</div>
			<div id="jsn-installing-sampledata-successfully">
				<hr />
				<h3 class="jsn-text-success"><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_SAMPLE_DATA_IS_SUCCESSFULLY_INSTALLED'); ?></h3>
				<p><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_CONGRATULATIONS_NOW_YOU_CAN_OPERATE_ON_SAMPLE_SHOWLISTS_AND_SHOWCASES'); ?></p>
				<div class="form-actions">
					<button class="btn btn-primary agree_install_sample_local" type="button" name="button_installation_sampledata_finish" onclick="window.top.location='index.php?option=com_imageshow';"><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_FINISH');?></button>
				</div>
			</div>
		</form>
	</div>
</div>