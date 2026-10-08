-- ================================================
-- NEXORA LABS — COMPLETE DATABASE
-- Fresh install with demo data
-- ================================================

CREATE DATABASE IF NOT EXISTS nexora_labs
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE nexora_labs;

-- ================================================
-- USERS
-- ================================================
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('super_admin','admin','project_manager','support','client') DEFAULT 'client',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ================================================
-- SERVICES
-- ================================================
CREATE TABLE services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(100) UNIQUE NOT NULL,
  title VARCHAR(150) NOT NULL,
  short_desc TEXT,
  icon VARCHAR(50),
  features TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO services (slug, title, short_desc, icon, features) VALUES
('web-development','Website Development','Business websites, e-commerce, landing pages and custom CMS.','bi-globe2','Business Websites, Corporate Websites, E-commerce, Landing Pages, WordPress, Custom CMS'),
('software-development','Software Development','Custom software, ERP, CRM, HRMS and billing systems.','bi-cpu','Custom Software, ERP, CRM, HRMS, School Management, Inventory, Billing'),
('app-development','Application Development','Web apps, mobile apps, PWA and SaaS platforms.','bi-phone','Web Applications, Mobile Applications, PWA, SaaS Platforms'),
('ui-ux-design','UI/UX Design','Website UI, dashboard UI, design systems and prototypes.','bi-palette','Website UI, Dashboard UI, Mobile App UI, Design Systems, Prototypes'),
('ai-automation','AI & Automation','AI integration, chatbots, workflow and document automation.','bi-robot','AI Integration, Business Automation, AI Chatbots, Workflow Automation, Document Automation'),
('cloud-infra','Cloud & Infrastructure','Deployment, hosting, cloud migration and monitoring.','bi-cloud','Deployment, Hosting Setup, Cloud Migration, Database Setup, Monitoring'),
('digital-marketing','Digital Marketing','SEO, social media, performance marketing and content.','bi-megaphone','SEO, Social Media, Performance Marketing, Content Strategy'),
('maintenance','Maintenance & Support','Bug fixing, security, performance and backup.','bi-tools','Bug Fixing, Security, Performance Optimization, Backup, Technical Support');

