c 1711 заменил

		<div class="bp-users-reviews-stars" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
		    <span itemprop="ratingValue"  content="<?php echo $rating['result']; ?>"></span>
		    <span itemprop="bestRating"   content="100"></span>
		    <span itemprop="ratingCount"  content="<?php echo $rating['count']; ?>"></span>
		    <span itemprop="itemReviewed" content="Person"></span>
		    <span itemprop="name" content="<?php echo $BP_Member_Reviews->get_username($user_id); ?>"></span>
		    <!--<span itemprop="url" content="<?php echo $BP_Member_Reviews->get_user_link($user_id); ?>"></span>-->
		    <span itemprop="url" content="<?php echo $BP_Member_Reviews->get_user_link($user_id); ?>reviews/"></span>
		    <?php // override only due add image property ?>
		    <span itemprop="image" content="<?php echo $user_avatar; ?>"></span>
		    <?php echo $BP_Member_Reviews->print_stars($BP_Member_Reviews->settings['stars']); ?>
		    <div class="active" style="width:<?php echo $rating['result']; ?>%">
		        <?php echo $BP_Member_Reviews->print_stars($BP_Member_Reviews->settings['stars']); ?>
		    </div>
		</div>