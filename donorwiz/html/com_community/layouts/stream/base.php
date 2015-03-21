<?php
/**
 * @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
 * @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author iJoomla.com <webmaster@ijoomla.com>
 * @url https://www.jomsocial.com/license-agreement
 * The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
 * More info at https://www.jomsocial.com/license-agreement
 */
defined('_JEXEC') or die();

   if(!isset($isSingleActivity)){
       $isSingleActivity = false;
   }

$appLib      = CAppPlugins::getInstance();
$filter      = JFactory::getApplication()->input->get('filter',$filter);
$filterValue = JFactory::getApplication()->input->get('value');
$actId       = JFactory::getApplication()->input->get('actid');
$date        = JFactory::getDate();

if ($config->get('activitydateformat') == "lapse") {
    $createdTime = CTimeHelper::timeLapse($date);
} else {
    $createdTime = $date->format($config->get('profileDateFormat'));
}

// 2. welcome message for new installation
if (isset($freshInstallMsg)) :
    ?>
    <div class="cAlert" style="margin-top: 10px">
        <?php echo $freshInstallMsg; ?>
    </div>
<?php endif; ?>

<?php if (count($activities) == 0 && $actId) { ?>
    <div class="cAlert" style="margin-top: 10px">
        <?php echo JText::_('COM_COMMUNITY_STREAM_CONTENT_UNAVAILABLE'); ?>
    </div>
<?php } ?>

