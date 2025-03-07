<?php
/**
 * Template Name: Minimalistic Landing Page
 */
get_header(); ?>

<main>
    <section>
        <h2>Welcome to Our Website</h2>
        <p>We offer the best products and services for your needs. Discover more below.</p>
        <a href="#services" class="btn">Explore</a>
    </section>

    <section id="services">
        <h2>Our Services</h2>
        <p>We specialize in high-quality services that help your business grow.</p>
    </section>
</main>

<footer>
    <p>&copy; <?php echo date('Y'); ?> Your Company. All rights reserved.</p>
</footer>

<?php get_footer(); ?>
