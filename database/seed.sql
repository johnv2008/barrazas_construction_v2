-- Barraza's Construction — Development Seed Data
-- Run AFTER schema.sql. Safe to re-run (uses INSERT ... ON DUPLICATE KEY UPDATE
-- or checks) but intended for local/staging use, not production content.
--
-- NOTE: No administrator account is seeded here. Create the first admin
-- with public/install.php (recommended) or by generating a password hash
-- yourself — see README.md "First Admin Creation". Never commit a known
-- password hash to this file.

SET NAMES utf8mb4;

-- ---------------------------------------------------------------------
-- site_settings
-- ---------------------------------------------------------------------
INSERT INTO site_settings (setting_group, setting_key, setting_value) VALUES
    ('general', 'business_name', "Barraza's Construction"),
    ('general', 'business_tagline', 'Bay Area Homes, Thoughtfully Transformed.'),
    ('general', 'phone', ''),
    ('general', 'email', ''),
    ('general', 'address', ''),
    ('general', 'license_number', ''),
    ('social', 'instagram_url', ''),
    ('social', 'facebook_url', ''),
    ('seo', 'default_meta_title', "Barraza's Construction | Bay Area Residential Remodeling"),
    ('seo', 'default_meta_description', "Barraza's Construction delivers carefully planned remodeling, renovations, and residential improvements across the San Francisco Bay Area.")
ON DUPLICATE KEY UPDATE setting_key = setting_key;

-- ---------------------------------------------------------------------
-- pages + homepage content placeholders
-- ---------------------------------------------------------------------
INSERT INTO pages (title, slug, status, sort_order) VALUES
    ('Home', 'home', 'published', 1)
ON DUPLICATE KEY UPDATE title = VALUES(title);

INSERT INTO page_sections (page_id, section_key, heading, content, sort_order, status)
SELECT p.id, 'hero', 'Bay Area Homes, Thoughtfully Transformed.',
       "Barraza's Construction delivers carefully planned remodeling, renovations, and residential improvements with experienced craftsmanship and dependable service.",
       1, 'published'
FROM pages p
WHERE p.slug = 'home'
  AND NOT EXISTS (
      SELECT 1 FROM page_sections ps WHERE ps.page_id = p.id AND ps.section_key = 'hero'
  );

INSERT INTO page_sections (page_id, section_key, heading, content, sort_order, status)
SELECT p.id, 'intro', 'Design-Build Craftsmanship, Rooted in the Bay Area',
       'Placeholder introductory copy — to be replaced with final brand messaging in a later phase.',
       2, 'published'
FROM pages p
WHERE p.slug = 'home'
  AND NOT EXISTS (
      SELECT 1 FROM page_sections ps WHERE ps.page_id = p.id AND ps.section_key = 'intro'
  );

-- ---------------------------------------------------------------------
-- service placeholders
-- ---------------------------------------------------------------------
INSERT INTO services (title, slug, summary, status, sort_order) VALUES
    ('Kitchen Remodeling', 'kitchen-remodeling', 'Placeholder summary — to be finalized in a later phase.', 'draft', 1),
    ('Bathroom Remodeling', 'bathroom-remodeling', 'Placeholder summary — to be finalized in a later phase.', 'draft', 2),
    ('Whole-Home Renovation', 'whole-home-renovation', 'Placeholder summary — to be finalized in a later phase.', 'draft', 3),
    ('Additions & ADUs', 'additions-adus', 'Placeholder summary — to be finalized in a later phase.', 'draft', 4)
ON DUPLICATE KEY UPDATE title = VALUES(title);

-- ---------------------------------------------------------------------
-- project-category placeholders
-- ---------------------------------------------------------------------
INSERT INTO project_categories (name, slug, sort_order) VALUES
    ('Kitchens', 'kitchens', 1),
    ('Bathrooms', 'bathrooms', 2),
    ('Whole-Home Renovations', 'whole-home-renovations', 3),
    ('Additions & ADUs', 'additions-adus', 4)
ON DUPLICATE KEY UPDATE name = VALUES(name);
