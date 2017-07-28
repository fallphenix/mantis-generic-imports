<?php
# Copyright (c) 2017 abdourahmane fall
# Licensed under the MIT license

/**
 * list and process (create, send ack,...) each issue
 *
 * @author abdourahmane fall  fallphenix1987@gmail.com
 */
require_once 'ServiceFactory.php';
require_once 'UtileListeIssues.php';

access_ensure_global_level(plugin_config_get('view_threshold'));
$t_can_manage = access_has_global_level(plugin_config_get('manage_threshold'));

$t_type_webservice = plugin_config_get('type');
$filter_issues = ($_GET["filter_issues"]) ? $_GET["filter_issues"] : 'all';

try {
    $_service = ServiceFactory::create($t_type_webservice);
    $_service->getListeIssueFromEndPoint($filter_issues);
    $listAllIssues = $_service->getListAllIssues();
    $SizeIssue = $_service->getSizeIssue();
    $totalIssue = $_service->getTotalIssue();
} catch (Exception $ex) {
    $totalIssue = 0;
    $listAllIssues = array();
    $SizeIssue = 0;
}

//var_dump();
//die();

$t_enum_values_priority = getListEnum("priority");
$t_enum_values_reproducibility = getListEnum("reproducibility");
$t_enum_values_resolution = getListEnum("resolution");
$t_enum_values_severity = getListEnum("severity");
//$t_enum_values_status= getListEnum("status");

$t_current_user = auth_get_current_user_id();
foreach ($listAllIssues as $Issue) {

    $t_assignedTo_id = user_get_id_by_name($Issue->assignedTo);
    $t_project_id = project_get_id_by_name($Issue->projectId);
    $t_category_id = (int) category_get_id_by_name($Issue->category, $t_project_id, false);


    $t_enum_value_priority = findPriorityByName($Issue->priority, $t_enum_values_priority);
    $t_enum_value_reproductibility = findPriorityByName($Issue->reproducibility, $t_enum_values_reproducibility);
    $t_enum_value_resolution = findPriorityByName($Issue->resolution, $t_enum_values_resolution);
    $t_enum_value_severity = findPriorityByName($Issue->severity, $t_enum_values_severity);
    // $t_enum_value_status =  findPriorityByName($Issue->status,$t_enum_values_status);



    $remoteIssue = clone $Issue;
    $remoteIssue->assignedTo = $t_assignedTo_id;
    $remoteIssue->reporterId = $t_current_user;
    $remoteIssue->projectId = $t_project_id;
    $remoteIssue->category = $t_category_id;
    $remoteIssue->view_state = VS_PUBLIC;
    $remoteIssue->priority = (!empty($t_enum_value_priority)) ? $t_enum_value_priority["id"] : null;
    $remoteIssue->reproducibility = (!empty($t_enum_value_reproductibility)) ? $t_enum_value_reproductibility["id"] : null;
    $remoteIssue->resolution = (!empty($t_enum_value_resolution)) ? $t_enum_value_resolution["id"] : null;
    $remoteIssue->severity = (!empty($t_enum_value_severity)) ? $t_enum_value_severity["id"] : null;
    //$remoteIssue->status=(!empty($t_enum_value_status))?$t_enum_value_status["id"]:null;
    $remoteIssue->labelProject = $Issue->projectId;
    $remoteIssue->description = $Issue->description;
    $remoteIssue->acknowledge = $Issue->acknowledge;
    $remoteIssue->origine = $Issue->origine;

    $Issue->assignedTo = user_get_name($t_assignedTo_id);
    $Issue->jsonData = json_encode($remoteIssue);
}

$f_severity = gpc_get_int('severity', (int) config_get('default_bug_severity'));
$f_reproducibility = gpc_get_int('reproducibility', (int) config_get('default_bug_reproducibility'));
$f_handler_id = gpc_get_int('handler_id', 0);

$t_repos = SourceRepo::load_all();

layout_page_header(plugin_lang_get('title'));

layout_page_begin();
?>

