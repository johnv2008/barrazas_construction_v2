-- Barraza's Construction — Phase 1 Database Schema
-- Target: MariaDB 10.4+ / MySQL 8.0+ (one.com shared hosting compatible)
-- Import via phpMyAdmin: select your database, "Import" tab, choose this file.
--
-- Charset: utf8mb4 (full Unicode, incl. emoji) — required on every table
-- and connection (see App\Services\DatabaseService).
-- Engine: InnoDB everywhere for foreign key + transaction support.

SET FOREIGN_KEY_CHECKS = 0;
SET NAMES utf8mb4;

-- ---------------------------------------------------------------------
-- admin_users
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS admin_users (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name                VARCHAR(100)    NOT NULL,
    email               VARCHAR(190)    NOT NULL,
    password_hash       VARCHAR(255)    NOT NULL,
    role                ENUM('administrator', 'editor') NOT NULL DEFAULT 'administrator',
    is_active           TINYINT(1)      NOT NULL DEFAULT 1,
    last_login_at       DATETIME        NULL,
    failed_login_count  SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    locked_until        DATETIME        NULL,
    created_at          DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_admin_users_email (email),
    KEY idx_admin_users_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- password_reset_tokens
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS password_reset_tokens (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_id    BIGINT UNSIGNED NOT NULL,
    token_hash  CHAR(64)        NOT NULL COMMENT 'sha256 hex of the raw token; raw token is never stored',
    expires_at  DATETIME        NOT NULL,
    used_at     DATETIME        NULL,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_password_reset_token_hash (token_hash),
    KEY idx_password_reset_admin (admin_id),
    CONSTRAINT fk_password_reset_admin FOREIGN KEY (admin_id) REFERENCES admin_users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- login_attempts
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS login_attempts (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email       VARCHAR(190)    NOT NULL,
    ip_address  VARCHAR(45)     NOT NULL,
    user_agent  VARCHAR(255)    NULL,
    successful  TINYINT(1)      NOT NULL DEFAULT 0,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_login_attempts_email (email),
    KEY idx_login_attempts_ip_created (ip_address, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- activity_logs
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS activity_logs (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_id    BIGINT UNSIGNED NULL,
    action      VARCHAR(100)    NOT NULL,
    description VARCHAR(255)    NULL,
    ip_address  VARCHAR(45)     NULL,
    user_agent  VARCHAR(255)    NULL,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_activity_logs_admin (admin_id),
    KEY idx_activity_logs_created (created_at),
    CONSTRAINT fk_activity_logs_admin FOREIGN KEY (admin_id) REFERENCES admin_users (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- site_settings
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS site_settings (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_group   VARCHAR(50)     NOT NULL DEFAULT 'general',
    setting_key     VARCHAR(100)    NOT NULL,
    setting_value   TEXT            NULL,
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_site_settings_key (setting_key),
    KEY idx_site_settings_group (setting_group)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- pages
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS pages (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(150)    NOT NULL,
    slug        VARCHAR(170)    NOT NULL,
    status      ENUM('draft', 'published') NOT NULL DEFAULT 'draft',
    sort_order  SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_pages_slug (slug),
    KEY idx_pages_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- page_sections
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS page_sections (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_id     INT UNSIGNED    NOT NULL,
    section_key VARCHAR(100)    NOT NULL COMMENT 'e.g. hero, intro, services, cta',
    heading     VARCHAR(255)    NULL,
    content     MEDIUMTEXT      NULL,
    image_path  VARCHAR(255)    NULL,
    sort_order  SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    status      ENUM('draft', 'published') NOT NULL DEFAULT 'published',
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_page_sections_page_sort (page_id, sort_order),
    CONSTRAINT fk_page_sections_page FOREIGN KEY (page_id) REFERENCES pages (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- services
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS services (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(150)    NOT NULL,
    slug        VARCHAR(170)    NOT NULL,
    summary     VARCHAR(255)    NULL,
    description MEDIUMTEXT      NULL,
    icon        VARCHAR(100)    NULL COMMENT 'icon identifier rendered by the frontend icon set',
    image_path  VARCHAR(255)    NULL,
    status      ENUM('draft', 'published') NOT NULL DEFAULT 'draft',
    sort_order  SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_services_slug (slug),
    KEY idx_services_status_sort (status, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- project_categories
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS project_categories (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100)    NOT NULL,
    slug        VARCHAR(120)    NOT NULL,
    sort_order  SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_project_categories_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- projects
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS projects (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id         INT UNSIGNED    NULL,
    title               VARCHAR(180)    NOT NULL,
    slug                VARCHAR(200)    NOT NULL,
    short_description   VARCHAR(255)    NULL,
    full_description    MEDIUMTEXT      NULL,
    city                VARCHAR(100)    NULL,
    project_type        VARCHAR(100)    NULL,
    completion_year     YEAR            NULL,
    duration_weeks      SMALLINT UNSIGNED NULL,
    cover_image_path    VARCHAR(255)    NULL,
    is_featured         TINYINT(1)      NOT NULL DEFAULT 0,
    status              ENUM('draft', 'published') NOT NULL DEFAULT 'draft',
    sort_order          SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    deleted_at          DATETIME        NULL COMMENT 'soft delete: projects carry many images, so hard delete is avoided',
    created_at          DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_projects_slug (slug),
    KEY idx_projects_category (category_id),
    KEY idx_projects_status_sort (status, sort_order),
    KEY idx_projects_featured (is_featured),
    CONSTRAINT fk_projects_category FOREIGN KEY (category_id) REFERENCES project_categories (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- project_images
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS project_images (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_id  INT UNSIGNED    NOT NULL,
    image_path  VARCHAR(255)    NOT NULL,
    alt_text    VARCHAR(255)    NULL,
    caption     VARCHAR(255)    NULL,
    image_role  ENUM('gallery', 'before', 'after') NOT NULL DEFAULT 'gallery',
    sort_order  SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_project_images_project_sort (project_id, sort_order),
    CONSTRAINT fk_project_images_project FOREIGN KEY (project_id) REFERENCES projects (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- testimonials
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS testimonials (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(150)    NOT NULL,
    client_city VARCHAR(100)    NULL,
    project_id  INT UNSIGNED    NULL,
    rating      TINYINT UNSIGNED NULL COMMENT '1-5',
    quote       MEDIUMTEXT      NOT NULL,
    photo_path  VARCHAR(255)    NULL,
    status      ENUM('draft', 'published') NOT NULL DEFAULT 'draft',
    sort_order  SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_testimonials_status_sort (status, sort_order),
    KEY idx_testimonials_project (project_id),
    CONSTRAINT fk_testimonials_project FOREIGN KEY (project_id) REFERENCES projects (id) ON DELETE SET NULL,
    CONSTRAINT chk_testimonials_rating CHECK (rating IS NULL OR (rating BETWEEN 1 AND 5))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- service_areas
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS service_areas (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    city        VARCHAR(100)    NOT NULL,
    county      VARCHAR(100)    NULL,
    state       CHAR(2)         NOT NULL DEFAULT 'CA',
    status      ENUM('draft', 'published') NOT NULL DEFAULT 'published',
    sort_order  SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_service_areas_city_state (city, state)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- leads
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS leads (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(150)    NOT NULL,
    email           VARCHAR(190)    NOT NULL,
    phone           VARCHAR(30)     NULL,
    city            VARCHAR(100)    NULL,
    project_type    VARCHAR(100)    NULL,
    message         MEDIUMTEXT      NULL,
    source_page     VARCHAR(255)    NULL,
    status          ENUM('new', 'contacted', 'qualified', 'closed', 'spam') NOT NULL DEFAULT 'new',
    ip_address      VARCHAR(45)     NULL,
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_leads_status (status),
    KEY idx_leads_email (email),
    KEY idx_leads_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- lead_attachments
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS lead_attachments (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    lead_id             INT UNSIGNED    NOT NULL,
    file_path           VARCHAR(255)    NOT NULL,
    original_filename   VARCHAR(255)    NULL,
    mime_type           VARCHAR(100)    NULL,
    file_size_bytes     INT UNSIGNED    NULL,
    created_at          DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_lead_attachments_lead (lead_id),
    CONSTRAINT fk_lead_attachments_lead FOREIGN KEY (lead_id) REFERENCES leads (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- seo_metadata (polymorphic: one row per entity_type + entity_id)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS seo_metadata (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    entity_type         VARCHAR(50)     NOT NULL COMMENT 'e.g. page, project, service',
    entity_id           INT UNSIGNED    NOT NULL,
    meta_title          VARCHAR(255)    NULL,
    meta_description    VARCHAR(320)    NULL,
    canonical_url       VARCHAR(255)    NULL,
    og_title            VARCHAR(255)    NULL,
    og_description      VARCHAR(320)    NULL,
    og_image_path       VARCHAR(255)    NULL,
    robots              VARCHAR(50)     NOT NULL DEFAULT 'index,follow',
    structured_data     JSON            NULL,
    created_at          DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_seo_metadata_entity (entity_type, entity_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
