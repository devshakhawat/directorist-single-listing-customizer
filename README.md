# Directorist Single Listing Customizer

**Version:** 1.0.0  
**Author:** Shakhawat  
**License:** GPL v2 or later

A WordPress plugin that enhances the Directorist single listing page with a custom template, providing a modern and feature-rich layout for displaying listing details.

---

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Setup Instructions](#setup-instructions)
- [Override Approach](#override-approach)
- [Customization](#customization)
- [File Structure](#file-structure)
- [Support](#support)

---

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- [Directorist](https://wordpress.org/plugins/directorist/) plugin (active and configured)

---

## Installation

1. **Download the Plugin**  
   Clone or download the plugin repository to your local machine.

2. **Upload to WordPress**  
   Upload the `directorist-single-listing-customizer` folder to your WordPress installation's plugins directory:
   ```
   wp-content/plugins/directorist-single-listing-customizer
   ```

3. **Activate the Plugin**  
   Navigate to **Plugins > Installed Plugins** in your WordPress admin dashboard and click **Activate** next to "Directorist Single Listing Customizer".

4. **Verify Installation**  
   Visit any single listing page on your site to see the custom template in action.

---

## Setup Instructions

### Step 1: Install & Activate

After uploading the plugin to the `wp-content/plugins/` directory, activate it from the WordPress admin dashboard under **Plugins > Installed Plugins**.

### Step 2: Ensure Directorist is Active

This plugin is designed to work with the Directorist plugin. Make sure Directorist is installed, activated, and you have at least one listing created.

### Step 3: Configure Directorist Settings

1. Go to **Directorist > Settings** in your WordPress admin.
2. Ensure that the single listing page is enabled and configured.
3. Verify that listings are visible and functioning correctly.

### Step 4: View the Custom Template

Navigate to any single listing page (e.g., `yoursite.com/listing/your-listing-slug/`). The plugin will automatically override the default Directorist template with the custom layout.

### Step 5: Optional - Add Custom Styles

If you want to customize the appearance further:

1. Open `assets/css/single-listing-page.css`
2. Modify the CSS to match your brand's design
3. Save and refresh the listing page to see changes

---

## Override Approach

### How Template Override Works

This plugin uses the **Directorist template filter hook** to override the default single listing content template. Here's how it works:

#### 1. Hook into Directorist Template System

The plugin registers a filter on `directorist_template_file_path` with priority `999` (high priority to ensure it runs last):

```php
add_filter( 'directorist_template_file_path', [ $this, 'override_directorist_template' ], 999, 3 );
```

#### 2. Define Template Mapping

The `Template_Locator` class maintains a mapping of Directorist template names to custom plugin templates:

```php
$overrides = [
    'single-contents' => 'directory-single-listing-page.php',
];
```

When Directorist requests the `single-contents` template, the plugin intercepts and returns the path to the custom template instead.

#### 3. Validate & Return Custom Template

Before returning the custom template path, the plugin verifies that the file exists:

```php
if ( file_exists( $plugin_template ) ) {
    return $plugin_template;
}
```

If the custom template doesn't exist, the original Directorist template is used as a fallback.

### Benefits of This Approach

- **Non-Destructive:** No core Directorist files are modified. Updates to Directorist won't break your customizations.
- **Fallback Safety:** If the custom template is missing or corrupted, the system gracefully falls back to the default template.
- **Easy Maintenance:** All customizations are contained within this plugin, making updates and troubleshooting straightforward.
- **Selective Override:** Only specific templates are overridden (currently `single-contents`), leaving other Directorist templates intact.

### Template Override Flow

```
Directorist loads single listing
    ↓
Applies filter: directorist_template_file_path
    ↓
Template_Locator intercepts the request
    ↓
Checks if template name matches override map
    ↓
If match exists → Returns custom template path
    ↓
If no match → Returns original template path
    ↓
WordPress renders the listing with the chosen template
```

---

## Customization

### Modifying the Template

The main template file is located at:
```
templates/directory-single-listing-page.php
```

You can edit this file to:
- Change the layout structure
- Add or remove sections
- Modify how listing data is displayed
- Add custom fields or integrations

### Styling

All styles are loaded from:
```
assets/css/single-listing-page.css
```

Modify this file to customize colors, fonts, spacing, and responsive behavior.

### Adding New Template Overrides

To override additional Directorist templates:

1. Create the new template file in the `templates/` directory
2. Add the template mapping in `includes/template-locator.php`:
   ```php
   $overrides = [
       'single-contents'      => 'directory-single-listing-page.php',
       'new-template-name'    => 'your-custom-template.php',
   ];
   ```

---

## File Structure

```
directorist-single-listing-customizer/
├── assets/
│   ├── css/
│   │   └── single-listing-page.css    # Custom styles
│   └── js/                             # JavaScript files (if needed)
├── includes/
│   ├── autoloader.php                  # Class autoloader
│   ├── classmaps.php                   # Class-to-file mapping
│   ├── functions.php                   # Helper functions
│   ├── plugin.php                      # Main plugin class
│   ├── scripts.php                     # Script/style enqueue logic
│   └── template-locator.php            # Template override logic
├── templates/
│   └── directory-single-listing-page.php  # Custom single listing template
└── directorist-single-listing-customizer.php  # Main plugin file
```

---

## Support

For issues, feature requests, or contributions, please open an issue in the plugin's repository or contact the developer.

---

## License

This plugin is licensed under GPL v2 or later. See [GNU GPL License](https://www.gnu.org/licenses/gpl-2.0.html) for more details.
