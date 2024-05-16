1. **Set Up Your Development Environment**:
   - First, make sure you have Node.js and npm installed on your system.
   - Create a new directory for your WordPress plugin if you haven't already.
   - Initialize a new npm project in your plugin directory by running `npm init -y`.
   - Install Vite and React dependencies by running:
     ```
     npm install vite react react-dom
     ```

2. **Create Your React Component**:
   - Inside your plugin directory, create a folder named `src`.
   - Within the `src` folder, create a new file for your React component, for example, `SortableMenuPage.js`.
   - Write your React component code in this file. This component will represent your admin menu page.

3. **Set Up Vite Configuration**:
   - Create a `vite.config.js` file in your plugin directory.
   - Configure Vite to build your React component:
     ```javascript
     import { defineConfig } from 'vite';

     export default defineConfig({
        build: {
            outDir: 'dist',
            assetsDir: 'assets', 
            emptyOutDir: true,
            rollupOptions: {
                output: {
                    entryFileNames: 'assets/[name].js',
                    chunkFileNames: 'assets/[name].js',
                    assetFileNames: 'assets/[name].[ext]', // Custom asset file names ( when we used this it only changes css file name  )
                },
            },
         },
     });
     ```

4. **Create a Build Script**:
   - Open your `package.json` file and add a script to build your React component using Vite:
     ```json
     "scripts": {
       "build": "vite build"
     }
     ```

5. **Enqueue Your React Component in WordPress**:
   - In your main plugin file (e.g., `your-plugin.php`), enqueue the React component script and styles. You may use the `admin_enqueue_scripts` action hook for this:
     ```php
     function enqueue_sortable_menu_page_scripts() {
         wp_enqueue_script('your-plugin-sortable-menu-page', plugin_dir_url(__FILE__) . 'dist/index.js', array(), '1.0', true);
     }
     add_action('admin_enqueue_scripts', 'enqueue_sortable_menu_page_scripts');
     ```

6. **Create the Admin Menu Page**:
   - Finally, create your admin menu page using the `add_menu_page` function:
     ```php
     function add_sortable_menu_page() {
         add_menu_page(
             'Sortable Menu',
             'Sortable Menu',
             'manage_options',
             'sortable-menu',
             'render_sortable_menu_page'
         );
     }
     add_action('admin_menu', 'add_sortable_menu_page');

     function render_sortable_menu_page() {
         echo '<div id="sortable-menu-page"></div>';
     }
     
     

