class RelatedPostsCarousel {
    constructor(element, settings) {
        this.element = element;
        this.settings = settings;
        this.init();
    }

    init() {
        const defaultConfig = {
            slidesPerView: 1,
            spaceBetween: 20,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {},
            observer: true,
            observeParents: true,
            resizeObserver: true
        };

        // Create ResizeObserver to watch container width
        const containerObserver = new ResizeObserver(entries => {
            for (let entry of entries) {
                const containerWidth = entry.contentRect.width;
                this.updateSlidesPerView(containerWidth);
            }
        });

        // Start observing the container
        containerObserver.observe(this.element);

        // Initialize Swiper with default config
        this.swiper = new Swiper(this.element.querySelector('.swiper'), {
            ...defaultConfig,
            ...this.settings
        });
    }

    updateSlidesPerView(containerWidth) {
        if (!this.swiper) return;

        const { containerBreakpoints } = this.settings;
        let slidesToShow = 1;

        // Sort breakpoints in descending order
        const sortedBreakpoints = Object.keys(containerBreakpoints)
            .map(Number)
            .sort((a, b) => b - a);

        // Find the appropriate breakpoint
        for (const breakpoint of sortedBreakpoints) {
            if (containerWidth >= breakpoint) {
                slidesToShow = containerBreakpoints[breakpoint];
                break;
            }
        }

        // Update slides per view if it's different from current
        if (this.swiper.params.slidesPerView !== slidesToShow) {
            this.swiper.params.slidesPerView = slidesToShow;
            this.swiper.update();
        }
    }
}

// Initialize carousels when document is ready
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.related-posts-carousel-wrapper').forEach(element => {
        const settings = JSON.parse(element.dataset.settings || '{}');
        new RelatedPostsCarousel(element, settings);
    });
});

// Re-initialize when Elementor frontend is initialized (for editor preview)
if (window.elementorFrontend) {
    elementorFrontend.hooks.addAction('frontend/element_ready/related_posts_carousel.default', ($element) => {
        const element = $element[0].querySelector('.related-posts-carousel-wrapper');
        if (element) {
            const settings = JSON.parse(element.dataset.settings || '{}');
            new RelatedPostsCarousel(element, settings);
        }
    });
}