<!-- begin: .joms-stream__wrapper -->
<div class="joms-stream__wrapper">

    <div class="joms-load-latest joms-js--stream-latest" style="display:none;"></div>

    <!-- begin: .joms-stream__container -->
    <div class="joms-stream__container" data-filter="<?php echo $filter; ?>" data-filter-value="<?php echo $filterValue; ?>" data-filterid="<?php echo $filterId; ?>" data-groupid="<?php $this->groupId; ?>" data-eventid="<?php $this->eventId; ?>" data-profileid="<?php $this->profileId; ?>">

        <?php foreach ($activities as $act): ?>
            <?php
            if (empty($act->app)) {
                continue;
            }
            ?>
			
            <?php
            /* If actor is no longer exists than we do not display it */
            if ($act->actor != 0) {
                $actor = $act->actor;
            } else {
                if ($act->params instanceof JRegistry) {

                } else {
                    $act->params = new JRegistry($act->params);
                }
                $actor = $act->params->get('actors');
            }
            $tUser = CFactory::getUser($actor);
            /* User not exists and this's not system activity */
            if ($tUser->_userid == 0 && strpos($act->app, 'system.') === false)
                continue;
            ?>
            <?php
            //special case for video in normal post
            $headMetas = $act->params;
            $isVideo = '';
            /* We do convert into JRegistry to make it easier to use */
            if (!empty($headMetas) && $headMetas != '{}') {
                $headMetaParams = json_decode($headMetas);
                $headMetaParams = isset($headMetaParams->headMetas) ? json_decode($headMetaParams->headMetas) : null;
                if (isset($headMetaParams->type) && $headMetaParams->type == 'video') {
                    $isVideo = 'videos';
                }
            }
            ob_start();
            ?>

            <div class="joms-stream joms-js--stream joms-js--stream-<?php echo $act->id; ?>" data-stream-id="<?php echo $act->id; ?>" data-stream-type="<?php echo $act->app; ?>">
				
				<!-- yesinternet : Donate button inside activity stream-->
				<?php	$donorwizUser=new DonorwizUser(intval($act->actor));?>
				<?php 	$isBeneficiaryDonations = $donorwizUser-> isBeneficiary('com_dw_donations');?>
				<?php if ($isBeneficiaryDonations) :?>
				<?php JFactory::getLanguage()->load('com_dw_donations');?>
				<a href="<?php echo JRoute::_('donate/?beneficiary_id='.$act->actor);?>" class="uk-button uk-button-primary uk-button-mini uk-float-right">
					<i class="uk-icon uk-icon-euro"></i>
					<?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_DONATE'); ?>
				</a>
				<?php endif;?>
				<?php 	$isBeneficiaryVolunteers = $donorwizUser-> isBeneficiary('com_dw_opportunities');?>
				<?php if ($isBeneficiaryVolunteers) :?>
				<?php JFactory::getLanguage()->load('com_dw_opportunities');?>
				<a href="<?php echo JRoute::_('volunteer/?created_by='.$act->actor);?>" class="uk-button uk-button-blank uk-button-mini uk-float-right" style="border:0;">
					<i class="uk-icon uk-icon-users"></i>
					<?php echo JText::_('COM_DW_OPPORTUNITIES_OPPORTUNITY_VOLUNTEERS'); ?>
				</a>
				<?php endif;?>
				<!-- yesinternet -->

				<?php

                // echo $act->app;

                ob_start();
                $this->set('act', $act);
                $act->createdtime = $createdTime;
                // Load ONLY known app
                switch ($act->app) {
                    case 'users.featured':
                        $this->load('stream/profile-featured');
                        break;

                    case 'profile.avatar.upload':
                    case 'profile':
                        $this->load('stream/status');
                        break;
                    case 'profile.status.share':
                        $this->load('activities.profile.status.share');
                        break;
                    case 'albums.comment':
                    case 'albums':
                        $this->load('activities.albums');
                        break;

                    case 'albums.featured':
                    case 'photos.comment':
                    case 'photos':
                        $this->load('stream/photos');
                        break;

                    case 'videos.featured':
                        $this->load('activities/videos/featured');
                        break;
                    case 'videos.comment':
                    case 'videos.linking':
                    case 'videos':
                        $this->load('stream/videos');
                        break;

                    case 'friends.connect':
                        $this->load('stream/friend-connect');
                        break;

                    case 'groups.featured':
                    case 'groups.wall':
                    case 'groups.join':
                    case 'groups.bulletin':
                    case 'groups.discussion':
                    case 'groups.discussion.reply':
                    case 'groups.update':
                    case 'groups':
                        $this->load('activities/groups/base');
                        break;
                    case 'groups.avatar.upload':
                        $this->load('activities.groups.avatar.upload');
                        break;
                    case 'events.featured':
                    case 'events.wall':
                    case 'events.attend':
                    case 'events.update':
                    case 'events':
                        $this->load('stream/events');
                        break;
                    case 'events.avatar.upload':
                        $this->load('activities.events.avatar.upload');
                        break;
                    case 'system.message':
                    case 'system.videos.popular':
                    case 'system.photos.popular':
                    case 'system.members.popular':
                    case 'system.photos.total':
                    case 'system.groups.popular':
                    case 'system.members.registered':
                        $this->load('activities/system/base');
                        break;

                    case 'app.install':
                        $this->load('activities.app.install');
                        break;
                    case 'profile.like':
                    case 'groups.like':
                    case 'events.like':
                    case 'photo.like':
                    case 'videos.like':
                    case 'album.like':
                        $this->load('activities.likes');
                        break;
                    case 'cover.upload':
                        $this->load('activities.photos.cover');
                        break;
                    default:
                        // If none of the above, only load 3rd party stream data
                        // For some known stream, convert it into new app naming, which is the folder/plugin format
                        // try load the plugin getStreamHTML
                        $appName = explode('.', $act->app);
                        $appName = $appName[0];

                        $plugin = $appLib->getPlugin($appName);

                        if (!is_null($plugin)) {
                            if (method_exists($plugin, 'onCommunityStreamRender')) {
                                $stream = $plugin->onCommunityStreamRender($act);

                                if (!isset($stream->access)) {
                                    $stream->access = 10;
                                }

                                $date = JFactory::getDate($act->created);
                                if ( $config->get('activitydateformat') == "lapse" ) {
                                $createdTime = CTimeHelper::timeLapse($date);
                                } else {
                                $createdTime = $date->format($config->get('profileDateFormat'));
                                }

                                $stream->createdtime = $createdTime;

                                $this->set('stream', $stream);
                                $this->load('stream/base-extended');
                            }
                        } else {
                            // Process the old ways
                            $user = CFactory::getUser($act->actor);
                            $actorLink = '<a href="' . CUrlHelper::userLink($user->id) . '">' . $user->getDisplayName() . '</a>';
                            $title = $act->title;

                            // Handle 'single' view exclusively
                            $title = preg_replace('/\{multiple\}(.*)\{\/multiple\}/i', '', $title);
                            $search = array('{single}', '{/single}');
                            $title = CString::str_ireplace($search, '', $title);
                            $title = CString::str_ireplace('{actor}', $actorLink, $title);

                            //get the time
                            $date = JFactory::getDate($act->created);
                            if ( $config->get('activitydateformat') == "lapse" ) {
                                $createdTime = CTimeHelper::timeLapse($date);
                            } else {
                                $createdTime = $date->format($config->get('profileDateFormat'));
                            }

                            $stream = new stdClass();
                            $stream->actor = $user;
                            $stream->target = null;
                            $stream->headline = $title;
                            $stream->message = $act->content;
                            $stream->access = $act->access;
                            $stream->createdtime = $createdTime;
                            $stream->attachments = array();

                            $this->set('stream', $stream);
                            $this->load('stream/base-extended');
                        }

                        break;
                }

                $html = ob_get_contents();
                $html = trim($html);
                $showStream = true;
                ob_end_clean();
                echo $html;

                // Show debug message
                if (empty($html)) {
                    // enable only during stream debugging
                    // echo 'UNPROCESSED STREAM POST: ' . $act->app;
                    $showStream = false;
                }

                ?>
            </div>

            <?php
            $html = ob_get_contents();
            $html = trim($html);
            ob_end_clean();

            // Only show if there is a content t be shown
            if ($showStream) {
                echo $html;
            }
            ?>

        <?php endforeach; ?>

    </div>
    <!-- end: .joms-stream__container -->

    <?php if ($isSingleActivity) { ?>
    <script>joms_singleactivity = true;</script>
    <?php } ?>

    <?php if ($showMoreActivity && (!$isSingleActivity)) { ?>
    <div class="joms-stream__loadmore cActivity-LoadMore" id="activity-more">
        <a class="more-activity-text joms-button--primary joms-button--full" href="javascript:" onclick="joms.api.streamsLoadMore();"><?php echo JText::_('COM_COMMUNITY_MORE'); ?></a>
        <div class="loading"></div>
    </div>

    <?php if ($my->id != 0) { ?>
    <script>
        joms_infinitescroll = +'<?php echo $config->get("infinitescroll", 0); ?>';
        joms_autoloadtrigger = +'<?php echo $config->get("autoloadtrigger", 100); ?>';
    </script>
    <?php } ?>

    <?php } ?>

    <?php if ($my->id != 0 && (!$isSingleActivity) && CSystemHelper::isComponentExists('com_adagency') && JComponentHelper::getComponent('com_adagency', true)->enabled) { ?>
    <script>joms_adagency = 1;</script>
    <?php } ?>

</div>
<!-- end: .joms-stream__wrapper -->
