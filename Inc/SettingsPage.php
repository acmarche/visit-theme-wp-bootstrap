<?php


namespace VisitMarche\Theme\Inc;

class SettingsPage
{
    const NAME_OPTION = 'react_activate';

    public function __construct()
    {
        add_action(
            'admin_menu',
            function () {
                SettingsPage::createPage();
            }
        );
    }

    static function createPage()
    {
        add_options_page(
            'Active react or not',
            'React settings',
            'administrator',
            __FILE__,
            function () {
                SettingsPage::renderPage();
            },
        );
        add_action(
            'admin_init',
            function () {
                SettingsPage::registerSetting();
            }
        );
    }

    static function registerSetting()
    {
        register_setting('my-cool-plugin-settings-group', self::NAME_OPTION);
    }

    static function renderPage()
    {
        $value    = self::isReactActivate();
        $selected = 'checked="checked"';
        ?>
        <div class="wrap">
            <h1>Activer ou pas react</h1>

            <form method="post" action="options.php">
                <?php settings_fields('my-cool-plugin-settings-group'); ?>
                <?php do_settings_sections('my-cool-plugin-settings-group'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Activer</th>
                        <td>
                            <input type="checkbox"
                                   name="<?php echo self::NAME_OPTION; ?>" <?php echo $value ? $selected : '' ?> >
                        </td>
                    </tr>
                </table>

                <?php submit_button(); ?>

            </form>
        </div>
        <?php
    }

    public static function isReactActivate(): bool
    {
        return (bool)esc_attr(get_option(self::NAME_OPTION, true));
    }
}
