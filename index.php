<?php
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Film Makers - AI-Powered Script Generation</title>
    <meta name="description" content="Turn your ideas into film-ready scripts with AI. Generate compelling stories, characters, and production plans instantly.">
    
    <!-- MDBootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                🎬 <span class="fw-bold">Smart Film Makers</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#how-it-works">How It Works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials">Testimonials</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#faq">FAQ</a>
                    </li>
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary ms-2" href="register.php">Get Started</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6">
                    <div class="text-white fade-in">
                        <h1 class="display-3 fw-bold mb-4">
                            Turn Ideas into<br>
                            <span class="text-gradient">Film-Ready Scripts</span><br>
                            with AI
                        </h1>
                        <p class="lead mb-4">
                            Transform your creative vision into professional screenplays, character profiles, 
                            and production plans in minutes. Powered by advanced AI technology.
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            <?php if (isLoggedIn()): ?>
                                <a href="dashboard.php" class="btn btn-light btn-lg">
                                    <i class="fas fa-rocket me-2"></i>Go to Dashboard
                                </a>
                            <?php else: ?>
                                <a href="register.php" class="btn btn-light btn-lg">
                                    <i class="fas fa-rocket me-2"></i>Create Your Film Project
                                </a>
                                <a href="#how-it-works" class="btn btn-outline-light btn-lg">
                                    <i class="fas fa-play-circle me-2"></i>Watch Demo
                                </a>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Stats -->
                        <div class="row mt-5">
                            <div class="col-4">
                                <h3 class="fw-bold">10K+</h3>
                                <p class="small">Scripts Generated</p>
                            </div>
                            <div class="col-4">
                                <h3 class="fw-bold">5K+</h3>
                                <p class="small">Filmmakers</p>
                            </div>
                            <div class="col-4">
                                <h3 class="fw-bold">98%</h3>
                                <p class="small">Satisfaction</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="position-relative">
                        <img src="https://via.placeholder.com/600x400/6366f1/ffffff?text=AI+Script+Generation" 
                             alt="AI Script Generation" class="img-fluid rounded-3 shadow-xl">
                        <div class="position-absolute top-0 start-0 translate-middle">
                            <div class="bg-white rounded-circle p-3 shadow-lg">
                                <i class="fas fa-film fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="position-absolute bottom-0 end-0 translate-middle">
                            <div class="bg-white rounded-circle p-3 shadow-lg">
                                <i class="fas fa-magic fa-2x text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Powerful Features for Filmmakers</h2>
                <p class="lead text-muted">Everything you need to bring your story to life</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <a href="<?php echo isLoggedIn() ? 'create-project.php' : 'register.php'; ?>" class="text-decoration-none">
                                    <div class="bg-primary bg-gradient rounded-circle p-3 d-inline-block shadow-sm">
                                        <i class="fas fa-lightbulb fa-2x text-white"></i>
                                    </div>
                                </a>
                            </div>
                            <h4 class="card-title">Idea → Screenplay</h4>
                            <p class="card-text text-muted">
                                Transform your simple ideas into complete, structured screenplays with proper formatting and industry standards.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <a href="<?php echo isLoggedIn() ? 'character-generation.php' : 'register.php'; ?>" class="text-decoration-none">
                                    <div class="bg-secondary bg-gradient rounded-circle p-3 d-inline-block shadow-sm">
                                        <i class="fas fa-users fa-2x text-white"></i>
                                    </div>
                                </a>
                            </div>
                            <h4 class="card-title">Character Generation</h4>
                            <p class="card-text text-muted">
                                Create deep, compelling characters with detailed backstories, personalities, and character arcs.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <a href="<?php echo isLoggedIn() ? 'production-plan.php' : 'register.php'; ?>" class="text-decoration-none">
                                    <div class="bg-info bg-gradient rounded-circle p-3 d-inline-block shadow-sm">
                                        <i class="fas fa-clipboard-list fa-2x text-white"></i>
                                    </div>
                                </a>
                            </div>
                            <h4 class="card-title">Production Planning</h4>
                            <p class="card-text text-muted">
                                Generate comprehensive production plans including budgets, locations, casting requirements, and shooting schedules.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <a href="<?php echo isLoggedIn() ? 'export.php' : 'register.php'; ?>" class="text-decoration-none">
                                    <div class="bg-success bg-gradient rounded-circle p-3 d-inline-block shadow-sm">
                                        <i class="fas fa-file-export fa-2x text-white"></i>
                                    </div>
                                </a>
                            </div>
                            <h4 class="card-title">Multiple Export Formats</h4>
                            <p class="card-text text-muted">
                                Export your work in PDF, DOCX, or TXT formats for easy sharing and collaboration with your team.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <a href="<?php echo isLoggedIn() ? 'pitch-deck.php' : 'register.php'; ?>" class="text-decoration-none">
                                    <div class="bg-warning bg-gradient rounded-circle p-3 d-inline-block shadow-sm">
                                        <i class="fas fa-palette fa-2x text-white"></i>
                                    </div>
                                </a>
                            </div>
                            <h4 class="card-title">Pitch Deck Creation</h4>
                            <p class="card-text text-muted">
                                Generate professional pitch decks with one-line pitches, market analysis, and visual style references.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <a href="<?php echo isLoggedIn() ? 'dashboard.php' : 'register.php'; ?>" class="text-decoration-none">
                                    <div class="bg-danger bg-gradient rounded-circle p-3 d-inline-block shadow-sm">
                                        <i class="fas fa-cloud fa-2x text-white"></i>
                                    </div>
                                </a>
                            </div>
                            <h4 class="card-title">Cloud Storage</h4>
                            <p class="card-text text-muted">
                                Access your projects from anywhere with secure cloud storage and automatic backup of all your work.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section id="how-it-works" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">How It Works</h2>
                <p class="lead text-muted">From idea to production in 4 simple steps</p>
            </div>
            
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <span class="fw-bold">1</span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5>Enter Your Idea</h5>
                                    <p class="text-muted">Describe your film concept, genre, and target audience</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <span class="fw-bold">2</span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5>AI Generation</h5>
                                    <p class="text-muted">Our AI analyzes your input and generates comprehensive content</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <span class="fw-bold">3</span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5>Review & Edit</h5>
                                    <p class="text-muted">Review the generated content and make any needed adjustments</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <span class="fw-bold">4</span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5>Export & Share</h5>
                                    <p class="text-muted">Export your final script in multiple formats and share with your team</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">What Filmmakers Say</h2>
                <p class="lead text-muted">Join thousands of satisfied creators</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex mb-3">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <p class="card-text">
                                "This platform transformed my writing process. What used to take weeks now takes hours. 
                                The AI understands my vision and helps me bring it to life."
                            </p>
                            <div class="d-flex align-items-center">
                                <img src="https://via.placeholder.com/50x50" alt="User" class="rounded-circle me-3">
                                <div>
                                    <h6 class="mb-0">Sarah Johnson</h6>
                                    <small class="text-muted">Indie Filmmaker</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex mb-3">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <p class="card-text">
                                "As a film student, this tool has been invaluable for learning screenplay structure 
                                and developing my ideas. The character generation is particularly impressive."
                            </p>
                            <div class="d-flex align-items-center">
                                <img src="https://via.placeholder.com/50x50" alt="User" class="rounded-circle me-3">
                                <div>
                                    <h6 class="mb-0">Michael Chen</h6>
                                    <small class="text-muted">Film Student</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex mb-3">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <p class="card-text">
                                "The production planning features save me countless hours. Budget estimates, 
                                location scouting, and casting requirements are all generated automatically."
                            </p>
                            <div class="d-flex align-items-center">
                                <img src="https://via.placeholder.com/50x50" alt="User" class="rounded-circle me-3">
                                <div>
                                    <h6 class="mb-0">Emily Rodriguez</h6>
                                    <small class="text-muted">Producer</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Frequently Asked Questions</h2>
                <p class="lead text-muted">Everything you need to know about Smart Film Makers</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item border mb-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    How does the AI generate screenplays?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Our AI uses advanced natural language processing trained on thousands of professional screenplays. 
                                    It analyzes your input and generates content that follows industry standards for formatting, 
                                    structure, and storytelling techniques.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item border mb-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Can I edit the AI-generated content?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Absolutely! All generated content is fully editable. You can modify, add to, or regenerate 
                                    any section of your screenplay, characters, or production plan to perfectly match your vision.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item border mb-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    What export formats are available?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    You can export your projects in PDF (perfect for sharing and printing), DOCX (for further editing 
                                    in Word or Google Docs), and TXT (plain text for maximum compatibility).
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item border mb-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    Is my content secure and private?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes, your privacy is our top priority. All your projects are stored securely with encryption, 
                                    and you retain full ownership of all content you create. We never share your work with third parties.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item border">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                    Can I collaborate with my team?
                                </button>
                            </h2>
                            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    While individual accounts are private, you can easily export and share your projects with team members. 
                                    We're also working on dedicated collaboration features for future releases.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container text-center">
            <h2 class="display-5 fw-bold mb-4">Ready to Create Your Masterpiece?</h2>
            <p class="lead mb-4">Join thousands of filmmakers using AI to bring their stories to life</p>
            <?php if (isLoggedIn()): ?>
                <a href="dashboard.php" class="btn btn-light btn-lg">
                    <i class="fas fa-rocket me-2"></i>Go to Dashboard
                </a>
            <?php else: ?>
                <a href="register.php" class="btn btn-light btn-lg me-3">
                    <i class="fas fa-user-plus me-2"></i>Start Free Trial
                </a>
                <a href="#features" class="btn btn-outline-light btn-lg">
                    Learn More
                </a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="mb-3">🎬 Smart Film Makers</h5>
                    <p class="text-muted">
                        Transform your creative ideas into professional film scripts with the power of AI.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin fa-lg"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="mb-3">Product</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#features" class="text-muted text-decoration-none">Features</a></li>
                        <li class="mb-2"><a href="#how-it-works" class="text-muted text-decoration-none">How It Works</a></li>
                        <li class="mb-2"><a href="pricing.php" class="text-muted text-decoration-none">Pricing</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">API</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="mb-3">Company</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">About</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Blog</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Careers</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="mb-3">Support</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Help Center</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Documentation</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Community</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Status</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="mb-3">Legal</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Privacy Policy</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Terms of Service</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Cookie Policy</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">GDPR</a></li>
                    </ul>
                </div>
            </div>
            
            <hr class="border-secondary my-4">
            
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-muted mb-0">&copy; 2024 Smart Film Makers. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">
                        Made with <i class="fas fa-heart text-danger"></i> for filmmakers worldwide
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- MDBootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>
</body>
</html>
