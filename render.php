<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function phb_render_block( $attributes ) {
    $quantidade = ! empty( $attributes['quantidade'] ) ? intval( $attributes['quantidade'] ) : 4;
    $modo = $attributes['modo'] ?? 'automatico';
    $postsSelecionados = $attributes['postsSelecionados'] ?? [];

    $args = [
        'posts_per_page' => $quantidade,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    if ( $modo === 'manual' && ! empty( $postsSelecionados ) ) {
        $args['post__in'] = array_map( 'intval', $postsSelecionados );
        $args['orderby'] = 'post__in';
    }

    $query = new WP_Query( $args );
    if ( ! $query->have_posts() ) {
        return '<p>' . esc_html__( 'Nenhum post encontrado.', 'post-highlight' ) . '</p>';
    }

    ob_start();
    ?>
<div class="phb-grid">
    <?php
        $count = 0;

        while ( $query->have_posts() ) {
            $query->the_post();
            $count++;

            $thumb    = get_the_post_thumbnail_url( get_the_ID(), 'large' ) ?: 'https://via.placeholder.com/400x200';
            $cat      = get_the_category();
            $link     = get_permalink();
            $cat_link = $cat ? get_category_link( $cat[0]->term_id ) : '';
            $excerpt  = get_the_excerpt() ?: wp_trim_words( get_the_content(), 20, '...' );

            // POST GRANDE
            if ( $count === 1 ) : ?>
    <article class="phb-post phb-post--grande">
        <div class="phb-thumb-wrapper">
            <!-- Link da imagem -->
            <a href="<?php echo esc_url( $link ); ?>" class="phb-thumb-link">
                <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>"
                    class="phb-thumb-img" />
            </a>

            <!-- Overlay -->
            <div class="phb-overlay">
                <?php if ( $cat ) : ?>
                <a href="<?php echo esc_url( $cat_link ); ?>" class="phb-categoria">
                    <?php echo esc_html( $cat[0]->name ); ?>
                </a>
                <?php endif; ?>

                <!-- Link do título -->
                <a href="<?php echo esc_url( $link ); ?>" class="phb-thumb-link">
                    <h2 class="phb-titulo"><?php the_title(); ?></h2>
                </a>

                <?php if ( $excerpt ) : ?>
                <p class="phb-resumo"><?php echo esc_html( $excerpt ); ?></p>
                <?php endif; ?>

                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                    <?php echo esc_html( get_the_date() ); ?>
                </time>
            </div>
        </div>
    </article>

    <div class="phb-grid-pequenos">
        <!-- grid posts pequenos -->

        <?php
            // POSTS PEQUENOS (2 e 3)
            elseif ( $count <= 3 ) : ?>
        <article class="phb-post phb-post--pequeno">
            <div class="phb-thumb-wrapper">
                <!-- Link da imagem -->
                <a href="<?php echo esc_url( $link ); ?>" class="phb-thumb-link">
                    <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>"
                        class="phb-thumb-img" />
                </a>

                <!-- Overlay -->
                <div class="phb-overlay">
                    <?php if ( $cat ) : ?>
                    <a href="<?php echo esc_url( $cat_link ); ?>" class="phb-categoria">
                        <?php echo esc_html( $cat[0]->name ); ?>
                    </a>
                    <?php endif; ?>

                    <!-- Link do título -->
                    <a href="<?php echo esc_url( $link ); ?>" class="phb-thumb-link">
                        <h3 class="phb-titulo"><?php the_title(); ?></h3>
                    </a>

                    <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                        <?php echo esc_html( get_the_date() ); ?>
                    </time>
                </div>
            </div>
        </article>

        <?php if ( $count === 3 ) : ?>
    </div><!-- fecha grid dos pequenos -->
    <?php endif; ?>

    <?php endif;
        }

        wp_reset_postdata();
    ?>
</div>
<?php

    return ob_get_clean();
}
?>