<link href="<?php echo plugin_file('iao-alert.min.css') ?>" rel="stylesheet" type="text/css" />
<div class="col-md-12 col-xs-12">
    <div class="space-10"></div>




    <div class="widget-box widget-color-blue2">
        <div class="widget-header widget-header-small">
            <h4 class="widget-title lighter">
                <?php echo plugin_lang_get('recovery_list_issues') ?>  <?php echo "( $totalIssue " . plugin_lang_get('issue_finded') . " )"; ?>
            </h4>
        </div>

        <div class="widget-body">
            <div class="widget-main no-padding">
                <div class="table-responsive">	

                    <div class="widget-toolbox padding-8 clearfix ">

                        <?php if ($t_can_manage) { ?>
                            <a class="btn btn-xs btn-primary btn-white btn-round" href="<?php echo plugin_page('config') ?>">
                                <?php echo plugin_lang_get('configuration') ?>
                            </a>
                        <?php } ?>
                        <div class="btn-group">		
                            <form id="filter-filter-issues-form" class="form-inline pull-left padding-left-8" method="get" action="<?php echo plugin_page('list_issues') ?>">
                                <input type="hidden" value="GeneriqueImport/list_issues" name="page" />
                                <select name="filter_issues" id="filter_issues" onchange="document.querySelector('#filter-filter-issues-form').submit()">

                                    <option value="all" <?php echo ($filter_issues === "all") ? "selected" : ""; ?> ><?php echo plugin_lang_get('issue_choice_all') ?></option>
                                    <option value="new" <?php echo ($filter_issues === "new") ? "selected" : ""; ?>><?php echo plugin_lang_get('issue_choice_new') ?></option>		
                                    <option value="created" <?php echo ($filter_issues === "created") ? "selected" : ""; ?>><?php echo plugin_lang_get('issue_choice_created') ?></option>			</select>
                            </form>


                        </div>
                        <table class="table table-striped table-bordered table-condensed">
                            <thead>
                                <tr class="row-category">
                                    <th ><?php echo plugin_lang_get('issue_table_project') ?></th>

                                    <th ><?php echo plugin_lang_get('issue_table_assigned_to') ?></th>
                                    <th ><?php echo plugin_lang_get('issue_table_priority') ?></th>
                                    <th ><?php echo plugin_lang_get('issue_table_severity') ?></th>
                                    <th ><?php echo plugin_lang_get('issue_table_reproductibility') ?></th>

                                    <th ><?php echo plugin_lang_get('issue_table_category') ?></th>
                                    <th ><?php echo plugin_lang_get('issue_table_date_submit') ?></th>
                                    <th ><?php echo plugin_lang_get('issue_table_summary') ?></th>

                                    <th ><?php echo plugin_lang_get('issue_table_resolution') ?></th>
                                    <th ><?php echo plugin_lang_get('issue_table_action') ?></th>

                                </tr>
                            </thead>

                            <tbody>

                                <?php foreach ($listAllIssues as $key => $IssueOject): ?>                            
                                    <tr id="issue-tr-<?php echo string_display($IssueOject->id) ?>">
                                        <td ><?php echo string_display($IssueOject->projectId) ?></td>                                      
                                        <td class="i_assigned_to"><?php echo string_display($IssueOject->assignedTo) ?> </td>
                                        <td class="i_priority"><?php echo string_display($IssueOject->priority) ?></td>
                                        <td class="i_severity"><?php echo string_display($IssueOject->severity) ?></td>
                                        <td class="i_reproducibility"><?php echo string_display($IssueOject->reproducibility) ?></td>

                                        <td class="i_category"><?php echo string_display($IssueOject->category) ?></td>
                                        <td class="i_dateSubmitted"><?php echo string_display($IssueOject->dateSubmitted) ?></td>
                                        <td class="i_summary"><?php echo string_display($IssueOject->summary) ?></td>

                                        <td class="i_resolution"><?php echo string_display($IssueOject->resolution) ?></td>
                                        <td>





                                            <a style="display:<?php echo ($IssueOject->acknowledge > 0) ? "" : "none"; ?>" href="<?php echo string_get_bug_view_url($IssueOject->acknowledge); ?>" data-label="<?php echo plugin_lang_get('issue_table_action_label_show') ?>"  class="btn btn-xs btn-success btn-round" id="show-id-<?php echo string_display($IssueOject->id) ?>"   data-issue-show-id="<?php echo string_display($IssueOject->id) ?>"  >
                                                <?php echo plugin_lang_get('issue_table_action_label_show') ?>  
                                            </a>


                                <bouton  class="btn btn-xs btn-info btn-info btn-round save-issue-row" data-label="<?php echo plugin_lang_get('issue_table_action_label_create') ?>" data-label-created="<?php echo plugin_lang_get('issue_table_action_label_created') ?>"  data-loading="<?php echo plugin_lang_get('issue_table_action_label_create_loading') ?>" data-issue-id="<?php echo string_display($IssueOject->id) ?>" id="issue-row-bouton-create-<?php echo string_display($IssueOject->id) ?>"  >
                                    <?php echo ($IssueOject->acknowledge > 0) ? plugin_lang_get('issue_table_action_label_created') : plugin_lang_get('issue_table_action_label_create') ?>  
                                </bouton>
                                <a  class="btn btn-xs btn-primary btn-white btn-round bouton-edition-issue"  id="issue-<?php echo string_display($IssueOject->id) ?>"  data-issue="<?php echo string_display($IssueOject->jsonData); ?>" data-toggle="modal" data-target="#gridSystemModal" href="#" >
                                    <?php echo plugin_lang_get('issue_table_action_label_complete') ?>  
                                </a>
                            
                                </td>

                                </tr>
                            <?php endforeach; ?>

                            </tbody>
                        </table>


                        <nav aria-label="Page navigation">
                            <ul class="pagination" id="pagination"></ul>
                        </nav>

                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<div class="modal fade" tabindex="-1"  id="gridSystemModal" role="dialog" aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">



            <div class="col-md-12 col-xs-12 modal-body">


              
                <div class="widget-box widget-color-blue2">
                    <div class="widget-header widget-header-small">
                        <h4 class="widget-title lighter">
                            <i class="ace-icon fa fa-edit"></i>
                            <?php echo plugin_lang_get('issue_modal_title') ?> - <span id="project-label"></span>		</h4>
                    </div>
                    <div class="widget-body dz-clickable">
                        <div class="widget-main no-padding">
                            <div class="table-responsive" style="overflow: hidden">
                                <table class="table table-bordered table-condensed" >
                                    <tbody>
                                        <tr >
                                            <th class="category" width="30%">
                                                <span class="required">*</span> <label for="category_id"><?php echo plugin_lang_get('issue_modal_form_category') ?></label>		</th>
                                            <td >
                                                <select id="category_id" name="category_id" class=" input-sm">

                                                </select>  

                                                <span class="help-block" id="loading-category" style="display: none"><?php echo plugin_lang_get('issue_modal_form_category_loading') ?></span>

                                            </td>
                                        </tr>

                                        <tr>
                                            <th class="category">
                                                <label for="reproducibility"><?php echo plugin_lang_get('issue_modal_form_reproducibility') ?></label>
                                            </th>
                                            <td>
                                                <select <?php echo helper_get_tab_index() ?> id="reproducibility" name="reproducibility" class="input-sm">
                                                    <?php print_enum_string_option_list('reproducibility', $f_reproducibility) ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="category">
                                                <label for="severity"><?php echo plugin_lang_get('issue_modal_form_severity') ?></label>
                                            </th>
                                            <td>
                                                <select <?php echo helper_get_tab_index() ?> id="severity" name="severity" class="input-sm">
                                                    <?php print_enum_string_option_list('severity', $f_severity) ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="category">
                                                <label for="priority"><?php echo plugin_lang_get('issue_modal_form_priority') ?></label>
                                            </th>
                                            <td>
                                                <select  id="priority" name="priority" class="input-sm">
                                                    <?php print_enum_string_option_list('priority', $f_priority) ?>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th class="category">
                                                <label for="resolution"><?php echo plugin_lang_get('issue_modal_form_resolution') ?></label>
                                            </th>
                                            <td>
                                                <select  id="resolution" name="resolution" class="input-sm">
                                                    <?php print_enum_string_option_list('resolution', config_get('default_bug_resolution')); ?>
                                                </select>
                                            </td>
                                        </tr>


                                        <tr>
                                            <th class="category">
                                                <label for="due_date"><?php echo plugin_lang_get('issue_modal_form_data_submision') ?></label>
                                            </th>
                                            <td>
                                                <input  type="text" id="due_date" name="due_date" class="datetimepicker input-sm" data-picker-locale="en-us" data-picker-format="Y-MM-DD HH:mm" size="20" maxlength="16" value="">			<i class="fa fa-calendar fa-xlg datetimepicker"></i>
                                            </td>
                                        </tr>


                                        <tr>
                                            <th class="category">
                                                <label for="handler_id"><?php echo plugin_lang_get('issue_modal_form_data_assign_to') ?></label>
                                            </th>
                                            <td>
                                                <select <?php echo helper_get_tab_index() ?> id="handler_id" name="handler_id" class="input-sm">
                                                    <option value="0" selected="selected"></option>
                                                    <?php print_assign_to_option_list($f_handler_id, 0) ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="category">
                                                <span class="required">*</span><label for="summary"><?php echo plugin_lang_get('issue_modal_form_data_summary') ?></label>
                                            </th>
                                            <td>
                                                <input  type="text" id="summary" name="summary" size="105" maxlength="128" value="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="category">
                                                <span class="required">*</span><label for="description"><?php echo plugin_lang_get('issue_modal_form_data_description') ?></label>
                                            </th>
                                            <td>
                                                <textarea class="form-control" <?php echo helper_get_tab_index() ?> id="description" name="description" cols="10" rows="5"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="category">
                                              <?php echo plugin_lang_get('issue_modal_form_data_view_status') ?> 		</th>
                                            <td>
                                                <label>
                                                    <input  type="radio" class="ace" name="view_state" value="<?php echo VS_PUBLIC ?>" checked="" />
                                                    <span class="lbl"> <?php echo lang_get('public') ?> </span>
                                                </label>
                                                &#160;&#160;&#160;&#160;
                                                <label>
                                                    <input  type="radio" class="ace" name="view_state" value="<?php echo VS_PRIVATE ?>" />
                                                    <span class="lbl"> <?php echo lang_get('private') ?> </span>
                                                </label>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="widget-toolbox padding-8 clearfix">

                            <input type="submit" class="btn btn-primary btn-white btn-round" id="validation-colonne-issue" value="<?php echo plugin_lang_get('issue_modal_form_data_valider') ?>">
                            <input type="submit" class="btn btn-primary btn-white btn-round save-issue-row-modal"  value="Créer">
                        </div>
                    </div>
                </div>

            </div>





        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<input type="hidden" id="size-all-issues"  value="<?php echo $SizeIssue; ?>"  />
