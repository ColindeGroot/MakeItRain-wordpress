<?php
/**
 * Created by IntelliJ IDEA.
 * User: pupunzi
 * Date: 24/11/16
 * Time: 16:56
 */

$mbideas_menu = wp_get_nav_menu_object("mb.ideas" );
$all_mb_plugin = null;
if (!$mbideas_menu && !function_exists("mbideas_menu")){
    add_action( 'admin_menu', 'mbideas_menu' );
    function mbideas_menu(){
        $page_title = 'mb.ideas';
        $menu_title = 'mb.ideas';
        $capability = 'manage_options';
        $menu_slug  = 'mb-ideas-menu';
        $function   = 'mb_list_all_plugin';
        $icon_url   = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxpdmVsbG9fMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCAyNTQuNyAyNjcuOCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMjU0LjcgMjY3LjgiIHhtbDpzcGFjZT0icHJlc2VydmUiPjxnPjxwYXRoIGZpbGw9IiNGRkZGRkYiIGQ9Ik0xNTQuNiwyMTQuNGgtNTIuM2MtMy45LDAtNy4xLDMuMi03LjEsNy4xYzAsMy45LDMuMiw3LjEsNy4xLDcuMWg1Mi4zYzMuOSwwLDcuMS0zLjIsNy4xLTcuMUMxNjEuNywyMTcuNSwxNTguNSwyMTQuNCwxNTQuNiwyMTQuNHoiLz48cGF0aCBmaWxsPSIjRkZGRkZGIiBkPSJNMTE0LjcsMjU2LjF2My44YzAsNC4zLDMuNSw3LjksNy45LDcuOWgxMS43YzQuMywwLDcuOS0zLjUsNy45LTcuOXYtMy44YzguOS0wLjYsMTUuOS04LDE1LjktMTdIOTguOEM5OC44LDI0OC4xLDEwNS45LDI1NS40LDExNC43LDI1Ni4xeiIvPjxwYXRoIGZpbGw9IiNGRkZGRkYiIGQ9Ik0xMjguNSw1Ni4xYy0zOS4xLDAtNzAuNywzMS43LTcwLjcsNzAuN2MwLDE2LjUsNS43LDMxLjYsMTUuMSw0My42YzguNCwxMC42LDE2LjMsMjIuMiwyMy4yLDMzLjloNjQuOWM2LjktMTEuOCwxNC43LTIzLjIsMjMuMi0zMy45YzkuNS0xMiwxNS4xLTI3LjEsMTUuMS00My42QzE5OS4yLDg3LjcsMTY3LjUsNTYuMSwxMjguNSw1Ni4xeiBNMTI4LjYsODAuNmMtMjUuMSwwLTQ1LjUsMjAuNC00NS41LDQ1LjVjMCwzLjktMy4yLDcuMS03LjEsNy4xYy0zLjksMC03LjEtMy4yLTcuMS03LjFjMC0zMi45LDI2LjgtNTkuNiw1OS42LTU5LjZjMy45LDAsNy4xLDMuMiw3LjEsNy4xQzEzNS42LDc3LjQsMTMyLjUsODAuNiwxMjguNiw4MC42eiIvPjwvZz48Zz48Zz48cGF0aCBmaWxsPSIjRkZGRkZGIiBkPSJNNDYuNCwxMzEuNUg1LjhjLTMuMiwwLTUuOC0yLjYtNS44LTUuOGMwLTMuMiwyLjYtNS44LDUuOC01LjhoNDAuNmMzLjIsMCw1LjgsMi42LDUuOCw1LjhDNTIuMSwxMjguOSw0OS41LDEzMS41LDQ2LjQsMTMxLjV6Ii8+PC9nPjxnPjxwYXRoIGZpbGw9IiNGRkZGRkYiIGQ9Ik0yNDguOSwxMzEuNWgtNDAuNmMtMy4yLDAtNS44LTIuNi01LjgtNS44YzAtMy4yLDIuNi01LjgsNS44LTUuOGg0MC42YzMuMiwwLDUuOCwyLjYsNS44LDUuOEMyNTQuNywxMjguOSwyNTIuMSwxMzEuNSwyNDguOSwxMzEuNXoiLz48L2c+PGc+PHBhdGggZmlsbD0iI0ZGRkZGRiIgZD0iTTEzMi41LDUyLjFjLTMuMiwwLTUuOC0yLjYtNS44LTUuOFY1LjhjMC0zLjIsMi42LTUuOCw1LjgtNS44czUuOCwyLjYsNS44LDUuOHY0MC42QzEzOC4yLDQ5LjUsMTM1LjcsNTIuMSwxMzIuNSw1Mi4xeiIvPjwvZz48L2c+PGc+PHBhdGggZmlsbD0iI0ZGRkZGRiIgZD0iTTc0LDc0LjJjLTEuNSwwLTIuOS0wLjYtNC4xLTEuN0w0MS4yLDQzLjhjLTIuMi0yLjItMi4yLTUuOSwwLTguMWMyLjItMi4yLDUuOS0yLjIsOC4xLDBsMjguNywyOC43YzIuMiwyLjIsMi4yLDUuOSwwLDguMUM3Ni45LDczLjcsNzUuNSw3NC4yLDc0LDc0LjJ6Ii8+PC9nPjxnPjxwYXRoIGZpbGw9IiNGRkZGRkYiIGQ9Ik0yMTcuMiwyMTcuNGMtMS41LDAtMi45LTAuNi00LjEtMS43TDE4NC40LDE4N2MtMi4yLTIuMi0yLjItNS45LDAtOC4xYzIuMi0yLjIsNS45LTIuMiw4LjEsMGwyOC43LDI4LjdjMi4yLDIuMiwyLjIsNS45LDAsOC4xQzIyMC4yLDIxNi45LDIxOC43LDIxNy40LDIxNy4yLDIxNy40eiIvPjwvZz48Zz48cGF0aCBmaWxsPSIjRkZGRkZGIiBkPSJNMzguMSwyMTcuNGMtMS41LDAtMi45LTAuNi00LjEtMS43Yy0yLjItMi4yLTIuMi01LjksMC04LjFsMjguNy0yOC43YzIuMi0yLjIsNS45LTIuMiw4LjEsMGMyLjIsMi4yLDIuMiw1LjksMCw4LjFsLTI4LjcsMjguN0M0MS4xLDIxNi45LDM5LjYsMjE3LjQsMzguMSwyMTcuNHoiLz48L2c+PGc+PHBhdGggZmlsbD0iI0ZGRkZGRiIgZD0iTTE5MSw3OWMtMS41LDAtMi45LTAuNi00LjEtMS43Yy0yLjItMi4yLTIuMi01LjksMC04LjFsMjguNy0yOC43YzIuMi0yLjIsNS45LTIuMiw4LjEsMGMyLjIsMi4yLDIuMiw1LjksMCw4LjFsLTI4LjcsMjguN0MxOTMuOSw3OC40LDE5Mi41LDc5LDE5MSw3OXoiLz48L2c+PC9zdmc+';
        $position   = 90;
        add_menu_page( $page_title,
            $menu_title,
            $capability,
            $menu_slug,
            $function,
            $icon_url,
            $position );
        add_submenu_page('mb-ideas-menu', 'All mb.ideas plugins', 'All plug-ins', 'manage_options', 'mb-ideas-menu' );
    }

    if(!function_exists("mb_list_all_plugin")) {
        function mb_list_all_plugin() {
            global $all_mb_plugin;
            $all_mb_plugin = get_plugins();
            ?>
            <div class="wrap">
            <a href="http://pupunzi.com"><img style=" width: 350px" src="<?php echo plugins_url('images/logo.png', dirname(__FILE__)); ?>" alt="Made by Pupunzi"/></a>
            <h2 class="title"><?php _e('mb.ideas installed plug-ins', 'wpmbytplayer'); ?></h2>

            <table class="form-table">

                <?php
                foreach ($all_mb_plugin as $plugins => $val){
                    if ($val["Author"] == "Pupunzi (Matteo Bicocchi)") {
                        ?>
                        <tr valign="top">
                            <td valign="top">
                               <h3 class="title <?php echo strpos($val["Name"], "PLUS")>0 ? "plus" : "free" ?>" ><?php echo $val["Name"] ?></h3>
                            </td>
                            <td valign="top">
                                <h3><?php echo $val["Version"] ?></h3>
                            </td>
                            <td valign="top">
                               <h3 class="<?php echo is_plugin_active( $plugins ) == 1 ? "valid" : "invalid" ?>"><?php echo is_plugin_active( $plugins ) == 1 ? "active" : "" ?></h3>
                            </td>
                            <td>
                                <?php if(is_plugin_active( $plugins )) {
                                    $settings_link =  get_bloginfo('wpurl') . '/wp-admin/admin.php?page='. $plugins;
                                    ?>
                                    <h3><a href="<?php echo $settings_link ?>">settings</a></h3>
                                <?php
                                } ?>
                            </td>
                        </tr>
                    <?php
                    }
                }
                ?>

            </table>

        <?php
        }
    }
}

