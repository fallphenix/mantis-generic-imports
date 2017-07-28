<?php
# Copyright (c) 2017 abdourahmane fall
# Licensed under the MIT license

/**
 * Config form for api connexions
 *
 * @author abdourahmane fall  fallphenix1987@gmail.com
 */
auth_reauthenticate();
access_ensure_global_level(plugin_config_get('manage_threshold'));

layout_page_header(plugin_lang_get('title'));
layout_page_begin();
print_manage_menu();

$t_config_url = plugin_config_get('url');
?>

<br/>
<div class="col-md-12 col-xs-12">
   <div class="space-10"></div>
   <div class="form-container">
      <form action="<?php echo plugin_page('config_update') ?>" method="post" class="form-inline">
         <div class="widget-box widget-color-blue2">
            <div class="widget-header widget-header-small">
               <h4 class="widget-title lighter">
                  <?php echo plugin_lang_get('configuration_title'); ?>
               </h4>
            </div>
            <div class="widget-body">
               <div class="widget-main no-padding">
                  <div class="table-responsive">
                     <div class="widget-toolbox padding-8 clearfix">
                        <a class="btn btn-xs btn-primary btn-white btn-round" href="<?php echo plugin_page('list_issues') ?>">
                        <?php echo plugin_lang_get('recovery_list_issues') ?>
                        </a>
                     </div>
                     <table class="table table-striped table-bordered table-condensed">
                        <?php echo form_security_field('plugin_GeneriqueImport_config_update') ?>
                        <tr>
                           <td width="25%" class="category"><?php echo plugin_lang_get('config_url') ?></td>
                           <td>
                              <input name="url" required="true" type="text" class="input-sm" size="50" maxlength="500" placeholder="https://api.mon-domaine.com/issues" value="<?php echo string_attribute(plugin_config_get('url')); ?>"/>
                           </td>
                        </tr>
                        <tr>
                           <td width="25%" class="category"><?php echo plugin_lang_get('config_username') ?></td>
                           <td>
                              <input name="username"  type="text" class="input-sm" size="50"  value="<?php echo string_attribute(plugin_config_get('username')); ?>"/>
                           </td>
                        </tr>
                        <tr>
                           <td width="25%" class="category"><?php echo plugin_lang_get('config_password') ?></td>
                           <td>
                              <input name="password"  type="text" class="input-sm" size="50"  value="<?php echo string_attribute(plugin_config_get('password')); ?>"/>
                           </td>
                        </tr>
                        <tr>
                           <td  width="25%" class="category"><?php echo plugin_lang_get('config_pooling') ?></td>
                           <td>
                              <input name="pooling" required="true" type="number" class="input-sm"  value="<?php echo plugin_config_get('pooling'); ?>"/>
                              <span class="small"><?php echo plugin_lang_get('config_label_seconde') ?></span>
                           </td>
                        </tr>
                        <tr>
                           <td width="25%" class="category"><?php echo plugin_lang_get('config_type') ?></td>
                           <td>
                              <select name="type" class="input-sm">
                                 <option value="REST" <?php if (plugin_config_get('type') === "REST") echo "selected"; ?> >REST</option>
                                 <option value="SOAP" <?php if (plugin_config_get('type') === "SOAP") echo "selected"; ?> >SOAP</option>
                              </select>
                           </td>
                        </tr>
                        <tr>
                           <td  width="25%" class="category"><?php echo plugin_lang_get('config_size_per_page'); ?></td>
                           <td>
                              <input name="size_page" required="true" type="number" class="input-sm"  value="<?php echo plugin_config_get('size_page'); ?>"/>
                           </td>
                        </tr>
                        <tr>
                           <td width="25%" class="category"><?php echo plugin_lang_get('config_username_api_mantis'); ?></td>
                           <td>
                              <input name="username_api_mantis"  type="text" class="input-sm" size="50"  value="<?php echo string_attribute(plugin_config_get('username_api_mantis')); ?>"/>
                           </td>
                        </tr>
                        <tr>
                           <td width="25%" class="category"><?php echo plugin_lang_get('config_password_api_mantis'); ?></td>
                           <td>
                              <input name="password_api_mantis"  type="text" class="input-sm" size="50"  value="<?php echo string_attribute(plugin_config_get('password_api_mantis')); ?>"/>
                           </td>
                        </tr>
                     </table>
                  </div>
               </div>
               <div class="widget-toolbox padding-8 clearfix">
                  <input type="submit" class="btn btn-primary btn-white btn-round" value="<?php echo plugin_lang_get('update_configuration') ?>" />
               </div>
            </div>
         </div>
      </form>
   </div>
</div>
<?php
layout_page_end();

