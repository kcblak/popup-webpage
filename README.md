# Popup Webpage Plugin

**Plugin Name:** Popup Webpage  
**Plugin URI:** [Kingsley James-Hart's LinkedIn](https://www.linkedin.com/in/kingsley-james-hart-93679b184/?originalSubdomain=ng)  
**Description:** A plugin to create a shortcode for opening a webpage in a popup window.  
**Version:** 1.0  
**Author:** James-Hart Kingsley  
**Author URI:** [Kingsley James-Hart's LinkedIn](https://www.linkedin.com/in/kingsley-james-hart-93679b184/?originalSubdomain=ng)  

## Description

The Popup Webpage plugin enables WordPress users to create a customizable button that opens a webpage in a popup window. This functionality is perfect for displaying additional content, forms, or information without requiring users to navigate away from the current page. The plugin also allows you to manage popup settings via a custom post type and generate shortcodes for easy integration into your posts and pages.

## Features

- **Popup Creation:** Easily create popups using the custom post type "Popups."
- **Shortcode Support:** Use a simple shortcode to add popup buttons to posts or pages.
- **Customizable Button Text:** Change the button text that opens the popup.
- **Custom CSS Class:** Add your own CSS class to the popup button for styling.
- **Generated Shortcode:** Display a generated shortcode in the post editor, ready for use.
- **GamiPress Integration:** Optionally award points when the popup is triggered by a logged-in user.

## Installation

To install the Popup Webpage plugin:

1. **Download the Plugin:**
   - Download the plugin ZIP file or clone the repository to your computer.

2. **Upload the Plugin:**
   - Go to your WordPress dashboard and navigate to **Plugins > Add New**.
   - Click on **Upload Plugin**, select the ZIP file, and click **Install Now**.
   - Alternatively, you can manually upload the plugin files to your `wp-content/plugins` directory.

3. **Activate the Plugin:**
   - Once installed, click **Activate** to enable the plugin.

## Usage

Once activated, you can begin using the Popup Webpage plugin to add popup buttons to your posts and pages.

### Creating a Popup

1. Navigate to the "Popups" section in your WordPress admin menu.
2. Click **Add New** to create a new popup.
3. Enter a title for your popup and set the **Popup URL** in the meta box.
4. Save your popup.

### Using the Shortcode

After creating your popup, you can use the generated shortcode in any post or page. 

#### Example Shortcode:

```shortcode
[popup_webpage url="https://www.example.com" button_text="Open Popup" button_class="popup-webpage-button"]
