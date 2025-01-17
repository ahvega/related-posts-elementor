<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Related_Posts_Carousel_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'related_posts_carousel';
    }

    public function get_title() {
        return esc_html__('Related Posts Carousel', 'related-posts-elementor');
    }

    public function get_icon() {
        return 'eicon-posts-carousel';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_script_depends() {
        return ['swiper', 'related-posts-carousel'];
    }

    public function get_style_depends() {
        return ['swiper', 'related-posts-carousel'];
    }

    protected function register_controls() {
        // Query Settings
        $this->start_controls_section(
            'query_section',
            [
                'label' => esc_html__('Query Settings', 'related-posts-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Get all public post types
        $post_types = get_post_types(['public' => true], 'objects');
        $post_type_options = [];
        foreach ($post_types as $post_type) {
            $post_type_options[$post_type->name] = $post_type->label;
        }

        $this->add_control(
            'post_type',
            [
                'label' => esc_html__('Post Type', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'post',
                'options' => $post_type_options,
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => esc_html__('Number of Posts', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 12,
                'default' => 6,
            ]
        );

        $this->end_controls_section();

        // Content Settings
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content Settings', 'related-posts-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'read_more_text',
            [
                'label' => esc_html__('Read More Text', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Read More', 'related-posts-elementor'),
            ]
        );

        $this->add_control(
            'excerpt_length',
            [
                'label' => esc_html__('Excerpt Length', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'default' => 20,
            ]
        );

        $this->end_controls_section();

        // Carousel Settings
        $this->start_controls_section(
            'carousel_section',
            [
                'label' => esc_html__('Carousel Settings', 'related-posts-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'container_breakpoints_heading',
            [
                'label' => esc_html__('Container Breakpoints', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'container_breakpoint_large',
            [
                'label' => esc_html__('Large Container Width (px)', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 1024,
                'min' => 0,
                'step' => 1,
            ]
        );

        $this->add_control(
            'slides_large_container',
            [
                'label' => esc_html__('Slides for Large Container', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                ],
            ]
        );

        $this->add_control(
            'container_breakpoint_medium',
            [
                'label' => esc_html__('Medium Container Width (px)', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 768,
                'min' => 0,
                'step' => 1,
            ]
        );

        $this->add_control(
            'slides_medium_container',
            [
                'label' => esc_html__('Slides for Medium Container', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '2',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                ],
            ]
        );

        $this->add_control(
            'container_breakpoint_small',
            [
                'label' => esc_html__('Small Container Width (px)', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 480,
                'min' => 0,
                'step' => 1,
            ]
        );

        $this->add_control(
            'slides_small_container',
            [
                'label' => esc_html__('Slides for Small Container', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                ],
            ]
        );

        $this->add_control(
            'show_arrows',
            [
                'label' => esc_html__('Show Arrows', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'show_dots',
            [
                'label' => esc_html__('Show Dots', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Style Tab
        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Slide Style', 'related-posts-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'background_type',
            [
                'label' => esc_html__('Background Type', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'featured_image',
                'options' => [
                    'featured_image' => esc_html__('Featured Image', 'related-posts-elementor'),
                    'custom' => esc_html__('Custom', 'related-posts-elementor'),
                ],
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => esc_html__('Background Color', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f7f7f7',
                'condition' => [
                    'background_type' => 'custom',
                ],
                'selectors' => [
                    '{{WRAPPER}} .related-posts-image' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'custom_background_image',
            [
                'label' => esc_html__('Custom Background Image', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'condition' => [
                    'background_type' => 'custom',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Slide
        $this->start_controls_section(
            'section_style_slide',
            [
                'label' => esc_html__('Slide', 'related-posts-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'overlay_opacity',
            [
                'label' => esc_html__('Overlay Opacity', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 90,
                ],
                'selectors' => [
                    '{{WRAPPER}} .related-posts-content' => 'background: linear-gradient(to top, rgba(0, 0, 0, calc({{SIZE}} / 100)) 70%, rgba(0, 0, 0, 0));',
                ],
            ]
        );

        $this->add_control(
            'overlay_height',
            [
                'label' => esc_html__('Overlay Height', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 30,
                        'max' => 100,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 70,
                ],
                'selectors' => [
                    '{{WRAPPER}} .related-posts-content' => 'background: linear-gradient(to top, rgba(0, 0, 0, calc({{overlay_opacity.SIZE}} / 100)) {{SIZE}}%, rgba(0, 0, 0, 0));',
                ],
            ]
        );

        $this->add_control(
            'slide_background_overlay',
            [
                'label' => esc_html__('Background Overlay', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.5)',
                'selectors' => [
                    '{{WRAPPER}} .related-posts-slide-overlay' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'slide_border',
                'selector' => '{{WRAPPER}} .related-posts-slide',
            ]
        );

        $this->add_responsive_control(
            'slide_border_radius',
            [
                'label' => esc_html__('Border Radius', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .related-posts-slide' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'slide_box_shadow',
                'selector' => '{{WRAPPER}} .related-posts-slide',
            ]
        );

        $this->end_controls_section();

        // Style Tab - Typography
        $this->start_controls_section(
            'section_style_typography',
            [
                'label' => esc_html__('Typography', 'related-posts-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Title Typography', 'related-posts-elementor'),
                'selector' => '{{WRAPPER}} .related-posts-title',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt_typography',
                'label' => esc_html__('Excerpt Typography', 'related-posts-elementor'),
                'selector' => '{{WRAPPER}} .related-posts-excerpt',
            ]
        );

        $this->end_controls_section();

        // Style Tab - Colors
        $this->start_controls_section(
            'section_style_colors',
            [
                'label' => esc_html__('Colors', 'related-posts-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .related-posts-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'excerpt_color',
            [
                'label' => esc_html__('Excerpt Color', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .related-posts-excerpt' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Button
        $this->start_controls_section(
            'section_style_button',
            [
                'label' => esc_html__('Button', 'related-posts-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .related-posts-button',
            ]
        );

        $this->start_controls_tabs('button_style_tabs');

        $this->start_controls_tab(
            'button_normal_tab',
            [
                'label' => esc_html__('Normal', 'related-posts-elementor'),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__('Text Color', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .related-posts-button' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_background_color',
            [
                'label' => esc_html__('Background Color', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4054b2',
                'selectors' => [
                    '{{WRAPPER}} .related-posts-button' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_hover_tab',
            [
                'label' => esc_html__('Hover', 'related-posts-elementor'),
            ]
        );

        $this->add_control(
            'button_hover_text_color',
            [
                'label' => esc_html__('Text Color', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .related-posts-button:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_hover_background_color',
            [
                'label' => esc_html__('Background Color', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#3448a0',
                'selectors' => [
                    '{{WRAPPER}} .related-posts-button:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // Navigation Style Section
        $this->start_controls_section(
            'section_style_navigation',
            [
                'label' => esc_html__('Navigation', 'related-posts-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Arrow Style
        $this->add_control(
            'arrows_heading',
            [
                'label' => esc_html__('Arrows', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'arrows_background',
            [
                'label' => esc_html__('Background Color', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f5f5f5',
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_color',
            [
                'label' => esc_html__('Arrow Color', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_hover_background',
            [
                'label' => esc_html__('Hover Background Color', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#e5e5e5',
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev:hover, {{WRAPPER}} .swiper-button-next:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_hover_color',
            [
                'label' => esc_html__('Hover Arrow Color', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev:hover, {{WRAPPER}} .swiper-button-next:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Dots Style
        $this->add_control(
            'dots_heading',
            [
                'label' => esc_html__('Dots', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'dots_color',
            [
                'label' => esc_html__('Dots Color', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#d4d4d4',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dots_active_color',
            [
                'label' => esc_html__('Active Dot Color', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Prepare container breakpoints
        $container_breakpoints = [
            $settings['container_breakpoint_large'] => intval($settings['slides_large_container']),
            $settings['container_breakpoint_medium'] => intval($settings['slides_medium_container']),
            $settings['container_breakpoint_small'] => intval($settings['slides_small_container']),
            0 => 1, // Default for smallest size
        ];

        // Prepare carousel settings
        $carousel_settings = [
            'containerBreakpoints' => $container_breakpoints,
            'navigation' => [
                'nextEl' => '.swiper-button-next',
                'prevEl' => '.swiper-button-prev',
            ],
            'pagination' => [
                'el' => '.swiper-pagination',
                'clickable' => true,
            ],
            'showArrows' => $settings['show_arrows'] === 'yes',
            'showDots' => $settings['show_dots'] === 'yes',
        ];

        // Get posts
        $args = [
            'post_type' => $settings['post_type'],
            'posts_per_page' => $settings['posts_per_page'],
            'post__not_in' => [get_the_ID()],
            'orderby' => 'date',
            'order' => 'DESC',
        ];

        $posts_query = new \WP_Query($args);

        if ($posts_query->have_posts()) :
            ?>
            <div class="related-posts-carousel-wrapper" data-settings='<?php echo esc_attr(json_encode($carousel_settings)); ?>'>
                <div class="swiper">
                    <div class="swiper-wrapper">
                        <?php
                        while ($posts_query->have_posts()) :
                            $posts_query->the_post();
                            $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'large');
                            ?>
                            <div class="swiper-slide">
                                <div class="related-posts-slide">
                                    <div class="related-posts-image" style="background-image: url('<?php echo esc_url($thumbnail); ?>')"></div>
                                    <div class="related-posts-content">
                                        <h3 class="related-posts-title"><?php the_title(); ?></h3>
                                        <div class="related-posts-excerpt">
                                            <?php echo wp_trim_words(get_the_excerpt(), $settings['excerpt_length']); ?>
                                        </div>
                                        <a href="<?php the_permalink(); ?>" class="related-posts-button">
                                            <?php echo esc_html($settings['read_more_text']); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                    <?php if ($settings['show_arrows'] === 'yes' || $settings['show_dots'] === 'yes') : ?>
                        <div class="related-posts-navigation">
                            <?php if ($settings['show_arrows'] === 'yes') : ?>
                                <div class="swiper-button-prev"></div>
                            <?php endif; ?>
                            
                            <?php if ($settings['show_dots'] === 'yes') : ?>
                                <div class="swiper-pagination"></div>
                            <?php endif; ?>
                            
                            <?php if ($settings['show_arrows'] === 'yes') : ?>
                                <div class="swiper-button-next"></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php
        endif;
    }
}
