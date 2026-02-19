-- Smart Film Makers Database Schema

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Projects table
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    idea TEXT NOT NULL,
    genre VARCHAR(100),
    target_audience VARCHAR(100),
    language VARCHAR(50) DEFAULT 'English',
    status ENUM('draft', 'generated', 'exported') DEFAULT 'draft',
    
    -- Story Foundation
    logline TEXT,
    theme TEXT,
    emotional_tone VARCHAR(100),
    unique_hook TEXT,
    
    -- Screenplay Structure
    act1_breakdown TEXT,
    act2_breakdown TEXT,
    act3_breakdown TEXT,
    
    -- Character Profiles
    main_characters JSON,
    supporting_characters JSON,
    
    -- Screenplay Draft
    screenplay_draft LONGTEXT,
    
    -- Production Plan
    budget_estimate VARCHAR(50),
    locations JSON,
    cast_crew_requirements JSON,
    shooting_schedule TEXT,
    
    -- Pitch Deck
    one_line_pitch TEXT,
    market_appeal TEXT,
    visual_style_reference TEXT,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Project Versions (for versioning)
CREATE TABLE project_versions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    version_number INT NOT NULL,
    content JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Admin Users table
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('super_admin', 'content_manager') DEFAULT 'content_manager',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- AI Prompt Templates
CREATE TABLE ai_prompts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    template_text TEXT NOT NULL,
    module_type ENUM('story', 'characters', 'production', 'pitch_deck') NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- User Settings
CREATE TABLE user_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    setting_name VARCHAR(100) NOT NULL,
    setting_value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_setting (user_id, setting_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Analytics/Usage Tracking
CREATE TABLE usage_analytics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    project_id INT,
    action VARCHAR(100) NOT NULL,
    tokens_used INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Export History
CREATE TABLE export_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    project_id INT NOT NULL,
    export_type ENUM('pdf', 'docx', 'txt') NOT NULL,
    file_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert default admin user (password: password)
INSERT INTO admin_users (name, email, password, role) VALUES 
('Super Admin', 'admin@smartfilmmakers.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin');

-- Insert default AI prompt templates
INSERT INTO ai_prompts (name, template_text, module_type) VALUES 
('Story Foundation', 'Generate a compelling story foundation for a film with the following details:
Idea: {idea}
Genre: {genre}
Target Audience: {target_audience}
Language: {language}

Please provide:
1. A catchy title
2. A compelling logline (1-2 sentences)
3. The main theme
4. Emotional tone
5. Unique hook that makes this story special', 'story'),

('Character Generation', 'Create detailed character profiles for this film:
Title: {title}
Idea: {idea}
Genre: {genre}

Generate:
1. Main characters (3-5) with:
   - Name and role
   - Personality traits
   - Backstory
   - Character arc
   - Motivations and conflicts

2. Supporting characters (2-3) with brief descriptions', 'characters'),

('Screenplay Structure', 'Create a 3-act structure breakdown for:
Title: {title}
Logline: {logline}
Characters: {characters}

Provide detailed breakdown for:
Act 1: Setup (25%)
- Inciting incident
- Key plot points
- Character introductions

Act 2: Confrontation (50%)
- Rising action
- Midpoint
- Major conflicts
- Character development

Act 3: Resolution (25%)
- Climax
- Falling action
- Resolution
- Character transformation', 'story'),

('Production Plan', 'Create a comprehensive production plan for:
Title: {title}
Genre: {genre}
Story: {story}

Include:
1. Budget estimate (low/medium/high)
2. Key locations needed
3. Cast requirements (main roles, supporting, extras)
4. Crew requirements
5. Estimated shooting schedule
6. Special equipment or effects needed', 'production'),

('Pitch Deck', 'Create a compelling pitch deck summary for:
Title: {title}
Logline: {logline}
Genre: {genre}
Market: {target_audience}

Include:
1. One-line pitch
2. Market appeal and target audience analysis
3. Visual style references (similar films)
4. Unique selling points
5. Distribution potential', 'pitch_deck');

-- Create indexes for better performance
CREATE INDEX idx_projects_user_id ON projects(user_id);
CREATE INDEX idx_projects_status ON projects(status);
CREATE INDEX idx_projects_created_at ON projects(created_at);
CREATE INDEX idx_usage_analytics_user_id ON usage_analytics(user_id);
CREATE INDEX idx_usage_analytics_created_at ON usage_analytics(created_at);
CREATE INDEX idx_export_history_user_id ON export_history(user_id);
CREATE INDEX idx_export_history_project_id ON export_history(project_id);
