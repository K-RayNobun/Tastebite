<?php
require_once 'includes/config.php';

$page_title = "Tastebite | About Us";
$extra_css = ['about-page/about-page2/styles.css'];

include 'includes/head.php';
include 'includes/header.php';
?>

<main>
    <!-- About Section -->
    <section class="about-section">
        <h2>About</h2>
        <div class="divider"></div>
        <h1>We're a group of foodies who love cooking and the internet</h1>
        <img src="about-page/images/section-01.png" alt="A person holding a donut" class="main-image">
        <div class="intro-text">
            <p>
                Food qualities braise chicken cuts bowl through slices butternut snack. Tender meat juicy
                dinners. One-pot low heat plenty of time adobo fat raw soften fruit. Sweet renders bone-in
                marrow richness kitchen, fricassee basted pork shoulder. Delicious butternut squash hunk.
            </p>
        </div>
    </section>
    
    <!-- Simple, Easy Recipes -->
    <section class="simple-recipes">
        <div class="recipes-content">
            <h2>Simple, Easy Recipes for all</h2>
            <p>
                Juicy meatballs brisket slammin' baked shoulder. Juicy smoker soy sauce burgers brisket, polenta
                mustard hunk greens. Wine technique snack skewers chuck excess. Oil heat slowly. slices natural
                delicious, set aside magic tbsp skillet, bay leaves brown centerpiece.
            </p>
        </div>
        <img src="about-page/images/section-02.svg" alt="Dynamic recipes" class="recipe-image">
    </section>
    
    <!-- Team Members -->
    <section class="team-section">
        <h2>An incredible team of talented chefs and foodies</h2>
        <div class="team-grid">
            <div class="team-member">
                <img src="about-page/images/team/team-1.png" alt="Chef" class="avatar">
                <div class="team-info">
                    <div class="name">Ham Chuwon</div>
                    <div class="role">Chef Extraordinaire</div>
                </div>
            </div>
            <div class="team-member">
                <img src="about-page/images/team/team-2.png" alt="Chef" class="avatar">
                <div class="team-info">
                    <div class="name">Izabella Tabakova</div>
                    <div class="role">Chef Extraordinaire</div>
                </div>
            </div>
            <div class="team-member">
                <img src="about-page/images/team/team-3.png" alt="Chef" class="avatar">
                <div class="team-info">
                    <div class="name">Fatima Delgadillo</div>
                    <div class="role">Chef Extraordinaire</div>
                </div>
            </div>
            <div class="team-member">
                <img src="about-page/images/team/team-4.png" alt="Chef" class="avatar">
                <div class="team-info">
                    <div class="name">Harrison Phillips</div>
                    <div class="role">Chef Extraordinaire</div>
                </div>
            </div>
            <div class="team-member">
                <img src="about-page/images/team/team-5.png" alt="Chef" class="avatar">
                <div class="team-info">
                    <div class="name">Pablo Heymann</div>
                    <div class="role">Chef Extraordinaire</div>
                </div>
            </div>
            <div class="team-member">
                <img src="about-page/images/team/team-6.png" alt="Chef" class="avatar">
                <div class="team-info">
                    <div class="name">Phoebe Fraser</div>
                    <div class="role">Chef Extraordinaire</div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php 
include 'includes/newsletter.php';
include 'includes/footer.php';
include 'includes/foot.php';
?>
