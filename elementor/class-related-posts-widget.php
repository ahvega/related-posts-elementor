<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Elementor_Related_Posts_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'related_posts_widget';
    }

    public function get_title() {
        return __('Related Posts', 'related-posts-elementor');
    }

    public function get_icon() {
        return 'eicon-post-list';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        // Content Tab
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Settings', 'related-posts-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Number of Posts', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3,
            ]
        );

        $this->add_control(
            'order_by',
            [
                'label' => __('Order By', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'date' => __('Date', 'related-posts-elementor'),
                    'title' => __('Title', 'related-posts-elementor'),
                    'rand' => __('Random', 'related-posts-elementor'),
                ],
                'default' => 'date',
            ]
        );

        $this->add_control(
            'fallback_message',
            [
                'label' => __('Fallback Message', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('No related posts found.', 'related-posts-elementor'),
            ]
        );

        $this->end_controls_section();

        // Style Tab
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'related-posts-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .related-post-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'font_size',
            [
                'label' => __('Font Size', 'related-posts-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .related-post-title' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $current_post_id = get_the_ID();
        $categories = wp_get_post_categories($current_post_id);

        if (!empty($categories)) {
            $args = [
                'category__in' => $categories,
                'post__not_in' => [$current_post_id],
                'posts_per_page' => $settings['posts_per_page'],
                'orderby' => $settings['order_by'],
            ];

            $related_posts = new WP_Query($args);

            if ($related_posts->have_posts()) {
                echo '<div class="related-posts-container">';
                while ($related_posts->have_posts()) {
                    $related_posts->the_post();
                    echo '<div class="related-post-item">';
                    echo '<h3 class="related-post-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
                    echo '</div>';
                }
                echo '</div>';
                wp_reset_postdata();
            } else {
                echo '<div class="related-posts-fallback">' . esc_html($settings['fallback_message']) . '</div>';
            }
        } else {
            echo '<div class="related-posts-fallback">' . esc_html($settings['fallback_message']) . '</div>';
        }
    }
}