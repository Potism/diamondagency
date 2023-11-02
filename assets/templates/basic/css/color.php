<?php
header("Content-Type:text/css");
$color = "#f0f"; // Change your Color Here
$secondColor = "#ff8"; // Change your Color Here

function checkhexcolor($color){
    return preg_match('/^#[a-f0-9]{6}$/i', $color);
}

if (isset($_GET['color']) AND $_GET['color'] != '') {
    $color = "#" . $_GET['color'];
}

if (!$color OR !checkhexcolor($color)) {
    $color = "#336699";
}


function checkhexcolor2($secondColor){
    return preg_match('/^#[a-f0-9]{6}$/i', $secondColor);
}

if (isset($_GET['secondColor']) AND $_GET['secondColor'] != '') {
    $secondColor = "#" . $_GET['secondColor'];
}

if (!$secondColor OR !checkhexcolor2($secondColor)) {
    $secondColor = "#336699";
}
?>


.btn--base, .btn--base:hover, body::-webkit-scrollbar-thumb, .hero__form-btn, .feature-card__icon, .job-alert-form__btn, blockquote, .single-experience::before, .single-experience::after, .footer-widget__title::after, .contact-form-area .title::after, .sidebar .widget .widget__title::after, .pagination .page-item.active .page-link, .icon-btn, .d-widget::before, .d-widget__btn, .custom--accordion .accordion-button:not(.collapsed){
    background-color: <?php echo $color ?>;
}


.text--base {
    color: <?php echo $color ?> !important;
}

a:hover, .custom--accordion-two .accordion-button:not(.collapsed), .job-type-tabs .nav-item .nav-link.active, .job-cat-list li a:hover, .page-breadcum-menu li a, .job-alert-box__icon i, .blog-post__date i, .d-widget__icon i, .dashboard-sidebar__menu li.active a, .dashboard-sidebar__menu li a:hover, .footer-widget__list a:hover, .social-link li a:hover i, .contact-item a:hover, .header .main-menu li a:hover, .header .main-menu li a:focus, .page-breadcrumb li:first-child::before{
    color: <?php echo $color ?>;
}

old.account-tabs .nav-item .nav-link.active{
    color: <?php echo $color ?>;
    border-color: <?php echo $color ?>;
    background-color: <?php echo $color ?>1a;
}

.account-tabs .nav-item .nav-link.active{
    color: "#FFFFFC";
    border-color: <?php echo $color ?>;
    background-color: <?php echo $color ?>;
}


.bg--base {
    background-color: <?php echo $color ?> !important;
}


.preloader-holder, .header__bottom, .inner-hero::after, .package-card__top .icon, .job-summary__title, .candidate-header, .candidate-sidebar__title, .footer-section, .account-section, .map-area .map-address, .contact-item__icon {
    background-color : <?php echo $secondColor ?>;
}

.pagination .page-item .page-link, .job-cat-list li:hover a {
     border-color: <?php echo $color ?>40;
}


.job-cat-list {
    background-color:  <?php echo $secondColor ?>;
    border: 1px solid <?php echo $color ?>;
}

.section--bg2 {
    background-color: <?php echo $secondColor ?>;
}


.badge--base {
    background-color: <?php echo $color ?>26;
    border: 1px solid <?php echo $color ?>;
    color: <?php echo $color ?>;
}

.account-tabs .nav-item .nav-link::after {
    border-color: <?php echo $color ?> transparent transparent transparent;
}

.form--control:focus {
    border-color: <?php echo $color ?> !important;
    box-shadow: 0 0 5px <?php echo $color ?>59;
}

.hero::before{
    background: linear-gradient(to top, <?php echo $secondColor ?>fa, <?php echo $secondColor ?>73);
}


.custom--accordion .accordion-item {
    border: 1px solid <?php echo $color ?>80;
}