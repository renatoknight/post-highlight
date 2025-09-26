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
        $posts_pequenos = [];

        while ( $query->have_posts() ) {
            $query->the_post();
            $count++;

            $thumb = get_the_post_thumbnail_url( get_the_ID(), 'large' ) ?: 'https://via.placeholder.com/400x200';
            $cat   = get_the_category();
            $link  = get_permalink();
            $cat_link = $cat ? get_category_link( $cat[0]->term_id ) : '';
            $excerpt = get_the_excerpt() ?: wp_trim_words( get_the_content(), 20, '...' );

            if ( $count === 1 ) :
            ?>
    <article class="phb-post phb-post--grande">
        <a href="<?php echo esc_url( $link ); ?>" class="phb-thumb-link">
            <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>" class="phb-thumb-img" />
        </a>
        <div class="phb-conteudo">
            <?php if ( $cat ) : ?>
            <a href="<?php echo esc_url( $cat_link ); ?>" class="phb-categoria">
                <?php echo esc_html( $cat[0]->name ); ?>
            </a>
            <?php endif; ?>
            <h2 class="phb-titulo">
                <a href="<?php echo esc_url( $link ); ?>"><?php the_title(); ?></a>
            </h2>
            <?php if ( $excerpt ) : ?>
            <p class="phb-resumo"><?php echo esc_html( $excerpt ); ?></p>
            <?php endif; ?>
            <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                <?php echo esc_html( get_the_date() ); ?>
            </time>
        </div>
    </article>
    <?php
            else :
                $posts_pequenos[] = [
                    'thumb' => $thumb,
                    'title' => get_the_title(),
                    'link'  => $link,
                    'cat'   => $cat,
                    'cat_link' => $cat_link,
                    'date'  => get_the_date(),
                    'date_attr' => get_the_date( 'c' ),
                ];
            endif;
        }
        wp_reset_postdata();
        ?>

    <?php if ( ! empty( $posts_pequenos ) ) : ?>
    <div class="phb-grid-pequenos">
        <?php foreach ( $posts_pequenos as $p ) : ?>
        <article class="phb-post phb-post--pequeno">
            <a href="<?php echo esc_url( $p['link'] ); ?>" class="phb-thumb-link">
                <img src="<?php echo esc_url( $p['thumb'] ); ?>" alt="<?php echo esc_attr( $p['title'] ); ?>"
                    class="phb-thumb-img" />
            </a>
            <div class="phb-conteudo">
                <?php if ( ! empty( $p['cat'] ) ) : ?>
                <a href="<?php echo esc_url( $p['cat_link'] ); ?>" class="phb-categoria">
                    <?php echo esc_html( $p['cat'][0]->name ); ?>
                </a>
                <?php endif; ?>
                <h3 class="phb-titulo">
                    <a href="<?php echo esc_url( $p['link'] ); ?>"><?php echo esc_html( $p['title'] ); ?></a>
                </h3>
                <time datetime="<?php echo esc_attr( $p['date_attr'] ); ?>">
                    <?php echo esc_html( $p['date'] ); ?>
                </time>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
<?php

    return ob_get_clean();
}