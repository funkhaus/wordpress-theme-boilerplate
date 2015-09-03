<?php
    $cart_count = sizeof( WC()->cart->get_cart() );
?>
<ul id="store-controls" class="store-controls menu">
    
    <li class="account-menu-item">
        <a href="<?php echo get_permalink(9); // My Account?>">
            <?php if( is_user_logged_in() ) : ?>
                <?php echo get_the_title(9); ?>
            <?php else : ?>
                Login / Signup
            <?php endif; ?>
        </a>
    </li>
    
    <li class="cart-menu-item">
        Cart (<span id="cart-count"><?php echo $cart_count; ?></span>)
    </li>

</ul>