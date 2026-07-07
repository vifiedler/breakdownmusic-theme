<footer id="colophon" class="fbs__footer mt-3">
    <div class="container-fluid">
        <div class="row">
                <?php if (is_active_sidebar('footer_1')):
                    dynamic_sidebar('footer_1');
                endif; ?>
                <?php if (is_active_sidebar('footer_2')):
                    dynamic_sidebar('footer_2');
                endif; ?>
                <?php if (is_active_sidebar('footer_3')):
                    dynamic_sidebar('footer_3');
                endif; ?>
        </div>
    </div>
</footer>