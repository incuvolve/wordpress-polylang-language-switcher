# Polylang Language Switcher for WordPress Block Themes

Fix Polylang's language switcher when migrating from a classic theme (e.g. Twenty Nineteen) to a **block theme** (e.g. Twenty Twenty-Four).

Classic themes used `wp_nav_menu_items` and a `#pll_switcher` menu placeholder — neither works in block themes. This solution injects language links directly into the `core/navigation` block via PHP.

## Files

| File | Purpose |
|---|---|
| [`lang-switcher.php`](lang-switcher.php) | PHP snippet — add to your child theme's `functions.php` |
| [`lang-switcher.css`](lang-switcher.css) | CSS snippet — add to your child theme's `style.css` |

---

## Requirements

- WordPress with **Polylang** plugin installed and configured
- A **child theme** of your block theme
- At least two languages set up in Polylang

---

## Step 1 — Find your Navigation block ID

You need the `ref` ID of the Navigation block used in your header.

**Easiest way:**

1. Go to **Appearance → Editor → Templates → Header**
2. Click the **Navigation block**
3. Look at the browser URL — it will contain something like:
   ```
   site-editor.php?p=%2Fwp_navigation%2F261
   ```
   → `261` is your navigation block ID.

**Alternative:** Go to `wp-admin/edit.php?post_type=wp_navigation` and hover over your navigation — the link shows `post=261`.

---

## Step 2 — Add PHP to your child theme `functions.php`

Copy the contents of [`lang-switcher.php`](lang-switcher.php) into your child theme's `functions.php`.

Set `WP_PL_NAV_BLOCK_REF` to the ID found in Step 1:

```php
define( 'WP_PL_NAV_BLOCK_REF', 261 ); // <-- replace with your nav block ID
```

---

## Step 3 — Add CSS to your child theme `style.css`

Copy the contents of [`lang-switcher.css`](lang-switcher.css) into your child theme's `style.css`.

---

## How it works

| Problem | Solution |
|---|---|
| Block themes ignore `wp_nav_menu_items` | Use `render_block_core/navigation` filter instead |
| Polylang's `#pll_switcher` placeholder doesn't work in block menus | Inject `<li>` items directly into the rendered block HTML |
| Filter runs on all nav blocks (header, footer, etc.) | Target only the specific block by its `ref` ID |
| `style.css` not loaded in block themes by default | Enqueue it explicitly via `wp_enqueue_scripts` |

---

## Troubleshooting

**Switcher doesn't appear**
- Double-check `WP_PL_NAV_BLOCK_REF` matches the ID from Step 1
- Clear all caches (plugin cache, server cache, browser cache)
- Confirm Polylang is active and has multiple languages configured

**CSS not applying**
- Check browser DevTools that your child theme's `style.css` is loaded in `<head>`