-- ================================================
-- PORTFOLIO
-- ================================================
CREATE TABLE portfolio (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  category VARCHAR(80),
  technology VARCHAR(200),
  description TEXT,
  image VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO portfolio (title, category, technology, description) VALUES
('School ERP System','ERP','PHP 8, MySQL, Bootstrap','Complete school management with attendance, fees, exams, timetables and parent portal.'),
('Multi-Vendor E-commerce','E-commerce','PHP, MySQL, Stripe, Redis','Full-featured marketplace with vendor dashboards, commission engine and analytics.'),
('Sales CRM Platform','CRM','PHP, Chart.js, REST API','Pipeline management, lead scoring, email automation and revenue forecasting.'),
('Mobile Banking App','Mobile Apps','Flutter, Node.js, PostgreSQL','Biometric auth, instant transfers, bill payments and push notifications.'),
('Hospital Management','Software','PHP, MySQL, Bootstrap','Patient records, appointment booking, billing, pharmacy and lab integration.'),
('Learning Management System','Websites','PHP, MySQL, WebRTC','Live classes, assignments, quizzes, certificates and progress tracking.'),
('AI Customer Support Bot','AI','Python, OpenAI, FastAPI','GPT-powered assistant that resolves 70% of support queries automatically.'),
('Inventory & Billing','Software','PHP, MySQL, Thermal Printer API','Barcode scanning, stock alerts, GST invoicing and multi-warehouse support.');

-- ================================================
-- TESTIMONIALS
-- ================================================
CREATE TABLE testimonials (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  designation VARCHAR(100),
  company VARCHAR(100),
  rating INT DEFAULT 5,
  review TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO testimonials (name, designation, company, rating, review) VALUES
('Rahul Mehta','CTO','EduCore',5,'Nexora delivered our ERP ahead of schedule. Communication was excellent throughout the project.'),
('Priya Sharma','Founder','ShopEasy',5,'Our conversion rate jumped 38% after the new website. The team really understands business.'),
('Amit Verma','Director','FinTrust',5,'Professional team, clean code and great post-launch support. Highly recommended.'),
('Sneha Patel','Product Manager','HealthBridge',4,'They built our patient portal in 6 weeks. Responsive, secure and easy to use.'),
('Vikram Singh','CEO','LogiTrack',5,'Best agency we have worked with. They took ownership of the entire project.');

-- ================================================
-- BLOGS
-- ================================================
CREATE TABLE blogs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200),
  slug VARCHAR(200) UNIQUE,
  category VARCHAR(80),
  excerpt TEXT,
  content LONGTEXT,
  author VARCHAR(100),
  reading_time INT DEFAULT 5,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO blogs (title, slug, category, excerpt, content, author, reading_time) VALUES
('Why Every Business Needs a Custom CRM in 2026','why-custom-crm','Software','Off-the-shelf CRMs force you into their workflow. Custom CRMs fit yours. Here is when and why to build one.','<p>Full article coming soon...</p>','Nexora Team',7),
('The Real Cost of Technical Debt','cost-of-tech-debt','Business','Shortcuts today become emergencies tomorrow. Learn how to spot, measure and pay down tech debt.','<p>Full article coming soon...</p>','Nexora Team',6),
('AI Automation for Small Businesses','ai-automation-smb','AI','Practical AI workflows you can implement this month — no data science team required.','<p>Full article coming soon...</p>','Nexora Team',8),
('Core Web Vitals: The 2026 Playbook','core-web-vitals-2026','Web Development','Google changed the rules again. Here is how to keep your site fast and rankable.','<p>Full article coming soon...</p>','Nexora Team',9),
('Security Checklist for PHP Applications','php-security-checklist','Technology','The 12-point security audit we run before every deployment. Steal it.','<p>Full article coming soon...</p>','Nexora Team',10),
('From Monolith to Microservices: When and Why','monolith-to-microservices','Digital Transformation','Microservices are not always the answer. Learn when they actually help.','<p>Full article coming soon...</p>','Nexora Team',8);

-- ================================================
-- FAQS
-- ================================================
CREATE TABLE faqs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category VARCHAR(80),
  question TEXT,
  answer TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO faqs (category, question, answer) VALUES
('General','How long does a typical project take?','A landing page takes 1-2 weeks, a business website 3-4 weeks, a custom web app 6-10 weeks, and a full ERP 3-6 months depending on scope.'),
('General','Do you work with clients outside India?','Yes. We work with clients across the US, UK, UAE, Australia and Europe. We handle time zone differences with async updates and scheduled calls.'),
('General','What industries do you specialize in?','Education, healthcare, e-commerce, real estate, logistics, manufacturing, hospitality and professional services.'),
('General','How do we get started?','Simply request a quote or book a consultation. We will schedule a discovery call within 24 hours to understand your needs.'),
('Pricing','Do you provide fixed-price quotes?','Yes. After a discovery call, we deliver a detailed fixed-price proposal with milestones, timeline and deliverables.'),
('Pricing','What is your typical budget range?','Small websites start around $1,500. Custom web apps range from $5,000-$25,000. Enterprise ERP systems start at $25,000+.'),
('Pricing','Do you offer payment plans?','Yes. We split projects into milestones — typically 30% advance, 40% mid-project, 30% on delivery.'),
('Pricing','Are there any hidden charges?','No. Every quote clearly lists scope, deliverables and cost. Any scope changes are quoted separately and approved by you first.'),
('Development','What is your development process?','Discovery -> Design -> Sprint-based development -> QA -> Deployment -> Support. Weekly demos keep you in the loop.'),
('Development','Will I own the source code?','Yes. Upon full payment, you own 100% of the source code, designs and assets. No lock-in.'),
('Development','Can you work with our existing team?','Absolutely. We can augment your team, work as an outsourced partner, or hand over documentation for your team to maintain.'),
('Development','What technologies do you use?','PHP 8, MySQL, JavaScript, Bootstrap, React, Node.js, Python, Flutter, WordPress and modern cloud platforms like AWS.'),
('Support','What happens after launch?','Every project includes 30 days of free bug-fix support. After that, monthly maintenance plans start at $200/month.'),
('Support','Do you offer 24/7 support?','Business-critical clients get 24/7 emergency support. Standard clients get support during business hours with 4-hour response time.'),
('Support','Can you take over an existing project?','Yes. We audit the codebase, document it, and continue development. We have rescued many abandoned projects.'),
('Support','How do I report a bug?','Log into your client dashboard and create a support ticket. You can attach screenshots and track progress in real time.'),
('Security','How do you keep my data safe?','Encrypted connections, hashed passwords, prepared SQL statements, CSRF protection, role-based access and regular security audits.'),
('Security','Do you sign NDAs?','Yes, we sign NDAs on request before any project discussion. Your idea stays yours.'),
('Security','Where is my data hosted?','On secure cloud servers with daily backups. You can choose AWS, DigitalOcean or your own infrastructure.'),
('Payments','Which payment methods do you accept?','Bank transfer, UPI, PayPal, Wise and credit cards. International clients are invoiced in USD.'),
('Payments','Do you offer refunds?','Milestone-based refunds are available if we fail to deliver agreed scope. See our refund policy for details.'),
('Payments','When do I need to pay?','Typically 30% advance to start, 40% at mid-project milestone, and 30% on final delivery and handover.');

-- ================================================
-- QUOTES (Quote Requests)
-- ================================================
CREATE TABLE quotes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  enquiry_id VARCHAR(30) UNIQUE,
  name VARCHAR(100),
  email VARCHAR(150),
  phone VARCHAR(30),
  company VARCHAR(150),
  service VARCHAR(100),
  budget VARCHAR(50),
  timeline VARCHAR(50),
  requirements TEXT,
  status ENUM('new','review','quoted','closed') DEFAULT 'new',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ================================================
-- CONSULTATIONS
-- ================================================
CREATE TABLE consultations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(150),
  phone VARCHAR(30),
  company VARCHAR(150),
  service VARCHAR(100),
  preferred_date DATE,
  preferred_time VARCHAR(20),
  message TEXT,
  status ENUM('pending','confirmed','done') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ================================================
-- CONTACT ENQUIRIES
-- ================================================
CREATE TABLE contact_enquiries (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(150),
  phone VARCHAR(30),
  subject VARCHAR(200),
  message TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ================================================
-- DONE! Database is ready.
-- Now run create-admin.php to add admin user.
-- ================================================