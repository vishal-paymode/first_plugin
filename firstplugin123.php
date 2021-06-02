<?php
/*
    Plugin Name: first plugin
Plugin URI: http://www.vishal.com
Description: This is my first plugin.....
Author: Vishal Paymode
Version: 1.7.2
Author URI: http://www.vishal.com

*/
error_reporting(E_ALL ^ E_NOTICE);
define("NEXT_PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));

function add_first_plugin()
{

    add_menu_page("First Plugin", "First Plugin", "manage_options", "Form_dataa", "plugin_menu_callback_fn");


    add_submenu_page(
        "Form_dataa",
        "Insert Submenu",
        "Insert Submenu",
        "manage_options",
        "Form_dataa",
        "plugin_menu_callback_fn"
    );

    add_submenu_page(
        "Form_dataa",
        "View Submenu",
        "View Submenu",
        "manage_options",
        "plugin-submenu",
        "plugin_view_callback_fn"
    );




    add_submenu_page(
        'Form_dataa',
        'Example Plugin Menu',
        'Example Plugin Menu',
        'manage_options',
        'dbi-example-plugin',
        'dbi_render_plugin_settings_page'
    );
}




add_action("admin_menu", "add_first_plugin");



function plugin_menu_callback_fn()
{
?> <h2>HTML Forms</h2>

    <form action="#" method="POST">
        <label for="fname">First name:</label><br>
        <input type="text" id="fname" name="fname" placeholder="Enter your  first name"><br>
        <label for="lname">Last name:</label><br>
        <input type="text" id="lname" name="lname" placeholder="Enter your  last name"><br><br>
        <input type="submit" value="Submit" name="Submit">
    </form>


    <?php
    $conn = mysqli_connect('localhost', 'root', '', 'wscube');

    if (isset($_POST['Submit'])) {

        $fname = $_POST['fname'];
        $lname = $_POST['lname'];



        $insert = "INSERT INTO formdata(fname,lname) VALUES('$fname','$lname')";

        $run_insert = mysqli_query($conn, $insert);
        if ($run_insert) {
            header("Location: .../plugins/first_plugin.php");
        }
    }
}


function plugin_view_callback_fn()
{
    ?>

    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </head>

    <div class="container">
        <h2>View Data</h2>

        <?php
        $conn = mysqli_connect('localhost', 'root', '', 'avengers');
        if (isset($_GET['del'])) {

            $del_id = $_GET['del'];
            $delete = "DELETE FROM user WHERE Empid='$del_id'";

            $run_delete = mysqli_query($conn, $delete);
            if ($run_delete === true) {
                echo "deleted successfully";
            } else {
                echo "try again";
            }
        }

        ?>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Empid</th>
                    <th>firstname</th>
                    <th>lastname</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $conn = mysqli_connect('localhost', 'root', '', 'wscube');
                $select = "SELECT * FROM formdata";
                $run = mysqli_query($conn, $select);

                while ($row_user = mysqli_fetch_array($run)) {
                    $Empid = $row_user['Empid'];
                    $fname = $row_user['fname'];
                    $lname = $row_user['lname'];


                ?>

                    <tr>
                        <td><?php echo $Empid; ?></td>
                        <td><?php echo $fname; ?></td>
                        <td><?php echo $lname; ?></td>


                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
<?php } ?>



<?php
function dbi_render_plugin_settings_page()
{
?>
    <h2>Example Plugin Settings</h2>
    <form action="options.php" method="post">
        <?php
        settings_fields('dbi_example_plugin_options');
        do_settings_sections('dbi_example_plugin'); ?>
        <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e('Save'); ?>" />
    </form>
<?php
} ?>

<?php

function dbi_register_settings()
{
    register_setting('dbi_example_plugin_options', 'dbi_example_plugin_options', 'dbi_example_plugin_options_validate');
    add_settings_section('api_settings', 'API Settings', 'dbi_plugin_section_text', 'dbi_example_plugin');

    add_settings_field('dbi_plugin_setting_api_key', 'API Key', 'dbi_plugin_setting_api_key', 'dbi_example_plugin', 'api_settings');
    add_settings_field('dbi_plugin_setting_results_limit', 'Results Limit', 'dbi_plugin_setting_results_limit', 'dbi_example_plugin', 'api_settings');
    add_settings_field('dbi_plugin_setting_start_date', 'Start Date', 'dbi_plugin_setting_start_date', 'dbi_example_plugin', 'api_settings');
    add_settings_field('dbi_plugin_setting_gender', 'Gender', 'dbi_plugin_setting_gender', 'dbi_example_plugin', 'api_settings');
}
add_action('admin_init', 'dbi_register_settings');



function dbi_example_plugin_options_validate($input)
{
    $newinput['api_key'] = trim($input['api_key']);
    if (!preg_match('/^[a-z0-9]{32}$/i', $newinput['api_key'])) {
        $newinput['api_key'] = '';
    }

    return $newinput;
}

function dbi_plugin_section_text()
{
    echo '<p>Here you can set all the options for using the API</p>';
}

function dbi_plugin_setting_api_key()
{
    $options = get_option('dbi_example_plugin_options');
    echo "<input id='dbi_plugin_setting_api_key' name='dbi_example_plugin_options[api_key]' type='text' value='" . esc_attr($options['api_key']) . "' />";
}

function dbi_plugin_setting_results_limit()
{
    $options = get_option('dbi_example_plugin_options');
    echo "<input id='dbi_plugin_setting_results_limit' name='dbi_example_plugin_options[results_limit]' type='text' value='" . esc_attr($options['results_limit']) . "' />";
}

function dbi_plugin_setting_start_date()
{
    $options = get_option('dbi_example_plugin_options');
    echo "<input id='dbi_plugin_setting_start_date' name='dbi_example_plugin_options[start_date]' type='text' value='" . esc_attr($options['start_date']) . "' />";
}

function dbi_plugin_setting_gender()
{
    $options = get_option('dbi_example_plugin_options');
    echo "<label for='dbi_plugin_setting_male'  > Male </label>";
    echo "<input id='dbi_plugin_setting_male' name='dbi_example_plugin_options[gender]' type='radio' value='" . esc_attr($options['male']) . "' />";
    echo "<label for='dbi_plugin_setting_female'  > female </label>";
    echo "<input id='dbi_plugin_setting_female' name='dbi_example_plugin_options[gender]' type='radio' value='" . esc_attr($options['female']) . "' />";
    echo "<label for='dbi_plugin_setting_other'  > other </label>";
    echo "<input id='dbi_plugin_setting_other' name='dbi_example_plugin_options[gender]' type='radio' value='" . esc_attr($options['other']) . "' />";
}



?>