<input type="hidden" id="url-remote-server-create"  value="<?php echo plugin_page('ajax_create_issue'); ?>"  />
<input type="hidden" id="url-remote-server-ack"  value="<?php echo plugin_page('ajax_acknowledge_issue'); ?>"  />
<input type="hidden" id="url-remote-server-category"  value="<?php echo plugin_page('ajax_list_category_by_project'); ?>"  />
<input type="hidden" id="issue-notification-create"  value="<?php echo plugin_lang_get('issue_notification_create'); ?>"  />
<input type="hidden" id="issue_notification_ack"  value="<?php echo plugin_lang_get('issue_notification_ack'); ?>"  />
<input type="hidden" id="issue_notification_error_ack"  value="<?php echo plugin_lang_get('issue_notification_error_ack'); ?>"  />
<input type="hidden" id="issue_notification_error"  value="<?php echo plugin_lang_get('issue_notification_error'); ?>"  />
<input type="hidden" id="issue_pagination_previous"  value="<?php echo plugin_lang_get('issue_pagination_previous'); ?>"  />
<input type="hidden" id="issue_pagination_next"  value="<?php echo plugin_lang_get('issue_pagination_next'); ?>"  />

<script src="<?php echo plugin_file('generic.js') ?>"></script>
<script src="<?php echo plugin_file('iao-alert.jquery.min.js') ?>"></script>
<script src="<?php echo plugin_file('jquery.twbsPagination.min.js') ?>"></script>
<?php
layout_page_end();
