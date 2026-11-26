<?php
defined('ABSPATH') || exit;
$btn_text = $args['ia_btn_text'] ?? 'LOAD MORE INSIGHTS';
$archive_query = new WP_Query(['post_type' => 'post', 'posts_per_page' => 8]);
$categories = get_categories(['hide_empty' => true]);
?>
<section class="insights-archive">
    <div class="insights-archive__inner">
        <div class="insights-archive__filters">
            <button class="insights-filter is-active" data-filter="all">ALL</button>
            <?php foreach($categories as $cat): ?>
                <button class="insights-filter" data-filter="<?php echo esc_attr($cat->slug); ?>"><?php echo esc_html($cat->name); ?></button>
            <?php endforeach; ?>
        </div>
        <div class="insights-archive__grid" id="insights-grid">
            <?php if($archive_query->have_posts()) : while($archive_query->have_posts()) : $archive_query->the_post(); 
                $post_cats = get_the_category();
                $cat_name  = !empty($post_cats) ? $post_cats[0]->name : '';
                $cat_slug  = !empty($post_cats) ? $post_cats[0]->slug : '';
            ?>
                <a href="<?php the_permalink(); ?>" class="insight-card" data-category="<?php echo esc_attr($cat_slug); ?>">
                    <div class="insight-card__image-wrap">
                        <?php if (has_post_thumbnail()) : the_post_thumbnail('large', ['class' => 'insight-card__image']); else : ?>
                            <div class="insight-card__image" style="background:#f0f0f0;"></div>
                        <?php endif; ?>
                    </div>
                    <div class="insight-card__content">
                        <div class="insight-card__meta">
                            <span><?php echo get_the_date('d M, Y'); ?></span>
                            <span><?php echo esc_html($cat_name); ?></span>
                        </div>
                        <h3 class="insight-card__title"><?php the_title(); ?></h3>
                        <span class="insight-card__read-more">KNOW MORE <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><path d="M1 8H15M15 8L8 1M15 8L8 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                    </div>
                </a>
            <?php endwhile; wp_reset_postdata(); endif; ?>
        </div>
        <?php if($btn_text && $archive_query->found_posts > 8) : ?>
        <div class="insights-archive__footer">
            <button class="btn btn--primary"><span><?php echo esc_html($btn_text); ?></span> <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M1 8H15M15 8L8 1M15 8L8 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
        </div>
        <?php endif; ?>
    </div>
</section>