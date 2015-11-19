<article class="uk-article dw-article" <?php if ($permalink) echo 'data-permalink="'.$permalink.'"'; ?>>
	
	<?php if ($image && $image_alignment == 'none') : ?>
    	<?php 
			$video=DonorwizVideo::getYouTubeVideoHTMLForArticles($image);
			if($video){
				echo $video;
			}else{
		?>
		<?php if ($url) : ?>
			<a href="<?php echo $url; ?>" title="<?php echo $image_caption; ?>"><img src="<?php echo $image; ?>" alt="<?php echo $image_alt; ?>"></a>
		<?php else : ?>
			<img src="<?php echo $image; ?>" alt="<?php echo $image_alt; ?>">
		<?php endif; ?>
        <?php
			}
		?>
	<?php endif; ?>

	<?php if ($title) : ?>
	<h1 class="uk-article-title">
		<?php if ($url && $title_link) : ?>
			<a href="<?php echo $url; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a>
		<?php else : ?>
			<?php echo $title; ?>
		<?php endif; ?>
	</h1>
	<?php endif; ?>

	<?php echo $hook_aftertitle; ?>

	<?php if ($author || $date || $category) : ?>
	<div class="uk-article-meta">

		<?php

			$author   = ($author && $author_url) ? '<a href="'.$author_url.'">'.$author.'</a>' : $author;
			$date     = ($date) ? ($datetime ? '<time datetime="'.$datetime.'">'.JHtml::_('date', $date, JText::_('DATE_FORMAT_LC3')).'</time>' : JHtml::_('date', $date, JText::_('DATE_FORMAT_LC3'))) : '';
			$category = ($category && $category_url) ? '<a href="'.$category_url.'">'.$category.'</a>' : $category;

			/*if($author && $date) {
				printf(JText::_('TPL_WARP_META_AUTHOR_DATE'), $author, $date);
			} elseif ($author) {
				printf(JText::_('TPL_WARP_META_AUTHOR'), $author);
			} elseif ($date) {
				printf(JText::_('TPL_WARP_META_DATE'), $date);
			}*/
		?>	
			<!-- Donorwiz -->
            <?php 
				JHtml::stylesheet(Juri::base().'components/com_community/templates/jomsocial/assets/css/style.css');
            	$user = CFactory::getUser($author_id);
				$actorLink = '<a href="' . CUrlHelper::userLink($user->id) . '" class="joms-stream__user">' . $user->getDisplayName() . '</a>';
			?>
            <?php if($author):?>
                <div class="joms-avatar--stream">
                	<?php if (is_object($user)):?>
                        <a href="<?php echo ((int)$author_id !== 0) ? CUrlHelper::userLink($author_id) : 'javascript:void(0);'; ?>">
                            <img data-author="<?php echo $author_id; ?>" src="<?php echo $user->getThumbAvatar(); ?>" alt="<?php echo $user->getDisplayName(); ?>">
                        </a>
                    <?php else: ?>
                    	<img src="components/com_community/assets/user-Male-thumb.png" alt="male" />
                    <?php endif ?>
                </div>
			<?php endif ?>
            
            <div class="joms-stream__meta">
				<?php echo $actorLink ?>
                <?php if($date):?>
                <span class="joms-stream__time">
                    <small>
                        <?php printf(JText::_('TPL_WARP_META_DATE'), $date); ?>
                    </small>
                </span>
                <?php endif ?>
            </div>
			<!-- /Donorwiz -->
        <?php

			if ($category) {
				echo ' ';
				printf(JText::_('TPL_WARP_META_CATEGORY'), $category);
			}

		?>

	</div>
    
    <!-- Donorwiz -->
    <div class="dw-article-toolbar">
    <?php
		$params=array(
			'donate_button_params'=>array(
				'beneficiary_id'=>$author_id,
				'isPopup'=>true
			),
			'vounteer_params'=>array(
				'actor'=>$author_id
			)
		);
		echo JLayoutHelper::render('toolbar.toolbar',$params,JPATH_ROOT.'/components/com_donorwiz/layouts');
	?>
    </div>

	<!-- /Donorwiz -->
	<?php endif; ?>

	<?php if ($image && $image_alignment != 'none') : ?>
		<?php if ($url) : ?>
			<a class="uk-align-<?php echo $image_alignment; ?>" href="<?php echo $url; ?>" title="<?php echo $image_caption; ?>"><img src="<?php echo $image; ?>" alt="<?php echo $image_alt; ?>"></a>
		<?php else : ?>
			<img class="uk-align-<?php echo $image_alignment; ?>" src="<?php echo $image; ?>" alt="<?php echo $image_alt; ?>">
		<?php endif; ?>
	<?php endif; ?>

	<?php echo $hook_beforearticle; ?>
	
	<?php if ($article) : ?>
	<div>
		<?php echo $article; ?>
	</div>
	<?php endif; ?>

	<?php if ($tags) : ?>
	<p><?php echo JText::_('TPL_WARP_TAGS').': '.$tags; ?></p>
	<?php endif; ?>

	<?php if ($more) : ?>
	<p>
		<a href="<?php echo $url; ?>" title="<?php echo $title; ?>"><?php echo $more; ?></a>
	</p>
	<?php endif; ?>

	<?php if ($edit) : ?>
	<p><?php echo $edit; ?></p>
	<?php endif; ?>

	<?php if ($previous || $next) : ?>
	<ul class="uk-pagination">
		<?php if ($previous) : ?>
		<li class="uk-pagination-previous">
			<?php echo $previous; ?>
			<i class="uk-icon-angle-double-left"></i>
		</li>
		<?php endif; ?>

		<?php if ($next) : ?>
		<li class="uk-pagination-next">
			<?php echo $next; ?>
			<i class="uk-icon-angle-double-right"></i>
		</li>
		<?php endif; ?>
	</ul>
	<?php endif; ?>

	<?php echo $hook_afterarticle; ?>

</article>