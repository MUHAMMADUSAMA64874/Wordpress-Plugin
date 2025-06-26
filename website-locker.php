// Create the page locker settings page.
function page_locker_settings_page() {
    if (isset($_POST['lock_page'])) {
        $lock_option = sanitize_text_field($_POST['lock_option']);
        if ($lock_option === 'all_pages') {
            // Lock all pages.
            $pages = get_pages();
            foreach ($pages as $page) {
                update_post_meta($page->ID, 'page_locked', true);
            }
        } elseif ($lock_option === 'specific_page') {
            // Lock a specific page.
            $page_id = isset($_POST['specific_page_id']) ? absint($_POST['specific_page_id']) : 0;
            if ($page_id) {
                update_post_meta($page_id, 'page_locked', true);
            }
        }
    }

    if (isset($_POST['unlock_page'])) {
        // Prompt for a password to unlock the page.
        $entered_password = sanitize_text_field($_POST['page_unlock_password']);

        if ($entered_password === 'ghulambhai') {
            $unlock_option = sanitize_text_field($_POST['unlock_option']);
            if ($unlock_option === 'all_pages') {
                // Unlock all pages.
                $pages = get_pages();
                foreach ($pages as $page) {
                    delete_post_meta($page->ID, 'page_locked');
                }
            } elseif ($unlock_option === 'specific_page') {
                // Unlock a specific page.
                $page_id = isset($_POST['specific_page_id']) ? absint($_POST['specific_page_id']) : 0;
                if ($page_id) {
                    delete_post_meta($page_id, 'page_locked');
                }
            }
        } else {
            // Incorrect password, display an error message.
            echo '<div class="error"><p>Incorrect unlock password.</p></div>';
        }
    }

    $lock_option = isset($_POST['lock_option']) ? sanitize_text_field($_POST['lock_option']) : 'all_pages';
    $unlock_option = isset($_POST['unlock_option']) ? sanitize_text_field($_POST['unlock_option']) : 'all_pages';
    $page_id = isset($_POST['specific_page_id']) ? absint($_POST['specific_page_id']) : 0;
    $is_page_locked = get_post_meta($page_id, 'page_locked', true);
    ?>

    <div class="wrap">
        <h2>Page Locker</h2>
        <form method="post">
            <p>The page is currently <?php echo $is_page_locked ? 'locked' : 'unlocked'; ?>.</p>
            
            <p>Lock or unlock:</p>
            <input type="radio" name="lock_option" value="all_pages" <?php echo $lock_option === 'all_pages' ? 'checked' : ''; ?>> All Pages
            <input type="radio" name="lock_option" value="specific_page" <?php echo $lock_option === 'specific_page' ? 'checked' : ''; ?>> Specific Page

            <?php if ($lock_option === 'specific_page') : ?>
                <p>Select a specific page to lock:</p>
                <select name="specific_page_id">
                    <option value="0">Select a page</option>
                    <?php
                    $pages = get_pages();
                    foreach ($pages as $page) {
                        echo '<option value="' . $page->ID . '" ' . ($page_id === $page->ID ? 'selected' : '') . '>' . esc_html($page->post_title) . '</option>';
                    }
                    ?>
                </select>
            <?php endif; ?>

            <input type="submit" name="lock_page" value="<?php echo $is_page_locked ? 'Lock Page' : 'Unlock Page'; ?>">
        </form>
    </div>

    <?php
}
